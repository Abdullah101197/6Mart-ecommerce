@extends('layouts.vendor.app')

@section('title',translate('messages.Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        @php($store = \App\CentralLogics\Helpers::get_store_data())
        @php($vt = strtolower((string) ($store?->vendor_type ?? config('vendor_types.default','shopkeeper'))))
        @php($sub = $store?->store_sub ?? $store?->store_sub_update_application)
        @php($allowAll = ($store?->store_business_model ?? null) === 'commission')
        @php($ordersRmsUi = $allowAll || ((int) data_get($sub, 'order_rms_ui', 1) === 1))
        @php($os = $order_stats ?? [])
        @php($kpi = $order_kpis ?? [])

        @if($ordersRmsUi)
            @push('css_or_js')
                <style>
                    .mf-orders{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
                    .mf-orders .mf-welcome{
                        background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
                        border-radius:14px;padding:18px 20px;color:#fff;position:relative;overflow:hidden
                    }
                    .mf-orders .mf-welcome:before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04)}
                    .mf-orders .mf-welcome:after{content:'';position:absolute;right:60px;bottom:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.03)}
                    .mf-orders .mf-welcome h1{font-size:16px;font-weight:900;margin:0;position:relative;color:#fff}
                    .mf-orders .mf-welcome p{font-size:12px;opacity:.82;margin:6px 0 0;position:relative;color:#fff}
                    .mf-orders .mf-chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;position:relative}
                    .mf-orders .mf-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:7px 12px;font-weight:900;font-size:12px;border:1px solid rgba(255,255,255,.28);text-decoration:none}
                    .mf-orders .mf-chip.primary{background:#fff;color:#0f172a;border-color:#fff}
                    .mf-orders .mf-chip.ghost{background:rgba(255,255,255,.14);color:#fff}
                    .mf-orders .mf-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin:12px 0 14px}
                    .mf-orders .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:12px 14px}
                    .mf-orders .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                    .mf-orders .mf-kpi .v{font-size:20px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                    .mf-orders .mf-kpi .s{font-size:11px;color:#64748b;margin-top:4px}
                    .mf-orders .mf-tabs{display:flex;gap:8px;flex-wrap:wrap;margin:0 0 10px}
                    .mf-orders .mf-tab{background:#fff;border:1px solid #e2e8f0;border-radius:999px;padding:6px 10px;font-weight:900;font-size:12px;color:#0f172a;text-decoration:none}
                    .mf-orders .mf-tab.active{background:var(--primary-clr,#107980);border-color:var(--primary-clr,#107980);color:#fff}
                    .mf-orders .mf-pill{display:inline-flex;align-items:center;border-radius:999px;padding:3px 9px;font-size:10px;font-weight:900;border:1px solid #e2e8f0;background:#f8fafc;color:#334155}
                    .mf-orders .mf-pill.info{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
                    .mf-orders .mf-pill.ok{background:#ecfdf5;border-color:#bbf7d0;color:#166534}
                    .mf-orders .mf-pill.warn{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
                    .mf-orders .mf-pill.bad{background:#fef2f2;border-color:#fecaca;color:#991b1b}
                    .mf-orders .mf-toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:space-between}
                    .mf-orders .mf-toolbar .right{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
                    .mf-orders .mf-btn{border-radius:10px;height:40px;display:inline-flex;align-items:center;gap:6px;font-weight:900}
                    .mf-orders .mf-btn.primary{background:var(--primary-clr,#107980);border-color:var(--primary-clr,#107980);color:#fff}
                    .mf-orders .mf-btn.light{background:#fff;border:1px solid #e2e8f0;color:#0f172a}
                    .mf-orders .mf-actions{display:inline-flex;gap:8px;align-items:center;justify-content:center;flex-wrap:nowrap}
                    .mf-orders .mf-actions .action-btn{min-width:64px}
                    .mf-orders .mf-actions .action-btn.btn-sm{padding:.35rem .6rem}
                </style>
            @endpush

            <div class="mf-orders">
                <div class="mf-welcome mb-3">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1>{{ translate('Order Management') }}</h1>
                            <p>{{ translate('Manage purchase orders, statuses, and fulfillment.') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip primary" href="{{ route('vendor.order.list',['all']) }}">{{ translate('All') }}</a>
                                <a class="mf-chip ghost" href="{{ route('vendor.order.export',['status'=>$status,'file_type'=>'excel','type'=>'order', request()->getQueryString()]) }}">{{ translate('Export') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-3 mt-lg-0">
                            <div class="mf-kpis">
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Total Orders') }}</div>
                                    <div class="v">{{ (int) data_get($kpi,'total_orders',0) }}</div>
                                    <div class="s">{{ translate('This month') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Pending Pickup') }}</div>
                                    <div class="v">{{ (int) data_get($kpi,'pending_pickup',0) }}</div>
                                    <div class="s">{{ translate('Action needed') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('In Transit') }}</div>
                                    <div class="v">{{ (int) data_get($kpi,'in_transit',0) }}</div>
                                    <div class="s">{{ translate('Picked up') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Returns') }}</div>
                                    <div class="v">{{ (int) data_get($kpi,'returns',0) }}</div>
                                    <div class="s">{{ translate('This month') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- Keep wrapper open so tabs/toolbar use RMS styles --}}
        @endif

        <!-- Page Header -->
        @if(!$ordersRmsUi)
        <div class="page-header">
            <h1 class="page-header-title text-capitalize">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/order.png')}}" class="w--26" alt="">
                </span>
                <span>
                    {{translate(str_replace('_',' ',$status))}} {{translate('messages.orders')}}
                    <span class="badge badge-soft-dark ml-2">{{$orders->total()}}</span>
                </span>
            </h1>
        </div>
        @endif
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                @if($ordersRmsUi)
                    <div class="mf-toolbar">
                        <div class="mf-tabs mb-0">
                            @php($tabs = [
                                'all' => translate('All'),
                                'pending' => translate('Pending Pickup'),
                                'confirmed' => translate('Packed'),
                                'item_on_the_way' => translate('In Transit'),
                                'delivered' => translate('Delivered'),
                                'refund_requested' => translate('Return Initiated'),
                            ])
                            @foreach($tabs as $k => $lbl)
                                <a class="mf-tab {{ $status === $k ? 'active' : '' }}" href="{{ route('vendor.order.list', [$k]) }}">{{ $lbl }}</a>
                            @endforeach
                        </div>
                        <div class="right">
                            <form class="search-form min--260">
                                <div class="input-group input--group">
                                    <input type="search" value="{{ request()?->search ?? null }}" name="search" class="form-control" placeholder="{{translate('messages.ex_:_search_order_id')}}" aria-label="{{translate('messages.search')}}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>

                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-sm mf-btn light dropdown-toggle" href="javascript:"
                                   data-hs-unfold-options='{"target": "#usersExportDropdown","type": "css-animation"}'>
                                    <i class="tio-download-to"></i> {{translate('messages.export')}}
                                </a>
                                <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                    <span class="dropdown-header">{{translate('messages.options')}}</span>
                                    <a id="export-copy" class="dropdown-item" href="javascript:">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('assets/admin/svg/illustrations/copy.svg')}}" alt="Image Description">
                                        {{translate('messages.copy')}}
                                    </a>
                                    <a id="export-print" class="dropdown-item" href="javascript:">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('assets/admin/svg/illustrations/print.svg')}}" alt="Image Description">
                                        {{translate('messages.print')}}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-header">{{translate('messages.download_options')}}</span>
                                    <a id="export-excel" class="dropdown-item" href="javascript:">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('assets/admin/svg/components/excel.svg')}}" alt="Image Description">
                                        {{translate('messages.excel')}}
                                    </a>
                                    <a id="export-csv" class="dropdown-item" href="javascript:">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('assets/admin/svg/components/placeholder-csv-format.svg')}}" alt="Image Description">
                                        .{{translate('messages.csv')}}
                                    </a>
                                    <a id="export-pdf" class="dropdown-item" href="javascript:">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('assets/admin/svg/components/pdf.svg')}}" alt="Image Description">
                                        {{translate('messages.pdf')}}
                                    </a>
                                </div>
                            </div>

                            @if(\App\CentralLogics\Helpers::employee_module_permission_check('pos'))
                                <a class="btn btn-sm mf-btn primary" href="{{ route('vendor.pos.index') }}">{{ translate('+ New PO') }}</a>
                            @endif

                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker btn btn-sm mf-btn light" href="javascript:"
                                   data-hs-unfold-options='{"target": "#showHideDropdown","type": "css-animation"}'>
                                    <i class="tio-table"></i> {{translate('messages.column')}}
                                </a>
                                <div id="showHideDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                                    <div class="card card-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{translate('messages.order')}}</span>

                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_order" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{translate('messages.date')}}</span>
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_date" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{translate('messages.customer')}}</span>
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_customer">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_customer" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2 text-capitalize">{{translate('messages.total_amount')}}</span>
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_payment_status">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_payment_status" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <span class="mr-2">{{translate('messages.order_status')}}</span>
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_order_status" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="mr-2">{{translate('messages.actions')}}</span>
                                                <label class="toggle-switch toggle-switch-sm" for="toggleColumn_actions">
                                                    <input type="checkbox" class="toggle-switch-input" id="toggleColumn_actions" checked>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                <div class="search--button-wrapper justify-content-end">
                        <form class="search-form min--260">

                            <!-- Search -->
                            <div class="input-group input--group">
                                <input  type="search" value="{{  request()?->search ?? null }}" name="search" class="form-control" placeholder="{{translate('messages.ex_:_search_order_id')}}" aria-label="{{translate('messages.search')}}" >
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                            <!-- End Search -->
                        </form>
                        <!-- Unfold -->
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                            </a>

                            <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{translate('messages.options')}}</span>
                                <a id="export-copy" class="dropdown-item" href="javascript:">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('assets/admin/svg/illustrations/copy.svg')}}"
                                            alt="Image Description">
                                    {{translate('messages.copy')}}
                                </a>
                                <a id="export-print" class="dropdown-item" href="javascript:">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('assets/admin/svg/illustrations/print.svg')}}"
                                            alt="Image Description">
                                    {{translate('messages.print')}}
                                </a>
                                <div class="dropdown-divider"></div>
                                <span
                                    class="dropdown-header">{{translate('messages.download_options')}}</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('assets/admin/svg/components/excel.svg')}}"
                                            alt="Image Description">
                                    {{translate('messages.excel')}}
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('assets/admin/svg/components/placeholder-csv-format.svg')}}"
                                            alt="Image Description">
                                    .{{translate('messages.csv')}}
                                </a>
                                <a id="export-pdf" class="dropdown-item" href="javascript:">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{asset('assets/admin/svg/components/pdf.svg')}}"
                                            alt="Image Description">
                                    {{translate('messages.pdf')}}
                                </a>
                            </div>
                        </div>
                        <!-- End Unfold -->

                        <!-- Unfold -->
                        <div class="hs-unfold">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white h--40px" href="javascript:"
                                data-hs-unfold-options='{
                                    "target": "#showHideDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-table mr-1"></i> {{translate('messages.column')}} <span
                                    class="badge badge-soft-dark rounded-circle ml-1"></span>
                            </a>

                            <div id="showHideDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                                <div class="card card-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.order')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.date')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_date">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_date" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.customer')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_customer">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_customer" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span
                                                class="mr-2 text-capitalize">{{translate('messages.total_amount')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_payment_status">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_payment_status" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="mr-2">{{translate('messages.order_status')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm" for="toggleColumn_order_status">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_order_status" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="mr-2">{{translate('messages.actions')}}</span>

                                            <!-- Checkbox Switch -->
                                            <label class="toggle-switch toggle-switch-sm"
                                                    for="toggleColumn_actions">
                                                <input type="checkbox" class="toggle-switch-input"
                                                        id="toggleColumn_actions" checked>
                                                <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <!-- End Checkbox Switch -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Unfold -->
                    </div>
                </div>
                <!-- End Row -->
                @endif
            </div>
            <!-- End Header -->
            <div class="card-body p-0">
                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table id="datatable" class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                                    "order": [],
                                    "orderCellsTop": true,
                                    "paging":false
                                }'>
                        <thead class="thead-light">
                        <tr>
                            @if(!$ordersRmsUi)
                                <th class="border-0">
                                    {{translate('messages.#')}}
                                </th>
                                <th class="border-0 table-column-pl-0">{{translate('messages.order_id')}}</th>
                                <th class="border-0">{{translate('messages.order_date')}}</th>
                                <th class="border-0">{{translate('messages.customer_information')}}</th>
                                <th class="border-0">{{translate('messages.total_amount')}}</th>
                                <th class="border-0 text-center">{{translate('messages.order_status')}}</th>
                                <th class="border-0 text-center">{{translate('messages.actions')}}</th>
                            @else
                                <th class="border-0 table-column-pl-0">{{ translate('PO ID') }}</th>
                                <th class="border-0">{{ translate('Buyer') }}</th>
                                <th class="border-0">{{ translate('City') }}</th>
                                <th class="border-0">{{ translate('Channel') }}</th>
                                <th class="border-0">{{ translate('Category') }}</th>
                                <th class="border-0">{{ translate('Qty') }}</th>
                                <th class="border-0">{{ translate('Amount') }}</th>
                                <th class="border-0">{{ translate('Payment') }}</th>
                                <th class="border-0">{{ translate('Status') }}</th>
                                <th class="border-0">{{ translate('ETA') }}</th>
                                <th class="border-0 text-center">{{ translate('Actions') }}</th>
                            @endif
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($orders as $key=>$order)
                            <tr class="status-{{$order['order_status']}} class-all">
                                @php($addr = $order->is_guest ? json_decode((string)($order->delivery_address ?? '{}'), true) : (json_decode((string)($order->delivery_address ?? '{}'), true) ?: []))
                                @php($buyer = $order->is_guest ? (string) data_get($addr,'contact_person_name','') : trim((string)($order?->customer?->f_name ?? '').' '.(string)($order?->customer?->l_name ?? '')))
                                @php($city = (string) data_get($addr,'city','-'))
                                @php($qty = (int) collect($order->details ?? [])->sum('quantity'))
                                @php($cat = optional(optional(optional($order->details->first())->item)->category)->name ?? '—')
                                @php($ch = $order['order_type'] === 'take_away' ? 'Click & Collect' : ($order['order_type'] === 'pos' ? 'In-Store' : 'Online'))
                                @php($eta = !empty($order->schedule_at) ? date('M d', strtotime($order->schedule_at)) : '—')
                                @php($pay = $order->payment_status === 'paid' ? translate('Paid') : ($order->payment_status === 'partially_paid' ? translate('Partially paid') : translate('Unpaid')))
                                @php($st = (string) $order['order_status'])
                                @php($stCls = in_array($st,['delivered'],true) ? 'ok' : (in_array($st,['canceled','failed','refunded'],true) ? 'bad' : (in_array($st,['processing','pending','confirmed','accepted'],true) ? 'warn' : 'info')))

                                @if(!$ordersRmsUi)
                                    <td class="">
                                        {{$key+$orders->firstItem()}}
                                    </td>
                                    <td class="table-column-pl-0">
                                        <a href="{{route('vendor.order.details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                    </td>
                                    <td>
                                        <div>
                                            {{date('d M Y',strtotime($order['created_at']))}}
                                        </div>
                                        <div class="d-block text-uppercase">
                                            {{date(config('timeformat'),strtotime($order['created_at']))}}
                                        </div>
                                    </td>
                                    <td>
                                        @if($order->is_guest)
                                        @php($customer_details = json_decode($order['delivery_address'],true))
                                        <strong>{{$customer_details['contact_person_name']}}</strong>
                                        <div>{{$customer_details['contact_person_number']}}</div>
                                        @elseif($order->customer)

                                        <strong>
                                            {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                                        </strong>
                                        <div>
                                            {{$order->customer['phone']}}
                                        </div>
                                        @else
                                            <label
                                                class="badge badge-danger">{{translate('messages.invalid_customer_data')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-right mw--85px">
                                            <div>
                                                {{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}
                                            </div>
                                            @if($order->payment_status=='paid')
                                            <strong class="text-success">
                                                {{translate('messages.paid')}}
                                            </strong>
                                            @elseif($order->payment_status=='partially_paid')
                                            <strong class="text-success">
                                                {{translate('messages.partially_paid')}}
                                            </strong>
                                            @else
                                            <strong class="text-danger">
                                                {{translate('messages.unpaid')}}
                                            </strong>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-capitalize text-center">
                                        @if($order['order_status']=='pending')
                                            <span class="badge badge-soft-info">
                                            {{translate('messages.pending')}}
                                            </span>
                                        @elseif($order['order_status']=='confirmed')
                                            <span class="badge badge-soft-info">
                                            {{translate('messages.confirmed')}}
                                            </span>
                                        @elseif($order['order_status']=='processing')
                                            <span class="badge badge-soft-warning">
                                            {{translate('messages.processing')}}
                                            </span>
                                        @elseif($order['order_status']=='picked_up')
                                            <span class="badge badge-soft-warning">
                                            {{translate('messages.out_for_delivery')}}
                                            </span>
                                        @elseif($order['order_status']=='delivered')
                                            <span class="badge badge-soft-success">
                                            {{translate('messages.delivered')}}
                                            </span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-soft-danger">
                                            {{translate('messages.payment_failed')}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-danger">
                                            {{str_replace('_',' ',$order['order_status'])}}
                                            </span>
                                        @endif
                                        @if($order['order_type']=='take_away')
                                            <div class="text-info mt-1">
                                                {{translate('messages.take_away')}}
                                            </div>
                                        @else
                                            <div class="text-title mt-1">
                                            {{translate('messages.home Delivery')}}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="{{route('vendor.order.details',['id'=>$order['id']])}}"><i class="tio-visible-outlined"></i></a>
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank" href="{{route('vendor.order.generate-invoice',[$order['id']])}}"><i class="tio-print"></i></a>
                                        </div>
                                    </td>
                                @else
                                    <td class="table-column-pl-0">
                                        <a href="{{route('vendor.order.details',['id'=>$order['id']])}}" style="font-weight:900;color:var(--primary-clr,#107980);text-decoration:none">PO-{{ $order['id'] }}</a>
                                    </td>
                                    <td style="font-weight:700">{{ $buyer !== '' ? $buyer : translate('Customer') }}</td>
                                    <td>{{ $city !== '' ? $city : '—' }}</td>
                                    <td><span class="mf-pill info">{{ translate($ch) }}</span></td>
                                    <td>{{ $cat }}</td>
                                    <td>{{ $qty }}</td>
                                    <td style="font-weight:900">{{\App\CentralLogics\Helpers::format_currency($order['order_amount'])}}</td>
                                    <td><span class="mf-pill {{ $order->payment_status === 'paid' ? 'ok' : ($order->payment_status === 'partially_paid' ? 'warn' : 'bad') }}">{{ $pay }}</span></td>
                                    <td><span class="mf-pill {{ $stCls }}">{{ translate(ucwords(str_replace('_',' ',$st))) }}</span></td>
                                    <td>{{ $eta }}</td>
                                    <td class="text-center">
                                        <div class="mf-actions">
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="{{route('vendor.order.details',['id'=>$order['id']])}}">{{ translate('View') }}</a>
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn" target="_blank" href="{{route('vendor.order.generate-invoice',[$order['id']])}}">{{ translate('Invoice') }}</a>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($orders) === 0)
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
            <!-- Footer -->
            <div class="card-footer">
                {!! $orders->links() !!}
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->

        @if($ordersRmsUi)
            </div>
        @endif

@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function () {

            let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none',
                        action: function ()
                        {
                            window.location.href = '{{route("vendor.order.export",['status'=>$status,'file_type'=>'excel','type'=>'order', request()->getQueryString()])}}';
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'd-none',
                        action: function ()
                        {
                            window.location.href = '{{route("vendor.order.export",['status'=>$status,'file_type'=>'csv','type'=>'order', request()->getQueryString()])}}';
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'd-none'
                    },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="w-7rem mb-3" src="{{asset('assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description">' +

                        '</div>'
                }
            });

            $('#export-copy').click(function () {
                datatable.button('.buttons-copy').trigger()
            });

            $('#export-excel').click(function () {
                datatable.button('.buttons-excel').trigger()
            });

            $('#export-csv').click(function () {
                datatable.button('.buttons-csv').trigger()
            });

            $('#export-pdf').click(function () {
                datatable.button('.buttons-pdf').trigger()
            });

            $('#export-print').click(function () {
                datatable.button('.buttons-print').trigger()
            });

            $('#toggleColumn_order').change(function (e) {
                datatable.columns(1).visible(e.target.checked)
            })

            $('#toggleColumn_date').change(function (e) {
                datatable.columns(2).visible(e.target.checked)
            })

            $('#toggleColumn_customer').change(function (e) {
                datatable.columns(3).visible(e.target.checked)
            })

            $('#toggleColumn_payment_status').change(function (e) {
                datatable.columns(4).visible(e.target.checked)
            })

            $('#toggleColumn_order_status').change(function (e) {
                datatable.columns(5).visible(e.target.checked)
            })

            $('#toggleColumn_actions').change(function (e) {
                datatable.columns(6).visible(e.target.checked)
            })

        });
    </script>

@endpush
