@extends('layouts.admin.app')

@section('title',translate('Store List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .mf-rms-toolbar { display:flex; flex-wrap:wrap; gap:10px; align-items:center; justify-content:space-between; margin-bottom:12px; }
        .mf-rms-chart-wrap { height:220px; position:relative; }
        .mf-vendor-dd .dropdown-menu { min-width: 12rem; padding:.35rem 0; }
        .mf-vendor-dd .dropdown-menu form{ margin:0; }
        .mf-vendor-dd .dropdown-menu .dropdown-item{ font-weight:800; padding:.45rem 1rem; }
        .mf-vendor-dd .dropdown-menu .dropdown-item:hover{ background:#f1f5f9; }
        .mf-vendor-dd .dropdown-menu{ width:auto !important; max-width: 220px; }
        .mf-vendor-dd .dropdown-menu .dropdown-item{ white-space:nowrap; min-width:0; }
        .mf-headbar{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:space-between}
        .mf-headbar .mf-left{display:flex;flex-wrap:wrap;gap:8px;align-items:center}
        .mf-subtitle{letter-spacing:.08em;font-size:12px;font-weight:900;text-transform:uppercase;color:#64748b}
        .mf-filters{display:flex;flex-wrap:wrap;gap:10px;align-items:center;justify-content:flex-end}
        .mf-filters .select-item,.mf-filters .search-form,.mf-filters .hs-unfold{margin:0!important}
        .mf-filters .form-control{height:40px;border-radius:12px}
        .mf-filters .btn{height:40px;border-radius:12px;font-weight:900}
        .mf-store-sub{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-top:4px;color:#64748b;font-size:12px;font-weight:800}
        .mf-store-sub .star{color:#f59e0b}
        .mf-kpi-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:14px}
        @media(max-width:991.98px){.mf-kpi-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:575.98px){.mf-kpi-grid{grid-template-columns:1fr}}
        .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:12px 14px}
        .mf-kpi .t{font-size:10px;font-weight:900;color:#64748b;letter-spacing:.1em;text-transform:uppercase}
        .mf-kpi .v{font-size:24px;font-weight:900;color:#0f172a;margin-top:6px;line-height:1.1}
        .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
        .mf-kpi.mini .v{font-size:20px}
        .mf-kpi .ico{width:34px;height:34px;border-radius:12px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;border:1px solid #e2e8f0;color:#0f172a;font-size:16px}
        .mf-qa .btn{border-radius:12px;height:40px;font-weight:900}
        .mf-qa .btn.primary{background:#5566ff;border-color:#5566ff;color:#fff}
        .mf-qa .btn.soft{background:#eef2f7;border-color:#e2e8f0;color:#0f172a}
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.stores')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$stores->total()}}</span></h1>
            <div class="page-header-select-wrapper">
            </div>
        </div>
        <div class="mf-rms-toolbar">
            <span class="text-muted small">{{ translate('Vendor performance & revenue overview') }}</span>
            <div class="d-flex flex-wrap" style="gap:8px">
                <a class="btn btn-sm btn-primary" href="{{ route('admin.store.all-sellers') }}">{{ translate('All Sellers') }}</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.pending-requests') }}">{{ translate('messages.new_stores') }}</a>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="mf-kpi-grid">
            <div class="mf-kpi">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Total Vendors') }}</div>
                        <div class="v">{{ (int) ($total_store ?? 0) }}</div>
                    </div>
                    <div class="ico">🏪</div>
                </div>
                <div class="s">{{ (int) ($active_stores ?? 0) }} {{ translate('Approved') }} · {{ (int) (($total_store ?? 0) - ($active_stores ?? 0)) }} {{ translate('Pending') }}</div>
            </div>
            <div class="mf-kpi">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Vendor Revenue') }}</div>
                        <div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) ($vendorRevenue ?? 0)) }}</div>
                    </div>
                    <div class="ico">💰</div>
                </div>
                <div class="s">{{ translate('Not refunded transactions') }}</div>
            </div>
            <div class="mf-kpi">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Commission Earned') }}</div>
                        <div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) ($comission_earned ?? 0)) }}</div>
                    </div>
                    <div class="ico">🧾</div>
                </div>
                <div class="s">{{ (int) ($total_transaction ?? 0) }} {{ translate('Transactions') }}</div>
            </div>
            <div class="mf-kpi">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Avg Vendor Rating') }}</div>
                        <div class="v">{{ number_format((float) ($avgVendorRating ?? 0), 1) }} ★</div>
                    </div>
                    <div class="ico">⭐</div>
                </div>
                <div class="s">{{ translate('Across active vendors') }}</div>
            </div>
        </div>

        <div class="mf-kpi-grid" style="grid-template-columns:repeat(4,minmax(0,1fr));margin-top:-6px">
            <div class="mf-kpi mini">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Total stores') }}</div>
                        <div class="v">{{ (int) ($total_store ?? 0) }}</div>
                    </div>
                    <div class="ico">🏬</div>
                </div>
            </div>
            <div class="mf-kpi mini">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Active stores') }}</div>
                        <div class="v">{{ (int) ($active_stores ?? 0) }}</div>
                    </div>
                    <div class="ico">✅</div>
                </div>
            </div>
            <div class="mf-kpi mini">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Inactive stores') }}</div>
                        <div class="v">{{ (int) ($inactive_stores ?? 0) }}</div>
                    </div>
                    <div class="ico">⛔</div>
                </div>
            </div>
            <div class="mf-kpi mini">
                <div class="d-flex align-items-start justify-content-between" style="gap:10px">
                    <div>
                        <div class="t">{{ translate('Newly joined stores') }}</div>
                        <div class="v">{{ (int) ($recent_stores ?? 0) }}</div>
                    </div>
                    <div class="ico">🆕</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header py-2"><span class="card-title mb-0">{{ translate('Vendor revenue split') }}</span></div>
                    <div class="card-body">
                        <div class="mf-rms-chart-wrap">
                            <canvas id="rmsRevSplitChart"></canvas>
                        </div>
                        @if (array_sum($revData ?? [0, 0]) <= 0)
                            <p class="text-muted small mb-0">{{ translate('No revenue data yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card h-100">
                    <div class="card-header py-2"><span class="card-title mb-0">{{ translate('Quick actions') }}</span></div>
                    <div class="card-body d-flex flex-column mf-qa" style="gap:10px">
                        <a class="btn btn-block primary" href="{{ route('admin.store.pending-sellers') }}">{{ translate('Review Pending') }} ({{ (int) ($pendingSellersCount ?? 0) }})</a>
                        <a class="btn btn-block primary" href="javascript:">{{ translate('Vendor Promotions') }}</a>
                        <a class="btn btn-block soft" href="{{ route('admin.store.vendor-payouts') }}">{{ translate('Process Payouts') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Transaction Information -->
        <ul class="transaction--information text-uppercase">
            <li class="text--info">
                <i class="tio-document-text-outlined"></i>
                <div>
                    <span>{{translate('messages.total_transactions')}}</span> <strong>{{$total_transaction}}</strong>
                </div>
            </li>

            @if (auth('admin')->user()->role_id == 1)
                <li class="seperator"></li>
                <li class="text--success">
                    <i class="tio-checkmark-circle-outlined success--icon"></i>
                    <div>
                        <span>{{translate('messages.commission_earned')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($comission_earned)}}</strong>
                    </div>
                </li>
            @endif

            <li class="seperator"></li>
            <li class="text--danger">
                <i class="tio-atm"></i>
                <div>
                    <span>{{translate('messages.total_store_withdraws')}}</span> <strong>{{\App\CentralLogics\Helpers::format_currency($store_withdraws)}}</strong>
                </div>
            </li>
        </ul>
        <!-- Transaction Information -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2">
                <div class="mf-headbar">
                    <div class="mf-left">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.all-sellers') }}">{{ translate('All Sellers') }}</a>
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.add') }}">+ {{ translate('Invite Seller') }}</a>
                        <div class="mf-subtitle ml-2">{{ translate('Vendor Performance') }}</div>
                    </div>

                    <div class="mf-filters">
                        @if(!isset(auth('admin')->user()->zone_id))
                            <div class="select-item min--280">
                                <select name="zone_id" class="form-control js-select2-custom set-filter" data-url="{{url()->full()}}" data-filter="zone_id">
                                    <option value="" {{!request('zone_id')?'selected':''}}>{{ translate('messages.All_Zones') }}</option>
                                    @foreach(\App\Models\Zone::orderBy('name')->get(['id','name']) as $z)
                                        <option value="{{$z['id']}}" {{isset($zone) && $zone->id == $z['id']?'selected':''}}>
                                            {{$z['name']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="select-item min--220">
                            <select name="vendor_type" class="form-control js-select2-custom set-filter" data-url="{{ url()->full() }}" data-filter="vendor_type">
                                <option value="" {{ !request('vendor_type') ? 'selected' : '' }}>{{ translate('All Vendor Types') }}</option>
                                <option value="shopkeeper" {{ request('vendor_type') === 'shopkeeper' ? 'selected' : '' }}>{{ translate('Shopkeeper') }}</option>
                                <option value="manufacturer" {{ request('vendor_type') === 'manufacturer' ? 'selected' : '' }}>{{ translate('Manufacturer') }}</option>
                                <option value="wholesale" {{ request('vendor_type') === 'wholesale' ? 'selected' : '' }}>{{ translate('Wholesale Vendor') }}</option>
                                <option value="b2b" {{ request('vendor_type') === 'b2b' ? 'selected' : '' }}>{{ translate('B2B Vendor') }}</option>
                            </select>
                        </div>

                        <form class="search-form">
                            <div class="input-group input--group">
                                <input id="datatableSearch_" type="search" value="{{ request()?->search ?? null }}" name="search" class="form-control"
                                       placeholder="{{translate('ex_:_Search_Store_Name')}}" aria-label="{{translate('messages.search')}}" >
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        </form>

                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
                               data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                            </a>

                            <div id="usersExportDropdown"
                                 class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                <a id="export-excel" class="dropdown-item" href="{{route('admin.store.export', ['type'=>'excel',request()->getQueryString()])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                         src="{{ asset('assets/admin') }}/svg/components/excel.svg"
                                         alt="Image Description">
                                    {{ translate('messages.excel') }}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="{{route('admin.store.export', ['type'=>'csv',request()->getQueryString()])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                         src="{{ asset('assets/admin') }}/svg/components/placeholder-csv-format.svg"
                                         alt="Image Description">
                                    .{{ translate('messages.csv') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false

                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('sl')}}</th>
                        <th class="border-0">{{translate('messages.store_information')}}</th>
                        <th class="border-0">{{translate('messages.owner_information')}}</th>
                        <th class="border-0">{{translate('messages.zone')}}</th>
                        <th class="border-0">{{ translate('Vendor Type') }}</th>
                        <th class="border-0">{{ translate('messages.products') }}</th>
                        <th class="border-0">{{ translate('Revenue') }}</th>
                        <th class="border-0">{{ translate('Comm.') }} %</th>
                        <th class="text-uppercase border-0">{{translate('messages.featured')}}</th>
                        <th class="text-uppercase border-0">{{translate('messages.status')}}</th>
                        <th class="text-center border-0">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($stores as $key=>$store)
                        <tr>
                            <td>{{$key+$stores->firstItem()}}</td>
                            <td>
                                <div>
                                    <a href="{{route('admin.store.view', $store->id)}}" class="table-rest-info" alt="view store">
                                        <img class="img--60 circle onerror-image" data-onerror-image="{{asset('assets/admin/img/160x160/img1.jpg')}}"
                                             src="{{ $store['logo_full_url'] ?? asset('assets/admin/img/160x160/img1.jpg') }}" >
                                        <div class="info">
                                            <div title="{{ $store?->name }}" class="text--title">
                                                {{Str::limit($store->name,20,'...')}}
                                            </div>
                                            <div class="font-light">
                                                {{translate('messages.id')}}:{{$store->id}}
                                            </div>
                                            <div class="mf-store-sub">
                                                <span class="star">★</span>
                                                <span>{{ number_format((float) ($store->rating ?? 0), 1) }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </td>

                            <td>
                                <span title="{{ $store?->vendor?->f_name.' '.$store?->vendor?->l_name }}" class="d-block font-size-sm text-body">
                                    {{Str::limit($store->vendor->f_name.' '.$store->vendor->l_name,20,'...')}}
                                </span>
                                <div>
                                    <a href="tel:{{ $store['phone'] }}">
                                        {{$store['phone']}}
                                    </a>
                                </div>
                            </td>
                            <td>
                                {{$store->zone?$store->zone->name:translate('messages.zone_deleted')}}
                            </td>
                            <td>
                                <div class="d-flex flex-column align-items-start mf-vendor-dd" style="gap:6px">
                                    <span class="badge badge-soft-dark text-uppercase">{{ $store->vendor_type ?? 'shopkeeper' }}</span>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-white dropdown-toggle" type="button" id="vendorTypeMenu{{ $store->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ translate('Vendor Type') }}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="vendorTypeMenu{{ $store->id }}">
                                            <form action="{{ route('admin.store.vendor-type', $store->id) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="vendor_type" value="shopkeeper">
                                                <button type="submit" class="dropdown-item">{{ translate('Set Shopkeeper') }}</button>
                                            </form>
                                            <form action="{{ route('admin.store.vendor-type', $store->id) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="vendor_type" value="manufacturer">
                                                <button type="submit" class="dropdown-item">{{ translate('Set Manufacturer') }}</button>
                                            </form>
                                            <form action="{{ route('admin.store.vendor-type', $store->id) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="vendor_type" value="wholesale">
                                                <button type="submit" class="dropdown-item">{{ translate('Set Wholesale') }}</button>
                                            </form>
                                            <form action="{{ route('admin.store.vendor-type', $store->id) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="vendor_type" value="b2b">
                                                <button type="submit" class="dropdown-item">{{ translate('Set B2B') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @php
                                $rev = (float) ($store->rms_store_revenue ?? 0);
                                $commSum = (float) ($store->rms_store_commission_sum ?? 0);
                                $pct = $rev + $commSum > 0 ? round(($commSum / max(0.0001, $rev + $commSum)) * 100, 1) : (float) ($store->comission ?? 0);
                            @endphp
                            <td>{{ (int) ($store->items_count ?? 0) }}</td>
                            <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($rev) }}</td>
                            <td>{{ $pct }}%</td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="featuredCheckbox{{$store->id}}">
                                    <input type="checkbox" data-url="{{route('admin.store.featured',[$store->id,$store->featured?0:1])}}" class="toggle-switch-input redirect-url" id="featuredCheckbox{{$store->id}}" {{$store->featured?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>

                            <td>
                                @if(isset($store->vendor->status))
                                    @if($store->vendor->status)
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$store->id}}">
                                            <input type="checkbox" data-url="{{route('admin.store.status',[$store->id,$store->status?0:1])}}" data-message="{{translate('messages.you_want_to_change_this_store_status')}}" class="toggle-switch-input status_change_alert" id="stocksCheckbox{{$store->id}}" {{$store->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    @else
                                        <span class="badge badge-soft-danger">{{translate('messages.denied')}}</span>
                                    @endif
                                @else
                                    <span class="badge badge-soft-danger">{{translate('messages.pending')}}</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn action-btn btn--warning btn-outline-warning"
                                       href="{{route('admin.store.view', $store->id)}}"
                                       title="{{ translate('messages.view') }}"><i class="tio-visible-outlined"></i>
                                    </a>
                                    <a class="btn action-btn btn--primary btn-outline-primary"
                                       href="{{route('admin.store.edit',[$store['id']])}}" title="{{translate('messages.edit_store')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                       data-id="vendor-{{$store['id']}}" data-message="{{translate('You want to remove this store')}}" title="{{translate('messages.delete_store')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.store.delete',[$store['id']])}}" method="post" id="vendor-{{$store['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
                @if(count($stores) !== 0)
                <hr>
                @endif
                <div class="page-area">
                    {!! $stores->withQueryString()->links() !!}
                </div>
                @if(count($stores) === 0)
                <div class="empty--data">
                    <img src="{{asset('/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
        $('.status_change_alert').on('click', function (event) {
            let url = $(this).data('url');
            let message = $(this).data('message');
            status_change_alert(url, message, event)
        })

        function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: '{{ translate('Are you sure?') }}' ,
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
                    location.href=url;
                }
            })
        }
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        // Portal bulk controls removed (vendor_type is the unified control)

        function rmsLoadChartJs(cb) {
            if (typeof Chart !== 'undefined') {
                cb();
                return;
            }
            var s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
            s.onload = cb;
            document.head.appendChild(s);
        }

        rmsLoadChartJs(function () {
            var el = document.getElementById('rmsRevSplitChart');
            if (!el) return;
            var labels = @json($revLabels ?? []);
            var data = @json($revData ?? [0, 0]);
            if (!labels.length) { labels = ['A', 'B']; data = [0, 0]; }
            new Chart(el, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: ['#0d9488', '#6366f1'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        });
    </script>

@endpush
