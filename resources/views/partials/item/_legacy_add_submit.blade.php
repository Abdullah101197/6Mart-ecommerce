@php
    $context = $context ?? 'admin';
    $storeUrl = $storeUrl ?? '#';
    $listUrl = $listUrl ?? '#';
    $pendingListUrl = $pendingListUrl ?? '#';
@endphp

@if ($context === 'admin')
    $('#item_form').on('submit', function(e) {
        $('#submitButton').attr('disabled', true);
        e.preventDefault();

        let $form = $(this);
        if (!$form.valid()) {
            return false;
        }

        if (!validateImageSize('#customFileEg1', "Item image")) {
            return;
        }

        let fileInput = $('#customFileEg1')[0];
        if (fileInput.files.length > 0) {
            let fileSize = fileInput.files[0].size;
            if (fileSize > 1024 * 1024) {
                toastr.error('Image size should not exceed 2MB', {
                    CloseButton: true,
                    ProgressBar: true
                });
                return;
            }
        }

        let formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post({
            url: '{{ $storeUrl }}',
            data: $('#item_form').serialize(),
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
                } else {
                    toastr.success("{{ translate('messages.product_added_successfully') }}", {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function() {
                        location.href = "{{ $listUrl }}";
                    }, 1000);
                }
            }
        });
    });
@else
    $('#item_form').on('submit', function(e) {
        e.preventDefault();

        if(typeof FormValidation != 'undefined' && !FormValidation.validateForm(this)) {
            return false;
        }

        let formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.post({
            url: '{{ $storeUrl }}',
            data: $('#item_form').serialize(),
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

                if (data.product_approval) {
                    toastr.success(data.product_approval, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    setTimeout(function() {
                        location.href = '{{ $pendingListUrl }}';
                    }, 2000);
                }

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
@endif

