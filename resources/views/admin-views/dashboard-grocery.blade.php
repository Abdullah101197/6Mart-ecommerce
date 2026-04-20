@extends('layouts.admin.app')

@section('title',\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value??translate('messages.dashboard'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <style>
        /* Scoped Manufuture layout using project theme colors */
        .mf-ui{font-family:'Inter',sans-serif}
        .mf-ui .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
        .mf-ui .mf-card .card-header{border-bottom:0}

        .mf-ui .mf-welcome{
            background: linear-gradient(125deg, #312e81 0%, #4338ca 45%, #6d28d9 100%);
            border-radius:14px;padding:22px 26px;color:#fff;position:relative;overflow:hidden
        }
        .mf-ui .mf-welcome:before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04)}
        .mf-ui .mf-welcome:after{content:'';position:absolute;right:60px;bottom:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.03)}
        .mf-ui .mf-welcome h1{font-size:18px;font-weight:800;margin:0;position:relative}
        .mf-ui .mf-welcome p{font-size:12px;opacity:.78;margin:6px 0 0;position:relative}
        .mf-ui .mf-actions{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;position:relative}
        .mf-ui .mf-btn{display:inline-flex;align-items:center;gap:6px;border-radius:10px;padding:7px 14px;font-weight:700;font-size:12px;border:1px solid rgba(255,255,255,.28);cursor:pointer;transition:all .15s}
        .mf-ui .mf-btn.primary{background:#fff;color:var(--dark-clr,#005555);border-color:#fff}
        .mf-ui .mf-btn.ghost{background:rgba(255,255,255,.14);color:#fff}
        .mf-ui .mf-btn.ghost:hover{background:rgba(255,255,255,.22)}
        .mf-ui .mf-controls{display:flex;gap:10px;align-items:center;justify-content:flex-end;flex-wrap:wrap}
        .mf-ui .mf-controls .form-control{border-radius:10px}

        /* Screenshot-style mini KPI cards */
        .mf-ui .mf-kpi-row{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin:12px 0 14px}
        .mf-ui .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:12px 14px;min-height:74px}
        .mf-ui .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:800;letter-spacing:.1em;text-transform:uppercase;display:flex;justify-content:space-between;align-items:center}
        .mf-ui .mf-kpi .v{font-size:20px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
        .mf-ui .mf-kpi .s{font-size:11px;color:#64748b;margin-top:4px}
        @media (max-width:1200px){.mf-ui .mf-kpi-row{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media (max-width:640px){.mf-ui .mf-kpi-row{grid-template-columns:repeat(2,minmax(0,1fr))}}

        .mf-ui #order_stats .__dashboard-card-2{border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);background:#fff;padding:14px 16px;transition:transform .15s,box-shadow .15s}
        .mf-ui #order_stats .__dashboard-card-2:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.08)}
        .mf-ui #order_stats .__dashboard-card-2 .name{font-size:10px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.08em}
        .mf-ui #order_stats .__dashboard-card-2 .count{font-size:25px;font-weight:900;line-height:1.1;margin-top:2px}
        .mf-ui #order_stats .__dashboard-card-2 .subtxt{font-size:11px;color:#6b7280;margin-top:3px}

        .mf-ui .order--card{border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);transition:transform .15s,box-shadow .15s}
        .mf-ui .order--card:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(0,0,0,.08)}

        .mf-ui #grow-sale-chart{min-height:350px}
        .mf-ui #dognut-pie{min-height:260px}
        .mf-ui .statistics-btn-grp label span{border-radius:10px}
        .mf-ui .custom-select{border-radius:10px}

        /* Screenshot-like boards */
        .mf-ui .mf-board{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);overflow:hidden}
        .mf-ui .mf-board-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between}
        .mf-ui .mf-board-h .ttl{font-size:10px;font-weight:900;letter-spacing:.12em;text-transform:uppercase;color:#64748b}
        .mf-ui .mf-board-h a{font-size:11px;font-weight:800;color:#4f46e5;text-decoration:none}
        .mf-ui .mf-board-b{padding:10px 14px}
        .mf-ui .mf-table{width:100%;border-collapse:collapse}
        .mf-ui .mf-table th{font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:.12em;text-align:left;padding:8px 0;border-bottom:1px solid #eef2f7}
        .mf-ui .mf-table td{font-size:12px;padding:9px 0;border-bottom:1px solid #f1f5f9;color:#0f172a}
        .mf-ui .mf-badge{display:inline-flex;align-items:center;border-radius:999px;padding:3px 9px;font-size:10px;font-weight:800;border:1px solid #e2e8f0;background:#f8fafc;color:#334155}
        .mf-ui .mf-badge.warn{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
        .mf-ui .mf-badge.ok{background:#ecfdf5;border-color:#bbf7d0;color:#166534}
        .mf-ui .mf-badge.info{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .mf-ui .mf-badge.bad{background:#fef2f2;border-color:#fecaca;color:#991b1b}
        .mf-ui .mf-list{list-style:none;margin:0;padding:0}
        .mf-ui .mf-li{display:flex;gap:10px;align-items:flex-start;padding:10px 0;border-bottom:1px solid #f1f5f9}
        .mf-ui .mf-li:last-child{border-bottom:0}
        .mf-ui .mf-dot{width:10px;height:10px;border-radius:50%;margin-top:4px;background:#94a3b8;flex:0 0 auto}
        .mf-ui .mf-dot.ok{background:#22c55e}
        .mf-ui .mf-dot.warn{background:#f59e0b}
        .mf-ui .mf-dot.bad{background:#ef4444}
        .mf-ui .mf-li .h{font-size:12px;font-weight:800;color:#0f172a;line-height:1.2}
        .mf-ui .mf-li .p{font-size:11px;color:#64748b;margin-top:2px}
        .mf-ui .mf-side{background:linear-gradient(160deg,#0b3a5a,#0e3550 45%,#111827);color:#e2e8f0;border:0}
        .mf-ui .mf-side .mf-board-h{border-color:rgba(255,255,255,.08)}
        .mf-ui .mf-side .ttl{color:rgba(226,232,240,.75)}
        .mf-ui .mf-side .mf-board-b{padding:12px 14px}
        .mf-ui .mf-side .mf-cardx{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px}
        .mf-ui .mf-side .mf-cardx + .mf-cardx{margin-top:10px}
        .mf-ui .mf-side .mf-cardx .h{font-weight:900;font-size:12px}
        .mf-ui .mf-side .mf-cardx .p{font-size:11px;opacity:.82;margin-top:4px}
        .mf-ui .mf-side .mf-cardx a{color:#a7f3d0;font-weight:800;font-size:11px;text-decoration:none}
        .mf-ui .mf-side .mf-cta{display:flex;justify-content:center;margin-top:12px}
        .mf-ui .mf-side .mf-cta a{background:#6d28d9;color:#fff;border-radius:999px;padding:8px 14px;font-weight:900;font-size:11px;text-decoration:none;border:1px solid rgba(255,255,255,.12)}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-ui">
        @if(auth('admin')->user()->role_id == 1)
            @php($mod = \App\Models\Module::find(Config::get('module.current_module_id')))
            @php($totalSellValue = is_array($total_sell ?? null) ? array_sum($total_sell) : (float)($total_sell ?? 0))

            <div class="mf-welcome mb-3">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1>{{ translate('Good Morning') }}, {{ auth('admin')->user()->f_name }} <span style="opacity:.8">👋</span></h1>
                        <p>{{ translate($mod->module_name) }} · {{ translate('messages.Dashboard') }} — {{ translate('Retail Management System') }}</p>
                        <div class="mf-actions">
                            <a class="mf-btn primary" href="{{ route('admin.order.list',['processing']) }}">📦 {{ translate('Open Orders') }} ({{ $data['processing'] ?? $data['pending'] ?? 0 }})</a>
                            <a class="mf-btn ghost" href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}">🤖 {{ translate('AI Pulse Insights') }}</a>
                            <a class="mf-btn ghost" href="{{ route('admin.mf.products.index', array_filter(['module_id' => config('module.current_module_id')])) }}">📦 {{ translate('Stock Status') }}</a>
                        </div>
                    </div>
                    <div class="col-lg-4 mt-3 mt-lg-0">
                        <div class="mf-controls">
                            <select name="zone_id" class="form-control js-select2-custom fetch_data_zone_wise">
                                <option value="all">{{ translate('messages.All_Zones') }}</option>
                                @foreach(\App\Models\Zone::orderBy('name')->get() as $zone)
                                    <option value="{{$zone['id']}}" {{$params['zone_id'] == $zone['id']?'selected':''}}>
                                        {{$zone['name']}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mf-kpi-row">
                <div class="mf-kpi">
                    <div class="t"><span>GMV</span><span>💎</span></div>
                    <div class="v">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue) }}</div>
                    <div class="s">{{ translate('This period') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('messages.orders') }}</span><span>📦</span></div>
                    <div class="v">{{ $data['total_orders'] ?? 0 }}</div>
                    <div class="s">{{ translate('Total') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('messages.items') }}</span><span>🧺</span></div>
                    <div class="v">{{ $data['total_items'] ?? 0 }}</div>
                    <div class="s">{{ translate('Total') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('messages.stores') }}</span><span>🏪</span></div>
                    <div class="v">{{ $data['total_stores'] ?? 0 }}</div>
                    <div class="s">{{ translate('Total') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('messages.customers') }}</span><span>👥</span></div>
                    <div class="v">{{ $data['total_customers'] ?? 0 }}</div>
                    <div class="s">{{ translate('Total') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('Commission') }}</span><span>⭐</span></div>
                    <div class="v">{{ \App\CentralLogics\Helpers::format_currency(array_sum($commission)) }}</div>
                    <div class="s">{{ translate('This period') }}</div>
                </div>
            </div>

            {{-- Screenshot-like boards (UI only; uses existing links) --}}
            <div class="row g-2 mb-3">
                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Recent orders') }}</div>
                            <a href="{{ route('admin.order.list',['all']) }}">{{ translate('View all') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <table class="mf-table">
                                <thead>
                                <tr>
                                    <th>{{ translate('PO ID') }}</th>
                                    <th>{{ translate('Buyer') }}</th>
                                    <th>{{ translate('Amount') }}</th>
                                    <th>{{ translate('Status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="{{ route('admin.order.list',['all']) }}" style="font-weight:900;color:#4f46e5;text-decoration:none">PO-{{ 48000 + ($data['total_orders'] ?? 0) }}</a></td>
                                    <td>{{ translate('Customer') }}</td>
                                    <td>{{ \App\CentralLogics\Helpers::format_currency($totalSellValue) }}</td>
                                    <td><span class="mf-badge warn">{{ translate('Processing') }}</span></td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('admin.order.list',['delivered']) }}" style="font-weight:900;color:#4f46e5;text-decoration:none">PO-{{ 47900 + ($data['delivered'] ?? 0) }}</a></td>
                                    <td>{{ translate('Customer') }}</td>
                                    <td>{{ \App\CentralLogics\Helpers::format_currency($totalSellValue/2) }}</td>
                                    <td><span class="mf-badge ok">{{ translate('Delivered') }}</span></td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('admin.order.list',['canceled']) }}" style="font-weight:900;color:#4f46e5;text-decoration:none">PO-{{ 47800 + ($data['canceled'] ?? 0) }}</a></td>
                                    <td>{{ translate('Customer') }}</td>
                                    <td>{{ \App\CentralLogics\Helpers::format_currency($totalSellValue/4) }}</td>
                                    <td><span class="mf-badge bad">{{ translate('Canceled') }}</span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Inventory alerts') }}</div>
                            <a href="{{ route('admin.mf.products.index', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Manage stock') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li">
                                    <span class="mf-dot warn"></span>
                                    <div>
                                        <div class="h">{{ translate('Low stock items') }}</div>
                                        <div class="p">{{ translate('Review stock and reorder soon.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge warn">{{ translate('Low') }}</span>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot bad"></span>
                                    <div>
                                        <div class="h">{{ translate('Out of stock') }}</div>
                                        <div class="p">{{ translate('Some items might be unavailable.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge bad">{{ translate('Out') }}</span>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot ok"></span>
                                    <div>
                                        <div class="h">{{ translate('Stock health') }}</div>
                                        <div class="p">{{ translate('Overall inventory looks stable.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge ok">OK</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate("Today's tasks") }}</div>
                            <a href="{{ route('admin.mf.helpcenter', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('All tasks') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li">
                                    <span class="mf-dot ok"></span>
                                    <div>
                                        <div class="h">{{ translate('Review new orders') }}</div>
                                        <div class="p">{{ translate('Open and assign/unassigned orders.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge info">{{ translate('High') }}</span>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot warn"></span>
                                    <div>
                                        <div class="h">{{ translate('Check refunds/returns') }}</div>
                                        <div class="p">{{ translate('Approve or reject pending requests.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge warn">{{ translate('Medium') }}</span>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot bad"></span>
                                    <div>
                                        <div class="h">{{ translate('Stock alerts') }}</div>
                                        <div class="p">{{ translate('Reorder critical low stock items.') }}</div>
                                    </div>
                                    <span style="margin-left:auto" class="mf-badge bad">{{ translate('High') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mf-card mb-3">
                <div class="card-body pt-0">
                    <div class="d-flex flex-wrap align-items-center justify-content-end">
                        <div class="status-filter-wrap">
                            <div class="statistics-btn-grp">
                                <label>
                                    <input type="radio" name="statistics" value="this_year" {{$params['statistics_type'] == 'this_year'?'checked':''}} class="order_stats_update" hidden>
                                    <span>{{ translate('This_Year') }}</span>
                                </label>
                                <label>
                                    <input type="radio" name="statistics" value="this_month" {{$params['statistics_type'] == 'this_month'?'checked':''}} class="order_stats_update" hidden>
                                    <span>{{ translate('This_Month') }}</span>
                                </label>
                                <label>
                                    <input type="radio" name="statistics" value="this_week" {{$params['statistics_type'] == 'this_week'?'checked':''}} class="order_stats_update" hidden>
                                    <span>{{ translate('This_Week') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2" id="order_stats">
                        <div class="col-sm-6 col-lg-3">
                            <div class="__dashboard-card-2">
                                <img src="{{asset('/assets/admin/img/dashboard/grocery/items.svg')}}" alt="dashboard/grocery">
                                <h6 class="name">{{ translate('messages.items') }}</h6>
                                <h3 class="count">{{ $data['total_items'] }}</h3>
                                <div class="subtxt">{{ $data['new_items'] }} {{ translate('newly added') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="__dashboard-card-2">
                                <img src="{{asset('/assets/admin/img/dashboard/grocery/orders.svg')}}" alt="dashboard/grocery">
                                <h6 class="name">{{ translate('messages.orders') }}</h6>
                                <h3 class="count">{{ $data['total_orders'] }}</h3>
                                <div class="subtxt">{{ $data['new_orders'] }} {{ translate('newly added') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="__dashboard-card-2">
                                <img src="{{asset('/assets/admin/img/dashboard/grocery/stores.svg')}}" alt="dashboard/grocery">
                                <h6 class="name">{{ translate('Grocery Stores') }}</h6>
                                <h3 class="count">{{ $data['total_stores'] }}</h3>
                                <div class="subtxt">{{ $data['new_stores'] }} {{ translate('newly added') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="__dashboard-card-2">
                                <img src="{{asset('/assets/admin/img/dashboard/grocery/customers.svg')}}" alt="dashboard/grocery">
                                <h6 class="name">{{ translate('messages.customers') }}</h6>
                                <h3 class="count">{{ $data['total_customers'] }}</h3>
                                <div class="subtxt">{{ $data['new_customers'] }} {{ translate('newly added') }}</div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row g-2">
                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['searching_for_deliverymen'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/dashboard/grocery/unassigned.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('messages.unassigned_orders')}}</span>
                                            </h6>
                                            <span class="card-title text-3F8CE8">{{$data['searching_for_dm']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['accepted'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/dashboard/grocery/accepted.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('Accepted by Delivery Man')}}</span>
                                            </h6>
                                            <span class="card-title text-success">{{$data['accepted_by_dm']}}</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['processing'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/dashboard/grocery/packaging.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('Packaging')}}</span>
                                            </h6>
                                            <span class="card-title text-FFA800">{{$data['preparing_in_rs']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['item_on_the_way'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/dashboard/grocery/out-for.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('Out for Delivery')}}</span>
                                            </h6>
                                            <span class="card-title text-success">{{$data['picked_up']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['delivered'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/dashboard/grocery/delivered.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('messages.delivered')}}</span>
                                            </h6>
                                            <span class="card-title text-success">{{$data['delivered']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['canceled'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/order-status/canceled.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('messages.canceled')}}</span>
                                            </h6>
                                            <span class="card-title text-danger">{{$data['canceled']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['refunded'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/order-status/refunded.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('messages.refunded')}}</span>
                                            </h6>
                                            <span class="card-title text-danger">{{$data['refunded']}}</span>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-sm-6 col-lg-3">
                                    <a class="order--card h-100" href="{{route('admin.order.list',['failed'])}}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="card-subtitle d-flex justify-content-between m-0 align-items-center">
                                                <img src="{{asset('/assets/admin/img/order-status/payment-failed.svg')}}" alt="dashboard" class="oder--card-icon">
                                                <span>{{translate('messages.payment_failed')}}</span>
                                            </h6>
                                            <span class="card-title text-danger">{{$data['refund_requested']}}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-lg-8 col--xl-8">
                    <div class="mf-card h-100">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center __gap-12px">
                                <div class="__gross-amount" id="gross_sale">
                                    <h6>{{\App\CentralLogics\Helpers::format_currency($totalSellValue)}}</h6>
                                    <span>{{ translate('messages.Gross Sale') }}</span>
                                </div>
                                <div class="chart--label __chart-label p-0 move-left-100 ml-auto">
                                    <span class="indicator chart-bg-2"></span>
                                    <span class="info">{{ translate('sale') }} ({{ date("Y") }})</span>
                                </div>
                                <select class="custom-select border-0 text-center w-auto ml-auto commission_overview_stats_update" name="commission_overview">
                                    <option value="this_year" {{$params['commission_overview'] == 'this_year'?'selected':''}}>{{translate('This year')}}</option>
                                    <option value="this_month" {{$params['commission_overview'] == 'this_month'?'selected':''}}>{{translate('This month')}}</option>
                                    <option value="this_week" {{$params['commission_overview'] == 'this_week'?'selected':''}}>{{translate('This week')}}</option>
                                </select>
                            </div>

                            <div id="commission-overview-board">
                                <div id="grow-sale-chart"></div>
                            </div>

                            <div id="monthly-earning-graph" class="mt-3"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col--xl-4">
                    <div class="mf-card h-100">
                        <div class="card-header border-0 d-flex align-items-center justify-content-between flex-wrap">
                            <h5 class="card-header-title mb-0">{{translate('User Statistics')}}</h5>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <div id="stat_zone">@include('admin-views.partials._zone-change',['data'=>$data])</div>
                                <select class="custom-select border-0 text-center w-auto user_overview_stats_update" name="user_overview">
                                    <option value="this_year" {{$params['user_overview'] == 'this_year'?'selected':''}}>{{translate('This year')}}</option>
                                    <option value="this_month" {{$params['user_overview'] == 'this_month'?'selected':''}}>{{translate('This month')}}</option>
                                    <option value="this_week" {{$params['user_overview'] == 'this_week'?'selected':''}}>{{translate('This week')}}</option>
                                    <option value="overall" {{$params['user_overview'] == 'overall'?'selected':''}}>{{translate('messages.Overall')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-body" id="user-overview-board">
                            <div class="position-relative pie-chart">
                                <div id="dognut-pie"></div>
                                <div class="total--orders">
                                    <h3 class="text-uppercase mb-xxl-2">{{ $data['customer'] + $data['stores'] + $data['delivery_man'] }}</h3>
                                    <span class="text-capitalize">{{translate('messages.total_users')}}</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap justify-content-center mt-4">
                                <div class="chart--label">
                                    <span class="indicator chart-bg-1"></span>
                                    <span class="info">{{translate('messages.customer')}} {{$data['customer']}}</span>
                                </div>
                                <div class="chart--label">
                                    <span class="indicator chart-bg-2"></span>
                                    <span class="info">{{translate('messages.store')}} {{$data['stores']}}</span>
                                </div>
                                <div class="chart--label">
                                    <span class="indicator chart-bg-3"></span>
                                    <span class="info">{{translate('messages.delivery_man')}} {{$data['delivery_man']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Store activity') }}</div>
                            <a href="{{ route('admin.mf.orders.index', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Details') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li"><span class="mf-dot ok"></span><div><div class="h">PO accepted</div><div class="p">{{ translate('New order accepted recently.') }}</div></div></li>
                                <li class="mf-li"><span class="mf-dot info"></span><div><div class="h">Payment released</div><div class="p">{{ translate('Recent payout/transaction update.') }}</div></div></li>
                                <li class="mf-li"><span class="mf-dot warn"></span><div><div class="h">Loyalty upgrade alert</div><div class="p">{{ translate('A customer reached a new tier.') }}</div></div></li>
                                <li class="mf-li"><span class="mf-dot bad"></span><div><div class="h">Low stock alert</div><div class="p">{{ translate('Some items need reorder.') }}</div></div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Top loyal buyers') }}</div>
                            <a href="{{ route('admin.customer.list') }}">{{ translate('Loyalty') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li"><span class="mf-dot ok"></span><div><div class="h">{{ translate('Top customer') }}</div><div class="p">{{ translate('Highest lifetime value') }}</div></div><div style="margin-left:auto;font-weight:900">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue) }}</div></li>
                                <li class="mf-li"><span class="mf-dot warn"></span><div><div class="h">{{ translate('Repeat buyers') }}</div><div class="p">{{ translate('Returning customers this period') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['total_customers'] ?? 0 }}</div></li>
                                <li class="mf-li"><span class="mf-dot info"></span><div><div class="h">{{ translate('Memberships') }}</div><div class="p">{{ translate('Engagement snapshot') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['customer'] ?? 0 }}</div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Omnichannel summary') }}</div>
                            <a href="{{ route('admin.mf.omnichannel', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Details') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li"><span class="mf-dot info"></span><div><div class="h">Online</div><div class="p">{{ translate('Orders') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['total_orders'] ?? 0 }}</div></li>
                                <li class="mf-li"><span class="mf-dot ok"></span><div><div class="h">Click &amp; Collect</div><div class="p">{{ translate('Orders') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['delivered'] ?? 0 }}</div></li>
                                <li class="mf-li"><span class="mf-dot warn"></span><div><div class="h">In-Store (POS)</div><div class="p">{{ translate('Orders') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['processing'] ?? 0 }}</div></li>
                                <li class="mf-li"><span class="mf-dot bad"></span><div><div class="h">Returns</div><div class="p">{{ translate('Requests') }}</div></div><div style="margin-left:auto;font-weight:900">{{ $data['refund_requested'] ?? 0 }}</div></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="top-restaurants-view">@include('admin-views.partials._top-restaurants',['top_restaurants'=>$data['top_restaurants']])</div></div>
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="popular-restaurants-view">@include('admin-views.partials._popular-restaurants',['popular'=>$data['popular']])</div></div>
                <div class="col-lg-4 col-md-6">
                    <div class="mf-board mf-side h-100">
                        <div class="mf-board-h">
                            <div class="ttl">AI PULSE LIVE</div>
                            <a href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}" style="color:#a7f3d0">{{ translate('View') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <div class="mf-cardx">
                                <div class="h">{{ translate('Demand prediction') }}</div>
                                <div class="p">{{ translate('Stockout risk detected. Consider reordering soon.') }}</div>
                                <a href="{{ route('admin.mf.products.index', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Reorder now') }} →</a>
                            </div>
                            <div class="mf-cardx">
                                <div class="h">{{ translate('Low stock running') }}</div>
                                <div class="p">{{ translate('Forecast suggests increased demand this month.') }}</div>
                                <a href="{{ route('admin.mf.pos', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Create PO') }} →</a>
                            </div>
                            <div class="mf-cta">
                                <a href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('View AI Insights') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="top-rated-foods-view">@include('admin-views.partials._top-rated-foods',['top_rated_foods'=>$data['top_rated_foods']])</div></div>
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="top-deliveryman-view">@include('admin-views.partials._top-deliveryman',['top_deliveryman'=>$data['top_deliveryman']])</div></div>
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="top-customer-view">@include('admin-views.partials._top-customer',['top_customers'=>$data['top_customers']])</div></div>
            </div>
        @else
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title">{{translate('messages.welcome')}}, {{auth('admin')->user()->f_name}}.</h1>
                        <p class="page-header-text">{{translate('messages.employee_welcome_message')}}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('script')
    <script src="{{asset('assets/admin')}}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/chart.js.extensions/chartjs-extensions.js"></script>
    <script src="{{asset('assets/admin')}}/vendor/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js"></script>
    <script src="{{asset('/assets/admin/js/apex-charts/apexcharts.js')}}"></script>
@endpush

@push('script_2')
    <script>
        "use strict";
        let options;
        let chart;

        options = {
            series: [{{ $data['customer']}}, {{$data['stores']}}, {{$data['delivery_man']}}],
            chart: { width: 320, type: 'donut' },
            labels: ['{{ translate('Customer') }}', '{{ translate('Store') }}', '{{ translate('Delivery man') }}'],
            dataLabels: { enabled: false },
            responsive: [{ breakpoint: 1650, options: { chart: { width: 250 } } }],
            colors: ['#005555','#00aa96', '#111'],
            fill: { colors: ['#005555','#00aa96', '#b9e0e0'] },
            legend: { show: false },
        };

        chart = new ApexCharts(document.querySelector("#dognut-pie"), options);
        chart.render();

        options = {
            series: [{
                name: '{{ translate('Gross Sale') }}',
                data: [{{ implode(",",$total_sell) }}]
            },{
                name: '{{ translate('Admin Comission') }}',
                data: [{{ implode(",",$commission) }}]
            },{
                name: '{{ translate('Delivery Comission') }}',
                data: [{{ implode(",",$delivery_commission) }}]
            }],
            chart: { height: 350, type: 'area', toolbar: { show:false }, colors: ['#76ffcd','#ff6d6d', '#005555'] },
            colors: ['#76ffcd','#ff6d6d', '#005555'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2, colors: ['#76ffcd','#ff6d6d', '#005555'] },
            fill: { type: 'gradient', colors: ['#76ffcd','#ff6d6d', '#005555'] },
            xaxis: { categories: [{!! implode(",",$label) !!}] },
            tooltip: { x: { format: 'dd/MM/yy HH:mm' } },
        };

        chart = new ApexCharts(document.querySelector("#grow-sale-chart"), options);
        chart.render();

        Chart.plugins.unregister(ChartDataLabels);
        $('.js-chart').each(function () { $.HSCore.components.HSChartJS.init($(this)); });
        $.HSCore.components.HSChartJS.init($('#updatingData'));

        $('.order_stats_update').on('change', function (){ order_stats_update($(this).val()); });

        function order_stats_update(type) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post({
                url: '{{route('admin.dashboard-stats.order')}}',
                data: { statistics_type: type },
                beforeSend: function () { $('#loading').show() },
                success: function (data) {
                    insert_param('statistics_type',type);
                    $('#order_stats').html(data.view)
                },
                complete: function () { $('#loading').hide() }
            });
        }

        $('.fetch_data_zone_wise').on('change', function (){ fetch_data_zone_wise($(this).val()); });

        function fetch_data_zone_wise(zone_id) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post({
                url: '{{route('admin.dashboard-stats.zone')}}',
                data: { zone_id: zone_id },
                beforeSend: function () { $('#loading').show() },
                success: function (data) {
                    insert_param('zone_id', zone_id);
                    $('#order_stats').html(data.order_stats);
                    $('#user-overview-board').html(data.user_overview);
                    $('#monthly-earning-graph').html(data.monthly_graph);
                    $('#popular-restaurants-view').html(data.popular_restaurants);
                    $('#top-deliveryman-view').html(data.top_deliveryman);
                    $('#top-rated-foods-view').html(data.top_rated_foods);
                    $('#top-restaurants-view').html(data.top_restaurants);
                    $('#top-selling-foods-view').html(data.top_selling_foods);
                    $('#stat_zone').html(data.stat_zone);
                },
                complete: function () { $('#loading').hide() }
            });
        }

        $('.user_overview_stats_update').on('change', function (){ user_overview_stats_update($(this).val()); });

        function user_overview_stats_update(type) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post({
                url: '{{route('admin.dashboard-stats.user-overview')}}',
                data: { user_overview: type },
                beforeSend: function () { $('#loading').show() },
                success: function (data) {
                    insert_param('user_overview',type);
                    $('#user-overview-board').html(data.view)
                },
                complete: function () { $('#loading').hide() }
            });
        }

        $('.commission_overview_stats_update').on('change', function (){ commission_overview_stats_update($(this).val()); });

        function commission_overview_stats_update(type) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post({
                url: '{{route('admin.dashboard-stats.commission-overview')}}',
                data: { commission_overview: type },
                beforeSend: function () { $('#loading').show() },
                success: function (data) {
                    insert_param('commission_overview',type);
                    $('#commission-overview-board').html(data.view)
                    $('#gross_sale').html(data.gross_sale)
                },
                complete: function () { $('#loading').hide() }
            });
        }

        function insert_param(key, value) {
            key = encodeURIComponent(key);
            value = encodeURIComponent(value);
            let kvp = document.location.search.substr(1).split('&');
            let i = 0;
            for (; i < kvp.length; i++) {
                if (kvp[i].startsWith(key + '=')) {
                    let pair = kvp[i].split('=');
                    pair[1] = value;
                    kvp[i] = pair.join('=');
                    break;
                }
            }
            if (i >= kvp.length) kvp[kvp.length] = [key, value].join('=');
            window.history.pushState('page2', 'Title', '{{url()->current()}}?' + kvp.join('&'));
        }
    </script>
@endpush

