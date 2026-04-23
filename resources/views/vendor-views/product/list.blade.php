@extends('layouts.vendor.app')

@section('title',translate('messages.item_list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
@php($store_data=\App\CentralLogics\Helpers::get_store_data())
@php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
@php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
@php($productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))
@php($k = $kpis ?? [])
@php($catalogMode = request()->query('catalog', $catalog ?? 'all'))
    <div class="content container-fluid">
        @if($productsRmsUi)
            @push('css_or_js')
                <style>
                    .mf-products{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
                    .mf-products .mf-welcome{
                        background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
                        border-radius:14px;padding:18px 20px;color:#fff;position:relative;overflow:hidden
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
                    .mf-products .mf-table thead.thead-light th{font-size:10px;letter-spacing:.06em;text-transform:uppercase;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0}
                    .mf-products .mf-table tbody td{border-bottom:1px solid #f1f5f9}
                    .mf-products .mf-table tbody tr:hover td{background:#fafafa}
                    .mf-products .mf-table th,.mf-products .mf-table td{padding:.65rem .75rem;vertical-align:middle}
                    .mf-products .mf-col-sku{width:92px;white-space:nowrap;color:#64748b;font-weight:800}
                    .mf-products .mf-col-type{width:110px;white-space:nowrap}
                    .mf-products .mf-col-sales{width:74px;white-space:nowrap}
                    .mf-products .mf-col-stock{width:86px;white-space:nowrap}
                    .mf-products .mf-col-rating{width:84px;white-space:nowrap}
                    .mf-products .mf-col-dl{width:110px;white-space:nowrap}
                    .mf-products .mf-col-rev{width:130px;white-space:nowrap}
                    @media (max-width: 1199.98px){
                        .mf-products .mf-col-sales{display:none}
                        .mf-products .mf-col-rating{display:none}
                    }
                    @media (max-width: 991.98px){
                        .mf-products .mf-col-sku{display:none}
                    }
                    .mf-products .mf-namecell{min-width:180px}
                    .mf-products .mf-namecard{display:flex;flex-direction:column;align-items:flex-start;gap:8px}
                    .mf-products .mf-namecard .avatar{margin-right:0!important}
                    .mf-products .mf-namecard .avatar.avatar-lg{width:54px;height:54px;border-radius:12px;object-fit:cover}
                    .mf-products .mf-namecard .mf-n{font-weight:900;color:#0f172a;line-height:1.2}
                    .mf-products .mf-namecard .mf-n:hover{color:var(--primary-clr,#107980)}
                    .mf-products .mf-namecard .mf-r{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:900;color:#64748b;line-height:1}
                    .mf-products .mf-namecard .mf-r i{font-size:14px}
                    .mf-products .mf-rate{display:inline-flex;align-items:center;gap:6px;font-weight:900;font-size:12px;color:#0f172a}
                    .mf-products .mf-rate i{font-size:14px}
                    /* KPIs: right-side 2x2 grid inside banner (previous style) */
                    .mf-products .mf-kpis{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin:0}
                    .mf-products .mf-kpi{background:#fff;border:0;border-radius:14px;box-shadow:0 6px 18px rgba(2,6,23,.08);padding:14px 16px}
                    .mf-products .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                    .mf-products .mf-kpi .v{font-size:22px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                    .mf-products .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
                    @media (max-width: 991.98px){
                        .mf-products .mf-kpis{grid-template-columns:repeat(2,minmax(0,1fr));margin-top:12px}
                    }
                    .mf-products .mf-tabs{display:flex;gap:8px;flex-wrap:wrap;margin:0 0 10px}
                    .mf-products .mf-tab{background:#fff;border:1px solid #e2e8f0;border-radius:999px;padding:6px 10px;font-weight:900;font-size:12px;color:#0f172a;text-decoration:none}
                    .mf-products .mf-tab.active{background:var(--primary-clr,#107980);border-color:var(--primary-clr,#107980);color:#fff}
                    .mf-products .mf-tools{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
                    .mf-products .mf-tools .select-item{min-width:220px}
                    .mf-products .mf-tools .select-item .form-control{height:38px;border-radius:10px}
                    .mf-products .mf-tools .input-group .form-control{height:38px;border-radius:10px 0 0 10px}
                    .mf-products .mf-tools .input-group .btn{height:38px;border-radius:0 10px 10px 0}
                    .mf-products .mf-sub{display:block;font-size:11px;color:#94a3b8;font-weight:800;margin-top:2px}
                    .mf-products .mf-chip-ty{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:900;border:1px solid #e2e8f0;background:#f8fafc;color:#0f172a}
                    .mf-products .mf-chip-ty.seller{background:#ecfeff;border-color:#a5f3fc;color:#0e7490}
                    .mf-products .mf-chip-ty.inhouse{background:#eef2ff;border-color:#c7d2fe;color:#3730a3}
                    .mf-products .mf-pill{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:900;border:1px solid #e2e8f0}
                    .mf-products .mf-pill.ok{background:#ecfdf5;border-color:#bbf7d0;color:#16a34a}
                    .mf-products .mf-pill.off{background:#fff7ed;border-color:#fed7aa;color:#c2410c}
                    .mf-products .mf-actions{display:inline-flex;gap:8px;align-items:center;justify-content:center;flex-wrap:nowrap}
                    .mf-products .mf-actions .action-btn{min-width:38px}
                    .mf-products .update-quantity-modal .modal-dialog{max-width:560px}
                    .mf-products .update-quantity-modal .modal-content{border-radius:14px}
                    .mf-products .update-quantity-modal .modal-header{border-bottom:0;padding:10px 14px}
                    .mf-products .update-quantity-modal .modal-body{padding:8px 14px 14px}
                    .mf-products .mf-catalogue-h{display:flex;gap:12px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap}
                    .mf-products .mf-catalogue-title{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:900;margin:0}
                    .mf-products .mf-catalogue-sub{width:100%;display:flex;gap:10px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-top:10px}
                    .mf-products .mf-catalogue-sub .mf-tabs{margin:0}
                    .mf-products .mf-catalogue-sub .mf-tools{margin-left:auto}
                    .mf-products .mf-catalogue-actions{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
                    .mf-products .mf-catalogue-actions .btn{height:36px;border-radius:10px;font-weight:900;font-size:12px;display:inline-flex;align-items:center;gap:6px}
                    .mf-products .mf-catalogue-actions .btn.btn-soft{background:#eef2f7;border:1px solid #e2e8f0;color:#0f172a}
                    .mf-products .mf-table thead.thead-light th{font-size:9px;letter-spacing:.08em;text-transform:uppercase;padding:.55rem .7rem}
                    .mf-products .mf-table tbody td{padding:.6rem .7rem}
                </style>
            @endpush

            <div class="mf-products mb-3">
                <div class="mf-welcome">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1>
                                @if($catalogMode === 'inhouse')
                                    {{ translate('In-House Products') }}
                                @elseif($catalogMode === 'seller')
                                    {{ translate('Seller Products') }}
                                @elseif($catalogMode === 'digital')
                                    {{ translate('Digital Products') }}
                                @else
                                    {{ translate('messages.item_list') }}
                                @endif
                            </h1>
                            <p>{{ translate('Manage your items with RMS v12 UI') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip primary" href="{{ route('vendor.item.add-new') }}"><i class="tio-add-circle"></i> {{ translate('messages.add_new_item') }}</a>
                                <a class="mf-chip ghost" href="{{ route('vendor.item.bulk-import') }}">{{ translate('Bulk import') }}</a>
                                <a class="mf-chip ghost" href="{{ route('vendor.item.bulk-export-index') }}">{{ translate('Bulk export') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3 mt-lg-0">
                            <div class="mf-kpis">
                                @if($catalogMode === 'digital')
                                    <div class="mf-kpi">
                                        <div class="t">{{ translate('Digital Products') }}</div>
                                        <div class="v">{{ (int) data_get($k,'total',0) }}</div>
                                        <div class="s">{{ translate('Library size') }}</div>
                                    </div>
                                    <div class="mf-kpi">
                                        <div class="t">{{ translate('Downloads (Mo)') }}</div>
                                        <div class="v">{{ (int) data_get($k,'downloads',0) }}</div>
                                        <div class="s">{{ translate('Based on sales') }}</div>
                                    </div>
                                    <div class="mf-kpi">
                                        <div class="t">{{ translate('Revenue (Mo)') }}</div>
                                        <div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($k,'revenue',0)) }}</div>
                                        <div class="s">{{ translate('Estimated') }}</div>
                                    </div>
                                    <div class="mf-kpi">
                                        <div class="t">{{ translate('Avg Rating') }}</div>
                                        <div class="v">{{ number_format((float) data_get($k,'avg_rating',0), 1) }}</div>
                                        <div class="s">{{ translate('From reviews') }}</div>
                                    </div>
                                @elseif($catalogMode === 'seller')
                                    <div class="mf-kpi kpi-total">
                                        <div class="t">{{ translate('Seller Products') }}</div>
                                        <div class="v">{{ (int) data_get($k,'seller_total', data_get($k,'total',0)) }}</div>
                                        <div class="s">{{ translate('All items') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-published">
                                        <div class="t">{{ translate('Approved') }}</div>
                                        <div class="v">{{ (int) data_get($k,'seller_approved', data_get($k,'published',0)) }}</div>
                                        <div class="s">{{ translate('Live') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-draft">
                                        <div class="t">{{ translate('Pending Review') }}</div>
                                        <div class="v">{{ (int) data_get($k,'seller_pending_review',0) }}</div>
                                        <div class="s">{{ translate('Action needed') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-out">
                                        <div class="t">{{ translate('Rejected') }}</div>
                                        <div class="v">{{ (int) data_get($k,'seller_rejected',0) }}</div>
                                        <div class="s">{{ translate('Changes required') }}</div>
                                    </div>
                                @else
                                    <div class="mf-kpi kpi-total">
                                        <div class="t">{{ translate('Total Products') }}</div>
                                        <div class="v">{{ (int) data_get($k,'total',0) }}</div>
                                        <div class="s">{{ translate('All items') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-published">
                                        <div class="t">{{ translate('Published') }}</div>
                                        <div class="v">{{ (int) data_get($k,'published',0) }}</div>
                                        <div class="s">{{ translate('Visible to customers') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-draft">
                                        <div class="t">{{ translate('Draft') }}</div>
                                        <div class="v">{{ (int) data_get($k,'draft',0) }}</div>
                                        <div class="s">{{ translate('Not published') }}</div>
                                    </div>
                                    <div class="mf-kpi kpi-out">
                                        <div class="t">{{ translate('Out of Stock') }}</div>
                                        <div class="v">{{ (int) data_get($k,'out_of_stock',0) }}</div>
                                        <div class="s">{{ translate('Lost sales risk') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
        <!-- Page Header -->
        <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
            <div class="btn--container align-items-center mb-0">
                <div class="mr-auto">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.item_list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$items->total()}}</span></h1>
                </div>


            </div>
        </div>
        <!-- End Page Header -->


        <!-- End Page Header -->
        @if(!$productsRmsUi)
        <div class="card mb-3">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <h1>{{ translate('search_data') }}</h1>
            </div>
                <div class="row mr-1 ml-2 mb-5">

                    <div class="col-sm-6 col-md-4">
                        <div class="select-item">
                            <select name="category_id" id="category" data-placeholder="{{ translate('messages.select_category') }}"
                                class="js-data-example-ajax form-control set-filter" id="category_id"
                                data-url="{{url()->full()}}" data-filter="category_id">
                                @if($category)
                                <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                @else
                                <option value="all" selected>{{translate('messages.all_category')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="select-item">
                            <select name="sub_category_id" class="form-control js-select2-custom set-filter" data-placeholder="{{ translate('messages.select_sub_category') }}" id="sub-categories" data-url="{{url()->full()}}" data-filter="sub_category_id">
                                <option value="all" selected>{{translate('messages.all_sub_category')}}</option>
                                @foreach($sub_categories as $z)
                                <option
                                    value="{{$z['id']}}" {{ request()?->sub_category_id == $z['id']?'selected':''}}>
                                    {{$z['name']}}
                                </option>
                            @endforeach
                            </select>
                        </div>
                    </div>


                    @if (($store_data->module->module_type == 'food') && $toggle_veg_non_veg)
                    <!-- Veg/NonVeg filter -->

                <div class="col-sm-6 col-md-4">
                    <div class="select-item">
                        <select name="category_id" data-url="{{url()->full()}}" data-filter="type" data-placeholder="{{translate('messages.all')}}" class="form-control max-lg-h-40px set-filter">
                            <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                            <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                            <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                        </select>
                    </div>
                </div>
                    <!-- End Veg/NonVeg filter -->
                    @endif
                </div>
            </div>
        @endif


        <!-- Card -->
        <div class="card {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
            <!-- Header -->
            <div class="card-header py-2  border-0">
                @if($productsRmsUi)
                    <div class="mf-catalogue-h w-100">
                        <div>
                            <div class="mf-catalogue-title">{{ translate('Product Catalogue') }}</div>
                        </div>
                        <div class="mf-catalogue-actions">
                            <a class="btn btn-soft" href="{{ route('vendor.item.bulk-export-index') }}">
                                <i class="tio-download"></i> {{ translate('Export CSV') }}
                            </a>
                            <a class="btn btn--primary" href="{{ route('vendor.item.add-new') }}">
                                <i class="tio-add"></i> {{ translate('Add Product') }}
                            </a>
                        </div>

                        <div class="mf-catalogue-sub">
                            <div class="mf-tabs">
                                <a class="mf-tab {{ ($status_filter ?? 'all') === 'all' ? 'active' : '' }}" href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'all'])) }}">{{ translate('All') }}</a>
                                <a class="mf-tab {{ ($status_filter ?? 'all') === 'published' ? 'active' : '' }}" href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'published'])) }}">{{ translate('Published') }}</a>
                                <a class="mf-tab {{ ($status_filter ?? 'all') === 'draft' ? 'active' : '' }}" href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'draft'])) }}">{{ translate('Draft') }}</a>
                                <a class="mf-tab {{ ($status_filter ?? 'all') === 'out_of_stock' ? 'active' : '' }}" href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'out_of_stock'])) }}">{{ translate('Out of Stock') }}</a>
                                <a class="mf-tab" href="{{ route('vendor.item.pending_item_list') }}">{{ translate('Pending Approval') }}</a>
                            </div>

                            <div class="mf-tools">
                                @php($mfSellerListingTools = $productsRmsUi && $catalogMode === 'seller')
                                @if($mfSellerListingTools)
                                    <div class="select-item" style="min-width:160px">
                                        <select class="form-control" disabled>
                                            <option>{{ (string) collect($sellerOptions ?? [])->values()->first() ?: translate('All Sellers') }}</option>
                                        </select>
                                    </div>
                                    <div class="select-item" style="min-width:160px">
                                        <select class="form-control" onchange="if(this.value){location.href=this.value}">
                                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'all'])) }}">{{ translate('All Status') }}</option>
                                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'published'])) }}">{{ translate('Approved') }}</option>
                                            <option value="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['status' => 'draft'])) }}">{{ translate('Pending') }}</option>
                                        </select>
                                    </div>
                                    <a class="btn btn-soft" href="{{ route('vendor.item.pending_item_list') }}" style="height:38px;border-radius:10px;font-weight:900;font-size:12px;display:inline-flex;align-items:center">
                                        {{ translate('Bulk Review') }}
                                        @if(($pendingApprovalCount ?? 0) > 0)
                                            <span class="badge badge-soft-info badge-pill ml-2">{{ (int) $pendingApprovalCount }}</span>
                                        @endif
                                    </a>
                                @endif
                                @if(!in_array($catalogMode, ['inhouse','digital'], true))
                                    <div class="select-item">
                                        <select name="category_id" id="category" data-placeholder="{{ translate('messages.select_category') }}"
                                            class="js-data-example-ajax form-control set-filter" id="category_id"
                                            data-url="{{url()->full()}}" data-filter="category_id">
                                            @if($category)
                                            <option value="{{$category->id}}" selected>{{$category->name}}</option>
                                            @else
                                            <option value="all" selected>{{translate('messages.all_category')}}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="select-item">
                                        <select name="sub_category_id" class="form-control js-select2-custom set-filter" data-placeholder="{{ translate('messages.select_sub_category') }}" id="sub-categories" data-url="{{url()->full()}}" data-filter="sub_category_id">
                                            <option value="all" selected>{{translate('messages.all_sub_category')}}</option>
                                            @foreach($sub_categories as $z)
                                            <option
                                                value="{{$z['id']}}" {{ request()?->sub_category_id == $z['id']?'selected':''}}>
                                                {{$z['name']}}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if (($store_data->module->module_type == 'food') && $toggle_veg_non_veg)
                                <div class="select-item" style="min-width:180px">
                                    <select name="category_id" data-url="{{url()->full()}}" data-filter="type" data-placeholder="{{translate('messages.all')}}" class="form-control max-lg-h-40px set-filter">
                                        <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                                        <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                                        <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                                    </select>
                                </div>
                                @endif

                                <form class="search-form" action="{{ url()->current() }}" method="get">
                                    @foreach(request()->except('page','search') as $qk => $qv)
                                        <input type="hidden" name="{{ $qk }}" value="{{ $qv }}">
                                    @endforeach
                                    <div class="input-group input--group">
                                        <input type="search" name="search" value="{{ request()?->search ?? null }}" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="search--button-wrapper justify-content-end">
                        <form class="search-form">
                        @csrf
                            <!-- Search -->
                            <div class="input-group input--group">
                                <input id="" type="search" name="search" value="{{ request()?->search ?? null }}" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                            <!-- End Search -->
                        </form>
                        <!-- End Unfold -->
                        <div>
                            <a href="{{route('vendor.item.add-new')}}" class="btn btn--primary m-0 pull-right"><i
                                        class="tio-add-circle"></i> {{translate('messages.add_new_item')}}</a>
                        </div>
                    </div>
                @endif
            </div>
            <!-- End Header -->


            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ $productsRmsUi ? 'mf-products mf-table' : '' }}"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [],
                            "width": "5%",
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },

                        "entries": "#datatableEntries",
                        "isResponsive": false,
                        "isShowPaging": false,
                            "paging":false
                    }'>
                    <thead class="thead-light">
                        <tr>
                            @php($mfCompactCatalogue = $productsRmsUi && in_array($catalogMode, ['inhouse'], true))
                            @php($mfSellerListing = $productsRmsUi && $catalogMode === 'seller')
                            <th class="border-0">{{translate('messages.#')}}</th>
                            <th class="border-0 w-20p {{ $productsRmsUi ? 'mf-namecell' : '' }}">{{translate('messages.name')}}</th>
                            @if($productsRmsUi && !$mfCompactCatalogue && !$mfSellerListing)
                                <th class="border-0 mf-col-sku">{{translate('SKU')}}</th>
                            @endif
                            @if($mfSellerListing)
                                <th class="border-0">{{ translate('Seller') }}</th>
                            @endif
                            <th class="border-0 w-20p">{{translate('messages.category')}}</th>
                            @if($productsRmsUi && !$mfCompactCatalogue && !$mfSellerListing)
                                <th class="border-0 mf-col-type">{{translate('Type')}}</th>
                            @endif
                            @if($productsRmsUi)
                                <th class="border-0 text-center mf-col-stock">{{ translate('Stock') }}</th>
                            @else
                                @if ($store_data->module->module_type != 'food')
                                    <th class="border-0 w-20p">{{translate('messages.quantity')}}</th>
                                @endif
                            @endif
                            <th class="border-0">{{translate('messages.price')}}</th>
                            @if($productsRmsUi)
                                @if($catalogMode === 'digital')
                                    <th class="border-0 text-center mf-col-dl">{{translate('Downloads')}}</th>
                                    <th class="border-0 text-center mf-col-rev">{{translate('Revenue')}}</th>
                                @elseif($mfCompactCatalogue)
                                    <th class="border-0 text-center mf-col-rev">{{translate('Revenue')}}</th>
                                @elseif($mfSellerListing)
                                    <th class="border-0 text-center">{{ translate('Commission') }}</th>
                                    <th class="border-0 text-center">{{ translate('Status') }}</th>
                                    <th class="border-0">{{ translate('Listed Date') }}</th>
                                @else
                                    <th class="border-0 text-center mf-col-sales">{{translate('Sales')}}</th>
                                    <th class="border-0 text-center mf-col-rating">{{translate('Rating')}}</th>
                                @endif
                            @endif
                            @if(!$productsRmsUi && $catalogMode !== 'digital')
                                <th class="border-0 text-center">{{translate('messages.Recommended')}}</th>
                            @endif
                            @if ($productWiseTax && !$mfSellerListing)
                            <th  class="border-0 ">{{ translate('messages.Vat/Tax') }}</th>
                            @endif
                            @if(!$mfSellerListing)
                                <th class="border-0 text-center">{{translate('messages.status')}}</th>
                            @endif
                            <th class="border-0 text-center">{{translate('messages.action')}}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($items as $key=>$item)
                        <tr>
                            <td>{{$key+$items->firstItem()}}</td>
                            <td>
                                @if($productsRmsUi)
                                    <a class="mf-namecard" href="{{route('vendor.item.view',[$item['id']])}}">
                                        <img class="avatar avatar-lg onerror-image" src="{{ $item['image_full_url'] }}"
                                             data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->name}} image">
                                        <div class="mf-n">{{Str::limit($item['name'],24,'...')}}</div>
                                    </a>
                                @else
                                    <a class="media align-items-center" href="{{route('vendor.item.view',[$item['id']])}}">
                                        <img class="avatar avatar-lg mr-3 onerror-image" src="{{ $item['image_full_url'] }}"
                                             data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->name}} image">
                                        <div class="media-body">
                                            <h5 class="text-hover-primary mb-0">{{Str::limit($item['name'],20,'...')}}</h5>
                                        </div>
                                    </a>
                                @endif
                            </td>
                            @if($productsRmsUi && !$mfCompactCatalogue && !$mfSellerListing)
                                @php($skuVal = data_get($item,'sku') ?? data_get($item,'code') ?? ('ID-'.$item['id']))
                                <td class="mf-col-sku">
                                    <span class="text-muted font-weight-bold">{{ $skuVal }}</span>
                                </td>
                            @endif
                            @if($mfSellerListing)
                                <td>
                                    <span class="font-weight-bold text-muted">{{ $store_data?->name ?? $store_data?->store_name ?? translate('Seller') }}</span>
                                </td>
                            @endif
                            <td>
                            {{Str::limit($item->category?$item->category->name:translate('messages.category_deleted'),20,'...')}}
                            </td>
                            @if($productsRmsUi && !$mfCompactCatalogue && !$mfSellerListing)
                                @php($typeLabel = $catalogMode === 'seller' ? 'Seller' : ($catalogMode === 'digital' ? 'Digital' : ($catalogMode === 'inhouse' ? 'In-House' : (($store_data?->store_business_model ?? null) === 'commission' ? 'Seller' : 'In-House'))))
                                @php($typeClass = $typeLabel === 'In-House' ? 'inhouse' : 'seller')
                                <td class="mf-col-type">
                                    <span class="mf-chip-ty {{ $typeClass }}">{{ $typeLabel }}</span>
                                </td>
                            @endif
                            @if($productsRmsUi)
                                <td class="text-center mf-col-stock">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="font-weight-bold">{{$item->stock}}</span>
                                        @if ($store_data->module->module_type != 'food')
                                            <span data-toggle="modal" data-id="{{ $item->id }}" data-target="#update-quantity" class="text-primary tio-add-circle fs-22 cursor-pointer update-quantity"></span>
                                        @endif
                                    </div>
                                </td>
                            @else
                                @if ($store_data->module->module_type != 'food')
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <h5 class="text-hover-primary fw-medium mb-0">{{$item->stock}}</h5>
                                            <span data-toggle="modal"  data-id="{{ $item->id }}"  data-target="#update-quantity" class="text-primary tio-add-circle fs-22 cursor-pointer update-quantity"></span>
                                        </div>
                                    </td>
                                @endif
                            @endif
                            <td>
                                <div class="mw--85px">
                                    {{\App\CentralLogics\Helpers::format_currency($item['price'])}}
                                </div>
                            </td>
                            @if($productsRmsUi)
                                @if($catalogMode === 'digital')
                                    <td class="text-center mf-col-dl">
                                        <span class="font-weight-bold">{{ (int) ($item->order_count ?? 0) }}</span>
                                    </td>
                                    <td class="text-center mf-col-rev">
                                        <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency(((float) ($item->price ?? 0)) * ((int) ($item->order_count ?? 0))) }}</span>
                                    </td>
                                @elseif($mfCompactCatalogue)
                                    <td class="text-center mf-col-rev">
                                        <span class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency(((float) ($item->price ?? 0)) * ((int) ($item->order_count ?? 0))) }}</span>
                                    </td>
                                @elseif($mfSellerListing)
                                    <td class="text-center">
                                        @php($cr = (float) ($commissionRate ?? ($store_data?->comission ?? 0)))
                                        <span class="font-weight-bold text-muted">{{ $cr > 0 ? rtrim(rtrim(number_format($cr, 2), '0'), '.') . '%' : '0%' }}</span>
                                    </td>
                                    <td class="text-center">
                                        @php($st = (int) ($item->status ?? 0))
                                        @php($pill = $st === 1 ? 'ok' : ($st === 2 ? 'off' : 'off'))
                                        <span class="mf-pill {{ $pill }}">{{ $st === 1 ? 'Approved' : 'Pending' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted font-weight-bold">{{ optional($item->created_at)->format('M d') }}</span>
                                    </td>
                                @else
                                    <td class="text-center mf-col-sales">
                                        <span class="font-weight-bold">{{ (int) ($item->order_count ?? 0) }}</span>
                                    </td>
                                    <td class="text-center mf-col-rating">
                                        <span class="mf-rate">
                                            <i class="tio-star text-warning"></i>
                                            <span>{{ number_format((float) ($item->avg_rating ?? 0), 1) }}</span>
                                        </span>
                                    </td>
                                @endif
                            @endif
                            @if(!$productsRmsUi && $catalogMode !== 'digital')
                                <td>
                                    <div class="d-flex">
                                        <div class="mx-auto">
                                            <label class="toggle-switch toggle-switch-sm mr-2"  data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Recommend_to_customers') }}" for="recCheckbox{{$item->id}}">
                                                <input type="checkbox" data-url="{{route('vendor.item.recommended',[$item['id'],$item->recommended?0:1])}}" class="toggle-switch-input redirect-url" id="recCheckbox{{$item->id}}" {{$item->recommended?'checked':''}}>
                                                <span class="toggle-switch-label">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            @endif

                              @if ($productWiseTax && !$mfSellerListing)
                                <td>
                                    <span class="d-block font-size-sm text-body">
                                        @forelse ($item?->taxVats?->pluck('tax.name', 'tax.tax_rate')->toArray() as $key => $tax)
                                            <span> {{ $tax }} : <span class="font-bold">
                                                    ({{ $key }}%)
                                                </span> </span>
                                            <br>
                                        @empty
                                            <span> {{ translate('messages.no_tax') }} </span>
                                        @endforelse
                                    </span>
                                </td>
                                @endif



                            @if(!$mfSellerListing)
                                <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$item->id}}">
                                        <input type="checkbox" data-url="{{route('vendor.item.status',[$item['id'],$item->status?0:1])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$item->id}}" {{$item->status?'checked':''}}>
                                        <span class="toggle-switch-label mx-auto">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                    </label>
                                </td>
                            @endif
                            <td>
                                <div class="{{ $productsRmsUi ? 'mf-actions' : 'btn--container justify-content-center' }}">
                                    @if($productsRmsUi && $catalogMode !== 'digital' && !$mfCompactCatalogue && !$mfSellerListing)
                                        <label class="toggle-switch toggle-switch-sm mr-2" data-toggle="tooltip" data-placement="top" title="{{ translate('messages.Recommend_to_customers') }}" for="recCheckbox{{$item->id}}">
                                            <input type="checkbox" data-url="{{route('vendor.item.recommended',[$item['id'],$item->recommended?0:1])}}" class="toggle-switch-input redirect-url" id="recCheckbox{{$item->id}}" {{$item->recommended?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @endif
                                    @if($mfSellerListing)
                                        <label class="toggle-switch toggle-switch-sm mr-2" for="stocksCheckbox{{$item->id}}">
                                            <input type="checkbox" data-url="{{route('vendor.item.status',[$item['id'],$item->status?0:1])}}" class="toggle-switch-input redirect-url" id="stocksCheckbox{{$item->id}}" {{$item->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @endif
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                        href="{{route('vendor.item.edit',[$item['id']])}}" title="{{translate('messages.edit_item')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert" href="javascript:"
                                        data-id="food-{{$item['id']}}" data-message="{{ translate('Want to delete this item ?') }}" title="{{translate('messages.delete_item')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('vendor.item.delete',[$item['id']])}}"
                                        method="post" id="food-{{$item['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="page-area">
                    <table>
                        <tfoot class="border-top">
                        {!! $items->links() !!}
                        </tfoot>
                    </table>
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
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>
</div>


    {{-- Add Quantity Modal --}}
    <div class="modal fade update-quantity-modal" id="update-quantity" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-0">

                    <form action="{{route('vendor.item.stock-update')}}" method="post">
                        @csrf
                        <div class="mt-2 rest-part w-100"></div>
                        <div class="btn--container justify-content-end">
                            <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn--reset">{{translate('cancel')}}</button>
                            <button type="submit" id="submit_new_customer" class="btn btn--primary">{{translate('update_stock')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function () {
        // INITIALIZATION OF DATATABLES
        // =======================================================
        let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
          select: {
            style: 'multi',
            classMap: {
              checkAll: '#datatableCheckAll',
              counter: '#datatableCounter',
              counterInfo: '#datatableCounterInfo'
            }
          },
          language: {
            zeroRecords: '<div class="text-center p-4">' +
                '<img class="w-7rem mb-3" src="{{asset('assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +

                '</div>'
          }
        });






            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#category').select2({
            ajax: {
                url: '{{route("vendor.category.get-all")}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        all:true,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        // $('#search-form').on('submit', function (e) {
        //     e.preventDefault();
        //     let formData = new FormData(this);
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.post({
        //         url: '{{route('vendor.item.search')}}',
        //         data: formData,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         beforeSend: function () {
        //             $('#loading').show();
        //         },
        //         success: function (data) {
        //             $('#set-rows').html(data.view);
        //             $('#itemCount').html(data.count);
        //             $('.page-area').hide();
        //         },
        //         complete: function () {
        //             $('#loading').hide();
        //         },
        //     });
        // });

        $(document).on('click', '.update-quantity', function () {
            let val = $(this).data('id');
            let $wrap = $('.rest-part');

            $wrap.html(
                '<div class="p-3 text-center text-muted" style="min-height:120px;display:flex;align-items:center;justify-content:center">' +
                '<span>{{ translate('messages.loading') ?? 'Loading…' }}</span>' +
                '</div>'
            );

            $.get({
                url: '{{ route('vendor.item.get_stock') }}',
                data: { id: val },
                success: function (data) {
                    // Accept both JSON ({view: ...}) and raw HTML (in case of redirects/errors)
                    let viewHtml = '';
                    try {
                        let parsed = (typeof data === 'string') ? JSON.parse(data) : data;
                        viewHtml = parsed && parsed.view ? parsed.view : (typeof data === 'string' ? data : '');
                    } catch (e) {
                        viewHtml = (typeof data === 'string') ? data : '';
                    }
                    $wrap.empty().html(viewHtml || '');
                    update_qty();
                },
                error: function () {
                    $wrap.html(
                        '<div class="p-3 text-center text-danger" style="min-height:120px;display:flex;align-items:center;justify-content:center">' +
                        '<span>{{ translate('messages.something_went_wrong') ?? 'Something went wrong' }}</span>' +
                        '</div>'
                    );
                }
            });
        });

    function update_qty() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if(qty_elements.length > 0)
            {

                $('input[name="current_stock"]').attr("readonly", 'readonly');
                $('input[name="current_stock"]').val(total_qty);
            }
            else{
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }
    </script>
@endpush
