@extends('layouts.admin.app')

@section('title', request()->product_gellary == 1 ? translate('Add item') : translate('Edit item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
@endpush

@section('content')


    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/edit.png') }}" class="w--22" alt="">
                </span>
                <span>
                    {{ request()->product_gellary == 1 ? translate('Add_item') : translate('item_update') }}
                </span>
            </h1>
            <div class="d-flex align-items-end flex-wrap">
                @if (Config::get('module.current_module_type') == 'food')
                    <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center foodModalShow" type="button">
                        <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong>
                        <div>
                            <i class="tio-info-outined"></i>
                        </div>
                    </div>
                @else
                    <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center attributeModalShow" type="button">
                        <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong>
                        <div>
                            <i class="tio-info-outined"></i>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @php($openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config'))
        <!-- End Page Header -->
        <form id="product_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
            <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">
            @if (request()->product_gellary == 1)
                @php($route = route('admin.item.store', ['product_gellary' => request()->product_gellary]))
                @php($product->price = 0)
            @else
                @php($route = route('admin.item.update', [isset($temp_product) && $temp_product == 1 ? $product['item_id'] : $product['id']]))
            @endif

            <input type="hidden" class="route_url"
                value="{{ $route ?? route('admin.item.update', [isset($temp_product) && $temp_product == 1 ? $product['item_id'] : $product['id']]) }}">
            <input type="hidden" value="{{ $temp_product ?? 0 }}" name="temp_product">
            <input type="hidden" value="{{ $product['id'] ?? null }}" name="item_id">
            <input type="hidden" id="request_type" value="admin">


            <div class="row g-2">

                @includeif('admin-views.product.partials._title_and_discription')
                @include('partials.item._legacy_edit_image_block')

                @includeif('admin-views.product.partials._category_and_general')
                @includeif('admin-views.product.partials._price_and_stock')

                @if (Config::get('module.current_module_type') == 'food')

                    <div class="col-lg-12" id="food_variation_section">
                        <div class="variation_wrapper">
                            <div class="outline-wrapper">
                                <div class="card shadow--card-2 border-0 bg-animate">

                                    <div class="card-header flex-wrap">
                                        <h5 class="card-title">
                                            <span class="card-header-icon mr-2">
                                                <i class="tio-canvas-text"></i>
                                            </span>
                                            <span>{{ translate('messages.food_variations') }}</span>
                                        </h5>
                                        <div>

                                            <a class="btn text--primary-2" id="add_new_option_button">
                                                {{ translate('add_new_variation') }}
                                                <i class="tio-add"></i>
                                            </a>
                                            @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                                                <button type="button"
                                                    class="btn bg-white text-primary opacity-1 generate_btn_wrapper variation_setup_auto_fill"
                                                    id="variation_setup_auto_fill"
                                                    data-route="{{ route('admin.product.variation-setup-auto-fill') }}"
                                                    data-error="{{ translate('Please provide an item name and description so the AI can generate a suitable food variations.') }}"
                                                    data-lang="en">
                                                    <div class="btn-svg-wrapper">
                                                        <img width="18" height="18" class=""
                                                            src="{{ asset('assets/admin/img/svg/blink-right-small.svg') }}"
                                                            alt="">
                                                    </div>
                                                    <span class="ai-text-animation d-none" role="status">
                                                        {{ translate('Just_a_second') }}
                                                    </span>
                                                    <span class="btn-text">{{ translate('Generate') }}</span>
                                                </button>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="card-body">
                                        <div id="add_new_option">
                                            @if (isset($product->food_variations) && count($product->food_variations) > 0)
                                                @foreach ($product->food_variations as $key_choice_options => $item)
                                                    @if (isset($item['price']))
                                                        @break

                                                    @else
                                                        @include(
                                                            'admin-views.product.partials._new_variations',
                                                            [
                                                                'item' => $item,
                                                                'key' => $key_choice_options + 1,
                                                            ]
                                                        )
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- Empty Variation -->
                                        @if (!isset($product->food_variations) || count($product->food_variations) < 1)
                                            <div id="empty-variation">
                                                <div class="text-center">
                                                    <img src="{{ asset('/assets/admin/img/variation.png') }}"
                                                        alt="">
                                                    <div>{{ translate('No variation added') }}</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if (Config::get('module.current_module_type') != 'food')

                    <div class="col-md-12" id="attribute_section">
                        <div class="variation_wrapper">
                            <div class="outline-wrapper">
                                <div class="card shadow--card-2 border-0 bg-animate">
                                    <div class="card-header">
                                        <h5 class="card-title">
                                            <span class="card-header-icon"><i class="tio-canvas-text"></i></span>
                                            <span>{{ translate('attribute') }}</span>
                                        </h5>
                                        @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                                            <button type="button"
                                                class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 other_variation_setup_auto_fill"
                                                id="other_variation_setup_auto_fill"
                                                data-route="{{ route('admin.product.generate-other-variation-data') }}"
                                                data-error="{{ translate('Please provide an item name and description so the AI can generate a suitable variations.') }}"
                                                data-lang="en">
                                                <div class="btn-svg-wrapper">
                                                    <img width="18" height="18" class=""
                                                        src="{{ asset('assets/admin/img/svg/blink-right-small.svg') }}"
                                                        alt="">
                                                </div>
                                                <span class="ai-text-animation d-none" role="status">
                                                    {{ translate('Just_a_second') }}
                                                </span>
                                                <span class="btn-text">{{ translate('Generate') }}</span>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="card-body pb-0">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <label class="input-label"
                                                        for="exampleFormControlSelect1">{{ translate('messages.attribute') }}<span
                                                            class="input-label-secondary"></span></label>
                                                    <select name="attribute_id[]" id="choice_attributes"
                                                        class="form-control js-select2-custom" multiple="multiple">
                                                        @foreach (\App\Models\Attribute::orderBy('name')->get() as $attribute)
                                                            <option value="{{ $attribute['id'] }}"
                                                                {{ in_array($attribute->id, $product->attributes ?: []) ? 'selected' : '' }}>
                                                                {{ $attribute['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <div class="customer_choice_options d-flex __gap-24px"
                                                        id="customer_choice_options">
                                                        @include('admin-views.product.partials._choices', [
                                                            'choice_no' => $product->attributes,
                                                            'choice_options' => $product->choice_options,
                                                        ])
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="variant_combination" id="variant_combination">
                                                    @include(
                                                        'admin-views.product.partials._edit-combinations',
                                                        [
                                                            'combinations' => $product->variations,
                                                            'stock' => config(
                                                                'module.' . $product->module->module_type)['stock'],
                                                        ]
                                                    )
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn"
                            class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit"
                            class="btn btn--primary">{{ isset($temp_product) && $temp_product == 1 ? translate('Edit_&_Approve') : translate('messages.submit') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal" id="food-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close foodModalClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xG8fO7TXPbk"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="attribute-modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close attributeModalClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xG8fO7TXPbk"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>
    @includeif('admin-views.product.partials._ai_sidebar')

@endsection


@push('script_2')
    <script>
        let count = $('.count_div').length;
    </script>

    <script src="{{ asset('assets/admin') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('assets/admin/js/spartan-multi-image-picker.js') }}"></script>


    <script src="{{ asset('assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
    <script src="{{ asset('assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>
    @if (Config::get('module.current_module_type') == 'food')
        <script src="{{ asset('assets/admin/js/AI/products/variation-setup-auto-fill.js') }}"></script>
    @else
        <script src="{{ asset('assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>
    @endif

    <script src="{{ asset('assets/admin/js/AI/products/ai-sidebar.js') }}"></script>

    <script src="{{ asset('/assets/admin/js/AI/products/compressor/image-compressor.js') }}"></script>
    <script src="{{ asset('/assets/admin/js/AI/products/compressor/compressor.min.js') }}"></script>


    <script>
        "use strict";
        let removedImageKeys = [];
        let element = "";


        $(document).on('click', '.function_remove_img', function() {
            let key = $(this).data('key');
            let photo = $(this).data('photo');
            function_remove_img(key, photo);
        });

        function function_remove_img(key, photo) {
            $('#product_images_' + key).addClass('d-none');
            removedImageKeys.push(photo);
            $('#removedImageKeysInput').val(removedImageKeys.join(','));
        }


        function show_min_max(data) {
            console.log(data);
            $('#min_max1_' + data).removeAttr("readonly");
            $('#min_max2_' + data).removeAttr("readonly");
            $('#min_max1_' + data).attr("required", "true");
            $('#min_max2_' + data).attr("required", "true");
        }

        function hide_min_max(data) {
            console.log(data);
            $('#min_max1_' + data).val(null).trigger('change');
            $('#min_max2_' + data).val(null).trigger('change');
            $('#min_max1_' + data).attr("readonly", "true");
            $('#min_max2_' + data).attr("readonly", "true");
            $('#min_max1_' + data).attr("required", "false");
            $('#min_max2_' + data).attr("required", "false");
        }

        $(document).on('change', '.show_min_max', function() {
            let data = $(this).data('count');
            show_min_max(data);
        });

        $(document).on('change', '#discount_type', function() {
            let data = document.getElementById("discount_type");
            if (data.value === 'amount') {
                $('#symble').text("({{ \App\CentralLogics\Helpers::currency_symbol() }})");
            } else {
                $('#symble').text("(%)");
            }
        });

        $(document).on('change', '.hide_min_max', function() {
            let data = $(this).data('count');
            hide_min_max(data);
        });



        $(document).ready(function() {
            $("#add_new_option_button").click(function(e) {
                add_new_option_button();
            });
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
                count +
                `">
                                            </div>

                                            <div class="col-xl-4 col-lg-6">
                                                <div>
                                                    <label class="input-label text-capitalize d-flex align-items-center"><span class="line--limit-1">{{ translate('messages.selcetion_type') }} </span>
                                                    </label>
                                                    <div class="resturant-type-group px-0">
                                                        <label class="form-check form--check mr-2 mr-md-4">
                                                            <input class="form-check-input show_min_max" data-count="` +
                count + `" type="radio" value="multi"
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
                                                        <input id="min_max1_` + count + `" required  name="options[` +
                count + `][min]" class="form-control" type="number" min="1">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="">{{ translate('Max') }}</label>
                                                        <input id="min_max2_` + count + `"   required name="options[` +
                count + `][max]" class="form-control" type="number" min="1">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="option_price_` + count + `" >
                                            <div class="bg-white border rounded p-3 pb-0 mt-3">
                                                <div  id="option_price_view_` + count +
                `">
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


        function new_option_name(value, data) {
            $("#new_option_name_" + data).empty();
            $("#new_option_name_" + data).text(value)
            console.log(value);
        }

        function removeOption(e) {
            element = $(e);
            element.parents('.view_new_option').remove();
        }

        $(document).on('click', '.delete_input_button', function() {
            let e = $(this);
            removeOption(e);
        });

        function deleteRow(e) {
            element = $(e);
            element.parents('.add_new_view_row_class').remove();
        }

        $(document).on('click', '.deleteRow', function() {
            let e = $(this);
            deleteRow(e);
        });
        let countRow = 0;

        function add_new_row_button(data) {
            // count = data;
            countRow = 1 + $('#option_price_view_' + data).children('.add_new_view_row_class').length;
            let add_new_row_view = `
            <div class="row add_new_view_row_class mb-3 position-relative pt-3 pt-sm-0">
                <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Option_name') }}</label>
                        <input class="form-control" required type="text" name="options[` + data + `][values][` +
                countRow + `][label]" id="">
                    </div>
                    <div class="col-md-4 col-sm-5">
                        <label for="">{{ translate('Additional_price') }}</label>
                        <input class="form-control"  required type="number" min="0" step="0.01" name="options[` +
                data +
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

        $(document).on('click', '.add_new_row_button', function() {
            let data = $(this).data('count');
            add_new_row_button(data);
        });

        $(document).on('keyup', '.new_option_name', function() {
            let data = $(this).data('count');
            let value = $(this).val();
            new_option_name(value, data);
        });

        @include('partials.item._legacy_edit_admin_wiring')

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            combination_update();
            $.each($("#choice_attributes option:selected"), function() {
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name;

            $('#customer_choice_options').append(
                `<div class="__choos-item"><div><input type="hidden" name="choice_no[]" value="${i}"><input type="text" class="form-control d-none" name="choice[]" value="${n}" placeholder="{{ translate('messages.choice_title') }}" readonly> <label class="form-label">${n}</label> </div><div><input type="text" class="form-control combination_update" name="choice_options_${i}[]" placeholder="{{ translate('messages.enter_choice_values') }}" data-role="tagsinput"></div></div>`
            );
            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }

        setTimeout(function() {
            $('.call-update-sku').on('change', function() {
                combination_update();
            });
        }, 2000)

        $('#colors-selector').on('change', function() {
            combination_update();
        });

        $('input[name="unit_price"]').on('keyup', function() {
            combination_update();
        });

        $(document).on('change', '.combination_update', function() {
            combination_update();
        });
        // $('#product_form').on('keydown', function(e) {
        //        if (e.key === 'Enter') {
        //        e.preventDefault(); // Prevent submission on Enter
        //        }
        //    });

        @include('partials.item._legacy_edit_common_js', [
            'context' => 'admin',
            'variantCombinationUrl' => route('admin.item.variant-combination'),
            'brandListUrl' => url('/') . '/admin/brand/get-all',
            'variantStockValue' => 'stock',
            'variantStockIsJs' => true,
            'listUrl' => route('admin.item.list'),
            'hideQuantityOnMulti' => false,
            'imageMaxBytes' => 1024 * 1024 * MAX_FILE_SIZE,
        ])

        $('#reset_btn').click(function() {
            location.reload(true);
        })
    </script>
@endpush
