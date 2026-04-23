@extends('layouts.vendor.app')

@section('title',translate('messages.flash_sales'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        @php($store_data=\App\CentralLogics\Helpers::get_store_data())
        @php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
        @php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
        @php($productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))

        @if($productsRmsUi)
            @php($k = [
                'total' => $items->total(),
                'running' => (int) collect($items->items())->filter(function($i){
                    $t2 = \Carbon\Carbon::parse($i->flashSale->end_date);
                    return !($i['status'] == 0 || $i->flashSale->is_publish == 0) && ($i->flashSale->is_publish == 1 && $t2->gte(now()));
                })->count(),
                'expired' => (int) collect($items->items())->filter(function($i){
                    $t2 = \Carbon\Carbon::parse($i->flashSale->end_date);
                    return !($i['status'] == 0 || $i->flashSale->is_publish == 0) && !($i->flashSale->is_publish == 1 && $t2->gte(now()));
                })->count(),
                'off' => (int) collect($items->items())->filter(fn($i)=> ($i['status'] == 0 || $i->flashSale->is_publish == 0))->count(),
            ])
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
                    .mf-products .mf-kpis{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin:0}
                    .mf-products .mf-kpi{background:#fff;border:0;border-radius:14px;box-shadow:0 6px 18px rgba(2,6,23,.08);padding:14px 16px}
                    .mf-products .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                    .mf-products .mf-kpi .v{font-size:22px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                    .mf-products .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
                    .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                    .mf-products .mf-card .card-header{background:#fff;border-bottom:1px solid #eef2f7}
                    .mf-products .mf-catalogue-h{display:flex;gap:12px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap}
                    .mf-products .mf-catalogue-title{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:900;margin:0}
                    .mf-products .mf-tools{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
                    .mf-products .mf-tools .input-group .form-control{height:38px;border-radius:10px 0 0 10px}
                    .mf-products .mf-tools .input-group .btn{height:38px;border-radius:0 10px 10px 0}
                    .mf-products .mf-table thead.thead-light th{font-size:9px;letter-spacing:.08em;text-transform:uppercase;padding:.55rem .7rem;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0}
                    .mf-products .mf-table tbody td{padding:.6rem .7rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
                    .mf-products .mf-table tbody tr:hover td{background:#fafafa}
                    .mf-products .mf-namecard{display:flex;flex-direction:column;align-items:center;gap:8px}
                    .mf-products .mf-namecard .avatar.avatar-lg{width:54px;height:54px;border-radius:12px;object-fit:cover;margin-right:0!important}
                    .mf-products .mf-namecard .mf-n{font-weight:900;color:#0f172a;line-height:1.2}
                    .mf-products .mf-pill{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:900;border:1px solid #e2e8f0}
                    .mf-products .mf-pill.ok{background:#ecfdf5;border-color:#bbf7d0;color:#16a34a}
                    .mf-products .mf-pill.info{background:#eff6ff;border-color:#bfdbfe;color:#2563eb}
                    .mf-products .mf-pill.danger{background:#fef2f2;border-color:#fecaca;color:#dc2626}
                    .mf-products .card-header{padding:.75rem 1rem}
                    .mf-products .empty--data{padding:28px 0}
                    .mf-products .empty--data img{width:120px;margin-bottom:10px}
                    .mf-products .empty--data h5{font-size:13px;color:#64748b;font-weight:900;margin:0}
                </style>
            @endpush

            <div class="mf-products mb-3">
                <div class="mf-welcome">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1>{{ translate('messages.flash_sale_product_setup') }}</h1>
                            <p>{{ translate('Manage flash sale stock, discounts and status') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip ghost" href="{{ route('vendor.item.list') }}"><i class="tio-arrow-backward"></i> {{ translate('messages.back') }}</a>
                                <a class="mf-chip primary" href="{{ route('vendor.item.add-new') }}"><i class="tio-add-circle"></i> {{ translate('messages.add_new_item') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3 mt-lg-0">
                            <div class="mf-kpis">
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Total') }}</div>
                                    <div class="v">{{ (int) data_get($k,'total',0) }}</div>
                                    <div class="s">{{ translate('Items') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Running') }}</div>
                                    <div class="v">{{ (int) data_get($k,'running',0) }}</div>
                                    <div class="s">{{ translate('Live') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Expired') }}</div>
                                    <div class="v">{{ (int) data_get($k,'expired',0) }}</div>
                                    <div class="s">{{ translate('Ended') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Off') }}</div>
                                    <div class="v">{{ (int) data_get($k,'off',0) }}</div>
                                    <div class="s">{{ translate('Disabled') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/condition.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate('messages.flash_sale_product_setup')}}
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row g-3">


            <div class="col-12">
                <div class="card {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
                    <div class="card-header py-2 border-0">
                        @if($productsRmsUi)
                            <div class="mf-catalogue-h w-100">
                                <div class="mf-catalogue-title">
                                    {{translate('messages.flash_sale_product_list')}} <span class="text-muted font-weight-bold">({{$items->total()}})</span>
                                </div>
                                <div class="mf-tools">
                                    <form class="search-form">
                                        <div class="input-group input--group">
                                            <input value="{{ request()?->search ?? null }}" type="search" name="search" class="form-control"
                                                    placeholder="{{translate('ex_:_name')}}" aria-label="Search" >
                                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="search--button-wrapper">
                                <h5 class="card-title">
                                    {{translate('messages.flash_sale_product_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$items->total()}}</span>
                                </h5>
                                <form  class="search-form">
                                    <div class="input-group input--group">
                                        <input id="datatableSearch_" value="{{ request()?->search ?? null }}" type="search" name="search" class="form-control"
                                                placeholder="{{translate('ex_:_name')}}" aria-label="Search" >
                                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ $productsRmsUi ? 'mf-products mf-table' : '' }}"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true,
                                 "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr class="text-center">
                                <th class="border-0">{{translate('sl')}}</th>
                                <th class="border-0">{{translate('messages.product')}}</th>
                                <th class="border-0">{{translate('messages.Current_Stock')}}</th>
                                <th class="border-0">{{translate('messages.Flash_sale_Qty')}}</th>
                                <th class="border-0">{{translate('messages.Qty_Sold')}}</th>
                                <th class="border-0">{{translate('messages.Discount')}}</th>
                                <th class="border-0">{{translate('messages.Sold_Amount')}}</th>
                                <th class="border-0">{{translate('messages.status')}}</th>
                            </tr>

                            </thead>

                            <tbody id="set-rows">
                            @foreach($items as $key=>$item)
                                <tr>
                                    <td class="text-center">
                                        <span class="mr-3">
                                            {{$key+$items->firstItem()}}
                                        </span>
                                    </td>

                                    <?php
                                    $t2= Carbon\Carbon::parse($item->flashSale->end_date) ;
                                    ?>


                                    <td class="text-center">
                                        @if($productsRmsUi)
                                            <a class="mf-namecard" href="{{route('vendor.item.view',[$item['item_id']])}}">
                                                <img class="avatar avatar-lg onerror-image" src="{{ $item->item['image_full_url'] }}"
                                                        data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->item->name}} image">
                                                <div class="mf-n">{{Str::limit($item->item['name'],22,'...')}}</div>
                                            </a>
                                        @else
                                            <a class="media align-items-center" href="{{route('vendor.item.view',[$item['item_id']])}}">
                                                <img class="avatar avatar-lg mr-3 onerror-image" src="{{ $item->item['image_full_url'] }}"
                                                        data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->item->name}} image">
                                                <div class="media-body">
                                                    <h5 class="text-hover-primary mb-0">{{Str::limit($item->item['name'],20,'...')}}</h5>
                                                </div>
                                            </a>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ $item['available_stock'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['stock'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item['sold'] }}
                                    </td>
                                    <td class="text-center">
                                        @if($item->discount_type == 'percent')
                                        {{$item['discount']}} %
                                        @else
                                        {{\App\CentralLogics\Helpers::format_currency($item['discount'])}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        {{\App\CentralLogics\Helpers::format_currency($item['price'] * $item['sold'])}}

                                    </td>
                                    <td class="text-center">
                                        @if($item['status'] == 0 || $item->flashSale->is_publish == 0)
                                        <span class="{{ $productsRmsUi ? 'mf-pill info' : 'badge badge-soft-info' }}">{{ translate('off')}}</span>
                                        @elseif($item->flashSale->is_publish == 1 && $t2->gte(now())  )
                                        <span class="{{ $productsRmsUi ? 'mf-pill ok' : 'badge badge-soft-success' }}"> {{ translate('running')}} </span>
                                        @else
                                        <span class="{{ $productsRmsUi ? 'mf-pill danger' : 'badge badge-soft-danger' }}">{{ translate('expired')}}</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(count($items) !== 0)
                    <hr>
                    @endif
                    <div class="page-area">
                        {!! $items->links() !!}
                    </div>
                    @if(count($items) === 0)
                    <div class="empty--data">
                        <img src="{{asset('/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')

@endpush
