@extends('layouts.admin.manufuture')

@section('title',\App\Models\BusinessSetting::where(['key'=>'business_name'])->first()->value??translate('messages.dashboard'))
@section('page_title', translate('messages.dashboard'))

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

        /* Screenshot-style KPI cards */
        .mf-ui .mf-kpi-row{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:12px;margin:12px 0 14px}
        .mf-ui .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:12px 14px;min-height:74px}
        .mf-ui .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.12em;text-transform:uppercase;display:flex;justify-content:space-between;align-items:center}
        .mf-ui .mf-kpi .ic{width:26px;height:26px;border-radius:10px;display:inline-flex;align-items:center;justify-content:center;background:#f1f5ff;color:#4f46e5;font-size:13px}
        .mf-ui .mf-kpi .v{font-size:22px;font-weight:900;margin-top:6px;line-height:1.05;color:#0f172a}
        .mf-ui .mf-kpi .s{font-size:11px;color:#64748b;margin-top:4px;display:flex;gap:6px;align-items:center}
        .mf-ui .mf-kpi .up{color:#16a34a;font-weight:900}
        .mf-ui .mf-kpi .down{color:#dc2626;font-weight:900}
        @media (max-width:1200px){.mf-ui .mf-kpi-row{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media (max-width:640px){.mf-ui .mf-kpi-row{grid-template-columns:repeat(2,minmax(0,1fr))}}

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
    </style>
@endpush

@section('content')
    <div class="mf-ui">
        @if(auth('admin')->user()->role_id == 1)
            @php($mod = \App\Models\Module::find(Config::get('module.current_module_id')))
            @php($totalSellValue = is_array($total_sell ?? null) ? array_sum($total_sell) : (float)($total_sell ?? 0))
            @php($gmvShort = \App\CentralLogics\Helpers::number_format_short($totalSellValue))
            @php($totalSellSeries = is_array($total_sell ?? null) ? array_values($total_sell) : [(float)($total_sell ?? 0)])
            @php($commissionSeries = is_array($commission ?? null) ? array_values($commission) : [(float)($commission ?? 0)])
            @php($deliveryCommissionSeries = is_array($delivery_commission ?? null) ? array_values($delivery_commission) : [(float)($delivery_commission ?? 0)])
            @php($labelSeries = is_array($label ?? null) ? array_values($label) : [("'" . date('M') . "'")])

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
                    <div class="t"><span>GMV ({{ date('M') }})</span><span class="ic">💎</span></div>
                    <div class="v">{{ $gmvShort }}</div>
                    <div class="s"><span class="up">↑</span> <span>{{ translate('MoM') }}</span></div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('Orders') }}</span><span class="ic">📦</span></div>
                    <div class="v">{{ $data['total_orders'] ?? 0 }}</div>
                    <div class="s">{{ translate('Need action') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('Stock health') }}</span><span class="ic">📊</span></div>
                    <div class="v">{{ 87 }}%</div>
                    <div class="s">{{ translate('Low stock items') }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('Loyalty members') }}</span><span class="ic">⭐</span></div>
                    <div class="v">{{ $data['total_customers'] ?? 0 }}</div>
                    <div class="s"><span class="up">↑</span> <span>{{ translate('This week') }}</span></div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('Tasks due today') }}</span><span class="ic">✅</span></div>
                    <div class="v">{{ 5 }}</div>
                    <div class="s"><span class="down">•</span> <span>{{ translate('Overdue') }}</span></div>
                </div>
                <div class="mf-kpi">
                    <div class="t"><span>{{ translate('NPS score') }}</span><span class="ic">😊</span></div>
                    <div class="v">{{ 72 }}</div>
                    <div class="s">{{ translate('Excellent') }}</div>
                </div>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-lg-6">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Revenue trend + channels') }}</div>
                            <a href="{{ route('admin.mf.omnichannel', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Retail intelligence') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <div id="grow-sale-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Channel mix') }}</div>
                            <span></span>
                        </div>
                        <div class="mf-board-b">
                            <div id="dognut-pie"></div>
                            <div class="d-flex flex-wrap justify-content-center mt-2" style="gap:10px">
                                <span class="mf-badge info">{{ translate('Online') }}</span>
                                <span class="mf-badge ok">{{ translate('Click & Collect') }}</span>
                                <span class="mf-badge warn">{{ translate('In-store') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="mf-board mf-side h-100">
                        <div class="mf-board-h">
                            <div class="ttl">AI PULSE LIVE</div>
                            <a href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}" style="color:#a7f3d0">{{ translate('View') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <div class="mf-cardx">
                                <div class="h">{{ translate('Stockout risk') }}</div>
                                <div class="p">{{ translate('Demand predicted in next 14 days. Reorder immediately.') }}</div>
                                <a href="{{ route('admin.mf.products.index', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Reorder now') }} →</a>
                            </div>
                            <div class="mf-cardx">
                                <div class="h">{{ translate('SKU running low') }}</div>
                                <div class="p">{{ translate('Forecast suggests increased demand this month.') }}</div>
                                <a href="{{ route('admin.mf.pos', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Create PO') }} →</a>
                            </div>
                            <div class="mf-cta">
                                <a href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('View all insights') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Screenshot-like: Store activity / Loyalty buyers / Omnichannel summary --}}
            <div class="row g-2 mb-3">
                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Store activity') }}</div>
                            <span></span>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li">
                                    <span class="mf-dot ok"></span>
                                    <div>
                                        <div class="h">{{ translate('PO') }}-{{ 48000 + ($data['accepted'] ?? 0) }} {{ translate('accepted') }}</div>
                                        <div class="p">{{ translate('Just now') }}</div>
                                    </div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot info" style="background:#6366f1"></span>
                                    <div>
                                        <div class="h">{{ translate('Payment released') }}</div>
                                        <div class="p">{{ translate('5 mins ago') }}</div>
                                    </div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot warn"></span>
                                    <div>
                                        <div class="h">{{ translate('Loyalty upgrade alert') }}</div>
                                        <div class="p">{{ translate('1 day ago') }}</div>
                                    </div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot bad"></span>
                                    <div>
                                        <div class="h">{{ translate('Low stock alert') }}</div>
                                        <div class="p">{{ translate('1 day ago') }}</div>
                                    </div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot ok"></span>
                                    <div>
                                        <div class="h">{{ translate('AI replenishment suggestion') }}</div>
                                        <div class="p">{{ translate('2 days ago') }}</div>
                                    </div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot" style="background:#cbd5e1"></span>
                                    <div>
                                        <div class="h">{{ translate('Task completed') }}</div>
                                        <div class="p">{{ translate('2 days ago') }}</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Top loyal buyers') }}</div>
                            <a href="{{ route('admin.mf.helpcenter', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Loyalty program') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <table class="mf-table">
                                <tbody>
                                <tr>
                                    <td style="border-bottom:0">
                                        <div style="font-weight:900">Tata Steel</div>
                                        <div style="font-size:10px;color:#64748b;margin-top:2px">Platinum · 148,200 pts</div>
                                    </td>
                                    <td style="border-bottom:0;text-align:right;font-weight:900">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue * 0.22) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0">
                                        <div style="font-weight:900">Maruti Suzuki</div>
                                        <div style="font-size:10px;color:#64748b;margin-top:2px">Platinum · 210,000 pts</div>
                                    </td>
                                    <td style="border-bottom:0;text-align:right;font-weight:900">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue * 0.25) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0">
                                        <div style="font-weight:900">ONGC Petro</div>
                                        <div style="font-size:10px;color:#64748b;margin-top:2px">Gold · 95,000 pts</div>
                                    </td>
                                    <td style="border-bottom:0;text-align:right;font-weight:900">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue * 0.11) }}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0">
                                        <div style="font-weight:900">Bharat Forge</div>
                                        <div style="font-size:10px;color:#64748b;margin-top:2px">Gold · 62,500 pts</div>
                                    </td>
                                    <td style="border-bottom:0;text-align:right;font-weight:900">{{ \App\CentralLogics\Helpers::format_currency($totalSellValue * 0.08) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mf-board h-100">
                        <div class="mf-board-h">
                            <div class="ttl">{{ translate('Omnichannel summary') }}</div>
                            <a href="{{ route('admin.mf.omnichannel', array_filter(['module_id' => config('module.current_module_id')])) }}">{{ translate('Details') }} →</a>
                        </div>
                        <div class="mf-board-b">
                            <ul class="mf-list">
                                <li class="mf-li">
                                    <span class="mf-dot info" style="background:#3b82f6"></span>
                                    <div class="h">{{ translate('Online') }}</div>
                                    <div style="margin-left:auto;font-weight:900;color:#0f172a">{{ max(0, (int)($data['delivered'] ?? 0)) }} {{ translate('orders') }}</div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot ok"></span>
                                    <div class="h">{{ translate('Click & Collect') }}</div>
                                    <div style="margin-left:auto;font-weight:900;color:#16a34a">{{ max(0, (int)($data['processing'] ?? 0)) }} {{ translate('orders') }}</div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot warn"></span>
                                    <div class="h">{{ translate('In-store (POS)') }}</div>
                                    <div style="margin-left:auto;font-weight:900;color:#0f172a">{{ max(0, (int)($data['total_orders'] ?? 0) - (int)($data['delivered'] ?? 0)) }} {{ translate('orders') }}</div>
                                </li>
                                <li class="mf-li">
                                    <span class="mf-dot bad"></span>
                                    <div class="h">{{ translate('Cross-Channel Returns') }}</div>
                                    <div style="margin-left:auto;font-weight:900;color:#f59e0b">{{ max(0, (int)($data['refund_requested'] ?? 0)) }} {{ translate('orders') }}</div>
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
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="top-restaurants-view">@include('admin-views.partials._top-restaurants',['top_restaurants'=>$data['top_restaurants']])</div></div>
                <div class="col-lg-4 col-md-6"><div class="mf-card h-100" id="popular-restaurants-view">@include('admin-views.partials._popular-restaurants',['popular'=>$data['popular']])</div></div>
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
                data: [{{ implode(",",$totalSellSeries) }}]
            },{
                name: '{{ translate('Admin Comission') }}',
                data: [{{ implode(",",$commissionSeries) }}]
            },{
                name: '{{ translate('Delivery Comission') }}',
                data: [{{ implode(",",$deliveryCommissionSeries) }}]
            }],
            chart: { height: 350, type: 'area', toolbar: { show:false }, colors: ['#76ffcd','#ff6d6d', '#005555'] },
            colors: ['#76ffcd','#ff6d6d', '#005555'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2, colors: ['#76ffcd','#ff6d6d', '#005555'] },
            fill: { type: 'gradient', colors: ['#76ffcd','#ff6d6d', '#005555'] },
            xaxis: { categories: [{!! implode(",",$labelSeries) !!}] },
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

