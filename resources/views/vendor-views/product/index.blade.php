@extends('layouts.vendor.app')

@section('title', translate('messages.add_new_item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/admin/css/custom.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/css/upload-single-image.css')}}">

@endpush

@section('content')
    @php($module_type = \App\CentralLogics\Helpers::get_store_data()->module->module_type)
    @php(Config::set('module.current_module_type', $module_type))
    @php($openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config'))
    @include('partials.item._legacy_add_form', [
        'context' => 'vendor',
        'currentModuleType' => $module_type,
    ])

@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ asset('assets/admin') }}/js/view-pages/vendor/product-index.js"></script>


    <script src="{{ asset('assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
    @if ($module_type == 'food')
        <script src="{{ asset('assets/admin/js/AI/products/variation-setup-auto-fill.js') }}"></script>
    @else
        <script src="{{ asset('assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>
    @endif
    <script src="{{ asset('assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>

    <script src="{{ asset('assets/admin/js/AI/products/ai-sidebar.js') }}"></script>

    <script src="{{ asset('/assets/admin/js/AI/products/compressor/image-compressor.js') }}"></script>
    <script src="{{ asset('/assets/admin/js/AI/products/compressor/compressor.min.js') }}"></script>



    <script>
        "use strict";


        function validateImageSize(inputSelector, imageType = "Image", maxSizeMB = 2) {
            let fileInput = $(inputSelector)[0];
            if (fileInput && fileInput.files.length > 0) {
                let fileSize = fileInput.files[0].size;
                if (fileSize > maxSizeMB * 1024 * 1024) {
                    toastr.error(`${imageType} size should not exceed ${maxSizeMB}MB`, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    return false;
                }
            }
            return true;
        }

        mod_type = "{{ $module_type }}";

        $(document).ready(function() {
            $("#add_new_option_button").click(function(e) {
                add_new_option_button();
            });

        });

        // INITIALIZATION OF SELECT2
        // =======================================================
        $('.js-select2-custom').each(function() {
            let select2 = $.HSCore.components.HSSelect2.init($(this));
        });

        function add_new_option_button() {
            $('#empty-variation').hide();
            count++;
            let add_option_view = `
                    <div class="__bg-F8F9FC-card view_new_option mb-2">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <label class="form-check form--check">
                                    <input id="options[` + count + `][required]" name="options[` + count + `][required]" class="form-check-input" type="checkbox">
                                    <span class="form-check-label">{{ translate('Required') }}</span>
                                </label>
                                <div>
                                    <button type="button" class="btn btn-danger btn-sm delete_input_button"
                                        title="{{ translate('Delete') }}">
                                        <i class="tio-add-to-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-xl-4 col-lg-6">
                                    <label for="">{{ translate('name') }}</label>
                                    <input required name=options[` + count +
                `][name] class="form-control new_option_name" type="text" data-count="` +
                count + `">
                                </div>

                                <div class="col-xl-4 col-lg-6">
                                    <div>
                                        <label class="input-label text-capitalize d-flex align-items-center"><span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                                        </label>
                                        <div class="resturant-type-group px-0">
                                            <label class="form-check form--check mr-2 mr-md-4">
                                                <input class="form-check-input show_min_max" data-count="` + count + `" type="radio" value="multi"
                                                name="options[` + count + `][type]" id="type` + count +
                `" checked
                                                >
                                                <span class="form-check-label">
                                                    {{ translate('Multiple Selection') }}
                </span>
                </label>

                <label class="form-check form--check mr-2 mr-md-4">
                <input class="form-check-input hide_min_max" data-count="` + count + `" type="radio" value="single"
                    name="options[` + count + `][type]" id="type` + count +
                `"
                                                >
                                                <span class="form-check-label">
                                                    {{ translate('Single Selection') }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-6">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <label for="">{{ translate('Min') }}</label>
                                            <input id="min_max1_` + count + `" required  name="options[` + count + `][min]" class="form-control" type="number" min="1">
                                        </div>
                                        <div class="col-6">
                                            <label for="">{{ translate('Max') }}</label>
                                            <input id="min_max2_` + count + `"   required name="options[` + count + `][max]" class="form-control" type="number" min="1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="option_price_` + count + `" >
                                <div class="bg-white border rounded p-3 pb-0 mt-3">
                                    <div  id="option_price_view_` + count + `">
                                        <div class="row g-3 add_new_view_row_class mb-3">
                                            <div class="col-md-4 col-sm-6">
                                                <label for="">{{ translate('Option_name') }}</label>
                                                <input class="form-control" required type="text" name="options[` +
                count +
                `][values][0][label]" id="">
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <label for="">{{ translate('Additional_price') }}</label>
                                                <input class="form-control" required type="number" min="0" step="0.01" name="options[` +
                count + `][values][0][optionPrice]" id="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3 p-3 mr-1 d-flex "  id="add_new_button_` + count +
                `">
                                        <button type="button" class="btn btn--primary btn-outline-primary add_new_row_button" data-count="` +
                count + `">{{ translate('Add_New_Option') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

            $("#add_new_option").append(add_option_view);
        }



        function add_new_row_button(data) {
            count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            let add_new_row_view = `
            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Option_name') }}</label>
                        <input class="form-control" required type="text" name="options[` + count + `][values][` +
                countRow + `][label]" id="">
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Additional_price') }}</label>
                        <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                count +
                `][values][` + countRow + `][optionPrice]" id="">
                    </div>
                    <div class="col-sm-2 max-sm-absolute">
                        <label class="d-none d-sm-block">&nbsp;</label>
                        <div class="mt-1">
                            <button type="button" class="btn btn-danger btn-sm deleteRow"
                                title="{{ translate('Delete') }}">
                                <i class="tio-add-to-trash"></i>
                            </button>
                        </div>
                </div>
            </div>`;
            $('#option_price_view_' + data).append(add_new_row_view);

        }

        // $('#item_form').on('keydown', function(e) {
        //         if (e.key === 'Enter') {
        //         e.preventDefault(); // Prevent submission on Enter
        //         }
        //     });




        @include('partials.item._legacy_add_submit', [
            'context' => 'vendor',
            'storeUrl' => route('vendor.item.store'),
            'listUrl' => route('vendor.item.list'),
            'pendingListUrl' => route('vendor.item.pending_item_list'),
        ])

        @include('partials.item._legacy_add_inline_js', [
            'context' => 'vendor',
            'variantCombinationUrl' => route('vendor.item.variant-combination'),
            'brandListUrl' => route('vendor.item.getBrandList'),
            'variantStockValue' => $module_data['stock'],
            'variantStockIsJs' => false,
        ])
    </script>
@endpush
