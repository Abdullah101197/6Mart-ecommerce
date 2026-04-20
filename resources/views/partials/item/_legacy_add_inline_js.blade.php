@php
    $context = $context ?? 'admin';
    $variantCombinationUrl = $variantCombinationUrl ?? '#';
    $brandListUrl = $brandListUrl ?? null;
    $variantStockValue = $variantStockValue ?? '1';
    $variantStockIsJs = $variantStockIsJs ?? false;
@endphp

        function add_more_customer_choice_option(i, name) {
            let n = name;

            $('#customer_choice_options').append(
                `<div class="__choos-item"><div><input type="hidden" name="choice_no[]" value="${i}"><input type="text" class="form-control d-none" name="choice[]" value="${n}" placeholder="{{ translate('messages.choice_title') }}" readonly> <label class="form-label">${n}</label> </div><div><input type="text" class="form-control combination_update" name="choice_options_${i}[]" placeholder="{{ translate('messages.enter_choice_values') }}" data-role="tagsinput"></div></div>`
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        function combination_update() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ $variantCombinationUrl }}',
                data: $('#item_form').serialize() + '&stock=' + {!! $variantStockIsJs ? $variantStockValue : json_encode((string) $variantStockValue) !!},
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#loading').hide();
                    $('#variant_combination').html(data.view);
                    if (data.length < 1) {
                        $('input[name="current_stock"]').attr("readonly", false);
                    }
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

        function initImagePicker() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'item_images[]',
                maxCount: 5,
                rowHeight: '176px !important',
                groupClassName: 'spartan_item_wrapper min-w-176px max-w-176px',
                maxFileSize: 1024 * 1024 * 2,
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
        });

        $('#reset_btn').click(function() {
            @if ($context === 'admin')
            $('#module_id').val(null).trigger('change');
            $('#store_id').val(null).trigger('change');
            @endif
            $('#category_id').val(null).trigger('change');
            $('#sub-categories').val(null).trigger('change');
            $('#unit').val(null).trigger('change');
            $('#veg').val(0).trigger('change');
            $('#add_on').val(null).trigger('change');
            $('#discount_type').val(null).trigger('change');
            $('#choice_attributes').val(null).trigger('change');
            $('#customer_choice_options').empty().trigger('change');
            $('#variant_combination').empty().trigger('change');
            $('#viewer').attr('src', "{{ asset('assets/admin/img/upload.png') }}");
            @if ($context === 'admin')
            $('#customFileEg1').val(null).trigger('change');
            @endif
            $("#coba").empty();
            initImagePicker();
        })

