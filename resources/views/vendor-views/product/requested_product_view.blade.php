@extends('layouts.vendor.app')

@section('title', translate('Item Preview'))

@push('css_or_js')
@endpush

@section('content')
@php($store_data=\App\CentralLogics\Helpers::get_store_data())
@php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
@php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
@php($productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))

<div class="content container-fluid">
    @if($productsRmsUi)
        @push('css_or_js')
            <style>
                .mf-products{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
                .mf-products .mf-welcome{
                    background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
                    border-radius:14px;padding:18px 20px;color:#fff;position:relative;overflow:hidden;margin-bottom:14px
                }
                .mf-products .mf-welcome:before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04)}
                .mf-products .mf-welcome:after{content:'';position:absolute;right:60px;bottom:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.03)}
                .mf-products .mf-welcome h1{font-size:16px;font-weight:900;margin:0;color:#fff;position:relative}
                .mf-products .mf-welcome p{font-size:12px;opacity:.82;margin:6px 0 0;color:#fff;position:relative}
                .mf-products .mf-chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;position:relative}
                .mf-products .mf-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:7px 12px;font-weight:900;font-size:12px;border:1px solid rgba(255,255,255,.28);text-decoration:none}
                .mf-products .mf-chip.primary{background:#fff;color:#0f172a;border-color:#fff}
                .mf-products .mf-chip.ghost{background:rgba(255,255,255,.14);color:#fff}
                .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                .mf-products .mf-card .card-header{background:#fff;border-bottom:1px solid #eef2f7}
                .mf-products .table thead.thead-light th{font-size:10px;letter-spacing:.06em;text-transform:uppercase;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0}
                .mf-products .table tbody td{border-bottom:1px solid #f1f5f9}
            </style>
        @endpush

        <div class="mf-products mb-3">
            <div class="mf-welcome">
                <div class="d-flex flex-wrap justify-content-between align-items-start">
                    <div>
                        <h1>{{ translate('Product_Details') }}</h1>
                        <p class="mb-0">{{ $product?->getRawOriginal('name') ?? '' }}</p>
                        <div class="mf-chips">
                            <a class="mf-chip ghost" href="{{ route('vendor.item.pending_item_list') }}"><i class="tio-arrow-backward"></i> {{ translate('messages.back') }}</a>
                            <a class="mf-chip primary" href="{{ route('vendor.item.edit', [$product['id'], 'temp_product' => true]) }}"><i class="tio-edit"></i> {{ translate('messages.edit_&_Resubmit') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Page Header -->
    <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
        <div class="d-flex flex-wrap justify-content-between">
            <h1 class="page-header-title text-break">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/temp_pro.png') }}" class="w--22" alt="">
                </span>
                <span>{{ translate('Product_Details') }}</span>
            </h1>

        </div>
    </div>
    <!-- End Page Header -->

    <div class="card mb-3 {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
        <!-- Body -->
        <div class="card-body">
            <div class="row flex-wrap">
                <div>
                    <div class="d-flex flex-wrap align-items-center food--media position-relative mr-4">
                        <img class="avatar avatar-xxl avatar-4by3 onerror-image" src="{{ $product['image_full_url'] }}"
                            data-onerror-image="{{ asset('assets/admin/img/160x160/img2.jpg') }}"
                            alt="Image Description">
                        @if ($product['is_rejected'] == 1)
                            <div class="reject-info"> {{ translate('Your_Item_Has_Been_Rejected') }}</div>
                        @else
                            <div class="pending-info"> {{ translate('This_Item_Is_Under_Review') }}</div>
                        @endif
                    </div>
                </div>
                <div class="w-70 flex-grow">
                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first()?->value ?? null)
                    @php($defaultLang = str_replace('_', '-', app()->getLocale()))
                    <div class="d-flex flex-wrap gap-2 justify-content-between">
                        @if ($language)
                            <ul class="nav nav-tabs border-0 mb-3">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active" href="#"
                                        id="default-link">{{ translate('messages.default') }}</a>
                                </li>
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item">
                                        <a class="nav-link lang_link" href="#"
                                            id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <div class="d-flex flex-wrap gap-2 align-items-start">
                            <a class="btn btn--sm btn-outline-danger form-alert" href="javascript:"
                                data-id="food-{{$product['id']}}"
                                data-message="{{ translate('Want to delete this item ?') }}"
                                title="{{translate('messages.delete_item')}}">{{ translate('messages.Delete') }} <i
                                    class="tio-delete-outlined"></i>
                            </a>
                            <a href="{{ route('vendor.item.edit', [$product['id'], 'temp_product' => true]) }}"
                                class="btn btn--sm btn-outline-primary">
                                <i class="tio-edit"></i> {{ translate('messages.edit_&_Resubmit') }}
                            </a>
                            <form action="{{route('vendor.item.delete', [$product['id']])}}" method="post"
                                id="food-{{$product['id']}}">
                                @csrf @method('delete')
                                <input type="hidden" value="1" name="temp_product">
                            </form>


                        </div>
                    </div>

                    <div class="lang_form" id="default-form">
                        <h2 class="mt-3">{{ $product?->getRawOriginal('name') }} </h2>
                        <h6> {{ translate('description') }}:</h6>
                        <P> {{ $product?->getRawOriginal('description') }}</P>
                    </div>

                    @foreach (json_decode($language) as $lang)
                                        <?php
                        if (count($product['translations'])) {
                            $translate = [];
                            foreach ($product['translations'] as $t) {
                                if ($t->locale == $lang && $t->key == 'name') {
                                    $translate[$lang]['name'] = $t->value;
                                }
                                if ($t->locale == $lang && $t->key == 'description') {
                                    $translate[$lang]['description'] = $t->value;
                                }
                            }
                        }
                                                        ?>
                                        <div class="d-none lang_form" id="{{ $lang }}-form">
                                            <h2>{{ $translate[$lang]['name'] ?? '' }} </h2>
                                            <h6> {{ translate('description') }}:</h6>
                                            <P> {!! $translate[$lang]['description'] ?? '' !!}</P>
                                        </div>
                    @endforeach
                </div>
            </div>


        </div>
        <!-- End Body -->
    </div>

    <!-- Description Card Start -->
    <div class="card mb-3 {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless table-thead-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th class="px-4 border-0">
                                <h4 class="m-0 text-capitalize">{{ translate('General_Information') }}</h4>
                            </th>
                            <th class="px-4 border-0">
                                <h4 class="m-0 text-capitalize">{{ translate('price_Information') }}</h4>
                            </th>

                            @if (in_array($product->module->module_type, ['food', 'grocery']))
                                <th class="px-4 border-0">
                                    <h4 class="m-0 text-capitalize">{{ translate('Nutrition') }}</h4>
                                </th>
                                <th class="px-4 border-0">
                                    <h4 class="m-0 text-capitalize">{{ translate('Allergy') }}</h4>
                                </th>

                            @endif
                            @if (in_array($product->module->module_type, ['pharmacy']))
                                <th class="px-4 border-0">
                                    <h4 class="m-0 text-capitalize">{{ translate('Generic_Name') }}</h4>
                                </th>
                            @endif
                            <th class="px-4 border-0">
                                <h4 class="m-0 text-capitalize">{{ translate('Available_Variations') }}</h4>
                            </th>
                            @if ($product->module->module_type == 'food')
                                <th class="px-4 border-0">
                                    <h4 class="m-0 text-capitalize">{{ translate('addons') }}</h4>
                                </th>
                            @endif
                            <th class="px-4 border-0">
                                <h4 class="m-0 text-capitalize">{{ translate('tags') }}</h4>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 max-w--220px">
                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.Store') }} : </span>
                                    <strong>{{ $product?->store?->name }}</strong>
                                </span>
                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.Category') }} : </span>
                                    <strong>{{ Str::limit(
    ($product?->category?->parent ? $product?->category?->parent?->name : $product?->category?->name) ?? translate('messages.uncategorize')
    ,
    20,
    '...'
) }}</strong>
                                </span>

                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.Sub_Category') }} : </span>
                                    <strong>{{ Str::limit(
    ($product?->category?->parent?->name ? $product?->category?->name : '---')
    ,
    20,
    '...'
) }}</strong>
                                </span>

                                @if ($product->module->module_type == 'grocery')
                                    <span class="d-block mb-1">
                                        <span>{{ translate('messages.Is_Organic') }} : </span>
                                        <strong>
                                            {{  $product->organic == 1 ? translate('messages.yes') : translate('messages.no') }}</strong>
                                    </span>
                                @endif
                                @if ($product->module->module_type == 'food')
                                    <span class="d-block mb-1">
                                        <span>{{ translate('messages.Item_type') }} : </span>
                                        <strong>
                                            {{  $product->veg == 1 ? translate('messages.veg') : translate('messages.non_veg') }}</strong>
                                    </span>
                                @else
                                    <span class="d-block mb-1">
                                        <span>{{ translate('messages.Total_stock') }} : </span>
                                        <strong> {{  $product->stock  }}</strong>
                                    </span>

                                    @if ($product?->unit)
                                        <span class="d-block mb-1">
                                            <span>{{ translate('messages.Unit') }} : </span>
                                            <strong> {{ $product?->unit?->unit  }}</strong>
                                        </span>
                                    @endif
                                @endif
                                @if (config('module.' . $product->module->module_type)['item_available_time'])
                                    <span class="d-block mb-1">
                                        {{ translate('messages.available_time_starts') }} :
                                        <strong>{{ date(config('timeformat'), strtotime($product['available_time_starts'])) }}</strong>
                                    </span>
                                    <span class="d-block mb-1">
                                        {{ translate('messages.available_time_ends') }} :
                                        <strong>{{ date(config('timeformat'), strtotime($product['available_time_ends'])) }}</strong>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4">
                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.Unit_Price') }} : </span>
                                    <strong>{{ \App\CentralLogics\Helpers::format_currency($product['price']) }}</strong>
                                </span>
                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.discounted_amount') }} :</span>
                                    <strong>{{ \App\CentralLogics\Helpers::format_currency(\App\CentralLogics\Helpers::discount_calculate($product, $product['price'])) }}</strong>
                                </span>
                                <span class="d-block mb-1">
                                    <span>{{ translate('messages.discount') }} :</span>
                                    <strong>
                                        {{ $product->discount_type == 'percent' ? $product->discount . '%' : \App\CentralLogics\Helpers::format_currency($product['discount']) }}
                                    </strong>
                                </span>



                            </td>

                            @php($product_nutritions = $product?->nutrition_ids ? \App\Models\Nutrition::whereIn('id', json_decode($product?->nutrition_ids))->pluck('nutrition') : [])
                            @php($product_allergies = $product?->allergy_ids ? \App\Models\Allergy::whereIn('id', json_decode($product?->allergy_ids))->pluck('allergy') : [])

                            @if (in_array($product->module->module_type, ['food', 'grocery']))
                                <td class="px-4 product-gallery-info">

                                    @foreach($product_nutritions as $nutrition)
                                        {{$nutrition}}{{ !$loop->last ? ',' : '.'}}
                                    @endforeach

                                </td>
                                <td class="px-4 product-gallery-info">
                                    @foreach($product_allergies as $allergy)
                                        {{$allergy}}{{ !$loop->last ? ',' : '.'}}
                                    @endforeach

                                </td>
                            @endif
                            @if (in_array($product->module->module_type, ['pharmacy']))
                                <td class="px-4 product-gallery-info">
                                    {{ \App\Models\GenericName::where('id', json_decode($product?->generic_ids))->first()?->generic_name }}
                                </td>
                            @endif




                            <td class="px-4">
                                @if ($product->module->module_type == 'food')
                                    @if ($product->food_variations && is_array($product->food_variations))
                                        @foreach ($product->food_variations as $variation)
                                            @if (isset($variation['price']))
                                                <span class="d-block mb-1 text-capitalize">
                                                    <strong>
                                                        {{ translate('please_update_the_food_variations.') }}
                                                    </strong>
                                                </span>
                                                @break

                                            @else
                                                <span class="d-block text-capitalize">
                                                    <strong>
                                                        {{ $variation['name'] }} -
                                                    </strong>
                                                    @if ($variation['type'] == 'multi')
                                                        {{ translate('messages.multiple_select') }}
                                                    @elseif($variation['type'] == 'single')
                                                        {{ translate('messages.single_select') }}
                                                    @endif
                                                    @if ($variation['required'] == 'on')
                                                        - ({{ translate('messages.required') }})
                                                    @endif
                                                </span>

                                                @if ($variation['min'] != 0 && $variation['max'] != 0)
                                                    ({{ translate('messages.Min_select') }}: {{ $variation['min'] }} -
                                                    {{ translate('messages.Max_select') }}: {{ $variation['max'] }})
                                                @endif

                                                @if (isset($variation['values']))
                                                    @foreach ($variation['values'] as $value)
                                                        <span class="d-block text-capitalize">
                                                            &nbsp; &nbsp; {{ $value['label'] }} :
                                                            <strong>{{ \App\CentralLogics\Helpers::format_currency($value['optionPrice']) }}</strong>
                                                        </span>
                                                    @endforeach
                                                @endif
                                            @endif
                                        @endforeach
                                    @endif
                                @else
                                        @if ($product->variations && is_array($product->variations))
                                            @foreach ($product->variations as $variation)
                                                <span class="d-block mb-1 text-capitalize">
                                                    {{ $variation['type'] }} :
                                                    {{ \App\CentralLogics\Helpers::format_currency($variation['price']) }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                @endif
                            @if ($product->module->module_type == 'food')
                                <td class="px-4">
                                    {{-- @if (config('module.' . $product->module->module_type)['add_on']) --}}
                                    @foreach (\App\Models\AddOn::whereIn('id', $product->add_ons ?: [])->get() as $addon)
                                        <span class="d-block mb-1 text-capitalize">
                                            {{ $addon['name'] }} :
                                            {{ \App\CentralLogics\Helpers::format_currency($addon['price']) }}
                                        </span>
                                    @endforeach
                                    {{-- @endif --}}
                                </td>
                            @endif

                            @php( $tags = \App\Models\Tag::whereIn('id', json_decode($product?->tag_ids))->get('tag'))
                            <td>
                                @foreach($tags as $c) {{$c->tag}}{{ !$loop->last ? ',' : '.'}} @endforeach
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Description Card End -->

</div>
@endsection

@push('script_2')
    <script>
        "use strict";
        function request_alert(url, message) {
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }

        function cancelled_status(route, message, processing = false) {
            Swal.fire({
                //text: message,
                title: '{{ translate('messages.Are you sure ?') }}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '{{ translate('messages.Cancel') }}',
                confirmButtonText: '{{ translate('messages.submit') }}',
                inputPlaceholder: "{{ translate('Enter_a_reason') }}",
                input: 'text',
                html: message + '<br/>' + '<label>{{ translate('Enter_a_reason') }}</label>',
                inputValue: processing,
                preConfirm: (note) => {
                    location.href = route + '&note=' + note;
                },
                allowOutsideClick: () => !Swal.isLoading()
            })
        }
    </script>
@endpush
