        $('#store_id').on('change', function() {
            let route = '{{ url('/') }}/admin/store/get-addons?data[]=0&store_id=';
            let store_id = $(this).val();
            let id = 'add_on';
            getStoreData(route, store_id, id);
        });

        function getStoreData(route, store_id, id) {
            $.get({
                url: route + store_id,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        function getRequest(route, id) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    $('#' + id).empty().append(data.options);
                },
            });
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
            $('#image-viewer-section').show(1000)
        });

        $(document).ready(function() {
            @if (count($product->add_ons ?: []) > 0)
                getStoreData(
                    '{{ url('/') }}/admin/store/get-addons?@foreach ($product->add_ons ?: [] as $addon)data[]={{ $addon }}& @endforeach store_id=',
                    '{{ $product['store_id'] }}', 'add_on');
            @else
                getStoreData('{{ url('/') }}/admin/store/get-addons?data[]=0&store_id=',
                    '{{ $product['store_id'] }}', 'add_on');
            @endif
        });

        let module_id = {{ $product->module_id }};
        let module_type = "{{ $product->module->module_type }}";
        let parent_category_id = {{ $category ? $category->id : 0 }};
        <?php
        $module_data = config('module.' . $product->module->module_type);
        unset($module_data['description']);
        ?>
        let module_data = {{ str_replace('"', '', json_encode($module_data)) }};
        let stock = {{ $product->module->module_type == 'food' ? 'false' : 'true' }};
        input_field_visibility_update();

        function modulChange(id) {
            $.get({
                url: "{{ url('/') }}/admin/module/" + id,
                dataType: 'json',
                success: function(data) {
                    module_data = data.data;
                    stock = module_data.stock;
                    input_field_visibility_update();
                    combination_update();
                },
            });
            module_id = id;
        }

        function input_field_visibility_update() {
            if (module_data.stock) {
                $('#stock_input').show();
            } else {
                $('#stock_input').hide();
            }
            if (module_data.add_on) {
                $('#addon_input').show();
            } else {
                $('#addon_input').hide();
            }

            if (module_data.item_available_time) {
                $('#time_input').show();
            } else {
                $('#time_input').hide();
            }

            if (module_data.veg_non_veg) {
                $('#veg_input').show();
            } else {
                $('#veg_input').hide();
            }

            if (module_data.unit) {
                $('#unit_input').show();
            } else {
                $('#unit_input').hide();
            }
            if (module_data.common_condition) {
                $('#condition_input').show();
            } else {
                $('#condition_input').hide();
            }
            if (module_data.brand) {
                $('#brand_input').show();
            } else {
                $('#brand_input').hide();
            }
            if (module_type == 'food') {
                $('#food_variation_section').show();
                $('#attribute_section').hide();
            } else {
                $('#food_variation_section').hide();
                $('#attribute_section').show();
            }
            if (module_data.organic) {
                $('#organic').show();
            } else {
                $('#organic').hide();
            }
            if (module_data.basic) {
                $('#basic').show();
            } else {
                $('#basic').hide();
            }
            if (module_data.nutrition) {
                $('#nutrition').show();
            } else {
                $('#nutrition').hide();
            }
            if (module_data.allergy) {
                $('#allergy').show();
            } else {
                $('#allergy').hide();
            }
        }

        $('#category_id').on('change', function() {
            parent_category_id = $(this).val();
            let subCategoriesSelect = $('#sub-categories');
            subCategoriesSelect.empty();
            subCategoriesSelect.append(
                '<option value="" selected>{{ translate('messages.select_sub_category') }}</option>');
        });

        $('.foodModalClose').on('click', function() {
            $('#food-modal').hide();
        })

        $('.foodModalShow').on('click', function() {
            $('#food-modal').show();
        })

        $('.attributeModalClose').on('click', function() {
            $('#attribute-modal').hide();
        })

        $('.attributeModalShow').on('click', function() {
            $('#attribute-modal').show();
        })

        $(document).on('ready', function() {
            $('.js-select2-custom').each(function() {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#condition_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/common-condition/get-all',
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

        $('#store_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/store/get-stores',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page,
                        module_id: module_id
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

        $('#category_id').select2({
            ajax: {
                url: '{{ url('/') }}/admin/item/get-categories?parent_id=0',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page,
                        module_id: module_id
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

        $('#sub-categories').select2({
            ajax: {
                url: '{{ url('/') }}/admin/item/get-categories',
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page,
                        module_id: module_id,
                        parent_id: parent_category_id,
                        sub_category: true
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

