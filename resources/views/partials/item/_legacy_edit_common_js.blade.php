@php
    $context = $context ?? 'admin';
    $variantCombinationUrl = $variantCombinationUrl ?? '#';
    $brandListUrl = $brandListUrl ?? null;
    $variantStockValue = $variantStockValue ?? '1';
    $variantStockIsJs = $variantStockIsJs ?? false;
    $listUrl = $listUrl ?? '#';
    $pendingListUrl = $pendingListUrl ?? null;
    $hideQuantityOnMulti = $hideQuantityOnMulti ?? false;
    $imageMaxBytes = $imageMaxBytes ?? (2 * 1024 * 1024);
@endphp

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ $variantCombinationUrl }}',
                data: $('#product_form').serialize() + '&stock=' + {!! $variantStockIsJs ? $variantStockValue : json_encode((string) $variantStockValue) !!},
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    $('#variant_combination').html(data.view);
                    @if ($hideQuantityOnMulti)
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                    @else
                    if (data.length < 1) {
                        $('input[name="current_stock"]').attr("readonly", false);
                    }
                    @endif
                }
            });
        }

        @if ($brandListUrl)
        $('#brand_id').select2({
            ajax: {
                url: '{{ $brandListUrl }}',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page,
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                __port: function(params, success, failure) {
                    let $request = $.ajax(params);
                    $request.then(success);
                    $request.fail(failure);
                    return $request;
                }
            }
        });
        @endif

        $('#product_form').on('submit', function() {
            let $form = $(this);
            if (!$form.valid()) {
                return false;
            }

            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: $('.route_url').val(),
                data: $('#product_form').serialize(),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    if (data.errors) {
                        for (let i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    }
                    @if ($pendingListUrl)
                    if (data.product_approval) {
                        toastr.success(data.product_approval, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ $pendingListUrl }}';
                        }, 2000);
                    }
                    @endif
                    if (data.success) {
                        toastr.success(data.success, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function() {
                            location.href = '{{ $listUrl }}';
                        }, 2000);
                    }
                }
            });
        });

        function initImagePicker() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'item_images[]',
                maxCount: 5,
                rowHeight: '176px !important',
                groupClassName: 'spartan_item_wrapper min-w-176px max-w-176px',
                maxFileSize: {{ $imageMaxBytes }},
                placeholderImage: {
                    image: "{{ asset('assets/admin/img/upload-img.png') }}",
                    width: '176px'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {
                    setTimeout(function() {
                        let $newInput = $("#coba .spartan_item_wrapper").last();
                        if ($newInput.length) {
                            $newInput[0].scrollIntoView({
                                behavior: "smooth",
                                inline: "end",
                                block: "nearest"
                            });
                        }
                    }, 50);
                },
                onExtensionErr: function(index, file) {
                    toastr.error("{{ translate('messages.please_only_input_png_or_jpg_type_file') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error("{{ translate('messages.file_size_too_big') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        $(function() {
            initImagePicker();
            update_qty();
        });

        function update_qty() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val() || 0);
            }
            if (qty_elements.length > 0) {
                $('input[name="current_stock"]').attr("readonly", true);
                $('input[name="current_stock"]').val(total_qty);
            } else {
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }

        $(document).on('keyup', 'input[name^="stock_"]', function() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val() || 0);
            }
            $('input[name="current_stock"]').val(total_qty);
        });

