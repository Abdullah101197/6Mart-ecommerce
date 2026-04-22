@php($store = \App\CentralLogics\Helpers::get_store_data())
@php($sub = $store?->store_sub ?? $store?->store_sub_update_application)
@php($allowAll = ($store?->store_business_model ?? null) === 'commission')
@php($canAiPulse = $allowAll || ((int) data_get($sub, 'ai_pulse', 0) === 1))
@php($dashOn = function (string $key) use ($allowAll, $sub): bool {
    return $allowAll || ((int) data_get($sub, $key, 1) === 1);
})
@php($itemTotal = (int) ($item_total ?? 0))
@php($lowStock = (int) ($out_of_stock_count ?? 0))
@php($inStock = max(0, $itemTotal - $lowStock))
@php($stockHealth = $itemTotal > 0 ? round(($inStock / $itemTotal) * 100) : 0)
@php($ordersTotal = (int) data_get($data, 'all', 0))
@php($ordersNeedAction = (int) (data_get($data, 'pending', 0) + data_get($data, 'processing', 0)))
@php($npsScore = (int) data_get($data, 'nps_score', 72))
@php($npsLabel = $npsScore >= 70 ? translate('Excellent') : ($npsScore >= 50 ? translate('Good') : translate('Needs improvement')))
@php($recentOrders = $recent_orders ?? collect())
@php($lowStockItems = $low_stock_items ?? collect())
@php($topBuyers = $top_loyal_buyers ?? collect())
@php($activity = $store_activity ?? collect())
@php($channelMix = $channel_mix ?? [])
@php($omni = $omnichannel_summary ?? ['online' => 0, 'click_collect' => 0, 'pos' => 0, 'returns' => 0])
@php($aiLive = $ai_pulse_live ?? [])

@push('css_or_js')
    <style>
        .mf-rms{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-rms .mf-welcome{
            /* Use existing project palette */
            background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
            border-radius:14px;padding:22px 26px;color:#fff;position:relative;overflow:hidden
        }
        .mf-rms .mf-welcome:before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04)}
        .mf-rms .mf-welcome:after{content:'';position:absolute;right:60px;bottom:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.03)}
        .mf-rms .mf-welcome h1{font-size:18px;font-weight:800;margin:0;position:relative}
        .mf-rms .mf-welcome p{font-size:12px;opacity:.78;margin:6px 0 0;position:relative}
        .mf-rms .mf-actions{display:flex;gap:10px;margin-top:14px;flex-wrap:wrap;position:relative}
        .mf-rms .mf-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:7px 14px;font-weight:800;font-size:12px;border:1px solid rgba(255,255,255,.28);cursor:pointer;transition:all .15s;text-decoration:none}
        .mf-rms .mf-chip.primary{background:#fff;color:#0f172a;border-color:#fff}
        .mf-rms .mf-chip.ghost{background:rgba(255,255,255,.14);color:#fff}
        .mf-rms .mf-chip.ghost:hover{background:rgba(255,255,255,.22)}

        .mf-rms .mf-kpi-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(170px,1fr));gap:12px;margin:12px 0 14px}
        .mf-rms .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:12px 14px;min-height:74px}
        .mf-rms .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase;display:flex;justify-content:space-between;align-items:center}
        .mf-rms .mf-kpi .v{font-size:20px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
        .mf-rms .mf-kpi .s{font-size:11px;color:#64748b;margin-top:4px}
        @media (max-width:640px){.mf-rms .mf-kpi-row{grid-template-columns:repeat(2,minmax(0,1fr))}}

        .mf-rms .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
        .mf-rms .mf-card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between}
        .mf-rms .mf-card-h .ttl{font-size:10px;font-weight:900;letter-spacing:.12em;text-transform:uppercase;color:#64748b}
        .mf-rms .mf-card-h a{font-size:11px;font-weight:900;color:var(--primary-clr, #107980);text-decoration:none}
        .mf-rms .mf-card-b{padding:12px 14px}
        .mf-rms .mf-table{width:100%;border-collapse:collapse}
        .mf-rms .mf-table th{font-size:9px;color:#94a3b8;text-transform:uppercase;letter-spacing:.12em;text-align:left;padding:8px 0;border-bottom:1px solid #eef2f7}
        .mf-rms .mf-table td{font-size:12px;padding:9px 0;border-bottom:1px solid #f1f5f9;color:#0f172a}
        .mf-rms .mf-badge{display:inline-flex;align-items:center;border-radius:999px;padding:3px 9px;font-size:10px;font-weight:900;border:1px solid #e2e8f0;background:#f8fafc;color:#334155}
        .mf-rms .mf-badge.warn{background:#fff7ed;border-color:#fed7aa;color:#9a3412}
        .mf-rms .mf-badge.ok{background:#ecfdf5;border-color:#bbf7d0;color:#166534}
        .mf-rms .mf-badge.info{background:#eff6ff;border-color:#bfdbfe;color:#1d4ed8}
        .mf-rms .mf-badge.bad{background:#fef2f2;border-color:#fecaca;color:#991b1b}

        .mf-rms .mf-side{
            background:linear-gradient(160deg,var(--dark-clr, #005555), var(--primary, #006161) 45%, #0b2f2f);
            color:#e2e8f0;border:0
        }
        .mf-rms .mf-side .mf-card-h{border-color:rgba(255,255,255,.08)}
        .mf-rms .mf-side .ttl{color:rgba(226,232,240,.75)}
        .mf-rms .mf-side .mf-card-b{padding:12px 14px}
        .mf-rms .mf-side .mf-cardx{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:12px}
        .mf-rms .mf-side .mf-cardx + .mf-cardx{margin-top:10px}
        .mf-rms .mf-side .mf-cardx .h{font-weight:900;font-size:12px}
        .mf-rms .mf-side .mf-cardx .p{font-size:11px;opacity:.82;margin-top:4px}
        .mf-rms .mf-side .mf-cardx a{color:#c7ffef;font-weight:900;font-size:11px;text-decoration:none}
        .mf-rms .mf-side .mf-cta{display:flex;justify-content:center;margin-top:12px}
        .mf-rms .mf-side .mf-cta a{background:var(--primary-clr, #107980);color:#fff;border-radius:999px;padding:8px 14px;font-weight:900;font-size:11px;text-decoration:none;border:1px solid rgba(255,255,255,.12)}
    </style>
@endpush

<div class="mf-rms">
    <div class="mf-welcome mb-3">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1>{{ translate('Good Morning') }}, {{ auth('vendor')->user()->f_name ?? '' }}</h1>
                <p>{{ translate('Retail Management System') }} · {{ translate('messages.Dashboard') }}</p>
                <div class="mf-actions">
                    <a class="mf-chip primary" href="{{ route('vendor.order.list',['processing']) }}">
                        {{ translate('Open Orders') }} ({{ (int) data_get($data, 'processing', 0) }})
                    </a>
                    @if($canAiPulse)
                        <a class="mf-chip ghost" href="{{ route('vendor.ai_pulse') }}">{{ translate('AI Pulse Insights') }}</a>
                    @else
                        <span class="mf-chip ghost" style="opacity:.65;pointer-events:none">{{ translate('AI Pulse Insights') }}</span>
                    @endif
                    <a class="mf-chip ghost" href="{{ route('vendor.item.list') }}">{{ translate('Stock Status') }}</a>
                    <a class="mf-chip ghost" href="{{ route('vendor.order.list',['all']) }}">{{ translate("Today's Tasks") }} ({{ max(0,$ordersNeedAction) }})</a>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                {{-- keep right side empty to match RMS spacing --}}
            </div>
        </div>
    </div>

    {{-- KPI row --}}
    <div class="mf-kpi-row">
        @if($dashOn('dash_kpi_gmv'))
        <div class="mf-kpi">
            <div class="t"><span>GMV</span><span>💎</span></div>
            <div class="v">{{ \App\CentralLogics\Helpers::format_currency(array_sum($earning ?? [])) }}</div>
            <div class="s">{{ translate('messages.yearly_statistics') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_orders'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate('messages.orders') }}</span><span>📦</span></div>
            <div class="v">{{ $ordersTotal }}</div>
            <div class="s">{{ max(0,$ordersNeedAction) }} {{ translate('need action') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_stock_health'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate('Stock Health') }}</span><span>📦</span></div>
            <div class="v" style="color:#16a34a">{{ $stockHealth }}%</div>
            <div class="s">{{ $lowStock }} {{ translate('messages.low_stock') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_items'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate('messages.items') }}</span><span>🧺</span></div>
            <div class="v">{{ $itemTotal }}</div>
            <div class="s">{{ translate('Total SKUs') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_tasks_due'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate("Tasks due today") }}</span><span>✅</span></div>
            <div class="v">{{ max(0,$ordersNeedAction) }}</div>
            <div class="s">{{ translate('In progress') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_ai_pulse_status'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate('AI Pulse') }}</span><span>🤖</span></div>
            <div class="v">{{ $canAiPulse ? translate('Enabled') : translate('Disabled') }}</div>
            <div class="s">{{ translate('messages.subscription') }}</div>
        </div>
        @endif
        @if($dashOn('dash_kpi_nps'))
        <div class="mf-kpi">
            <div class="t"><span>{{ translate('NPS Score') }}</span><span>🙂</span></div>
            <div class="v">{{ $npsScore }}</div>
            <div class="s">{{ $npsLabel }}</div>
        </div>
        @endif
    </div>

    {{-- Top panels --}}
    <div class="row g-2 mb-3">
        @if($dashOn('dash_revenue_trend_channels'))
        <div class="col-lg-5">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Revenue Trend + Channels') }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('Retail intelligence') }} →</a>
                </div>
                <div class="mf-card-b">
                    {{-- Reuse existing yearly bar chart canvas for now (keeps real data) --}}
                    <div class="chartjs-custom">
                        <canvas id="updatingData" class="h-20rem"
                                data-hs-chartjs-options='{
                            "type": "bar",
                            "data": {
                              "labels": ["Jan","Feb","Mar","April","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
                              "datasets": [{
                                "data": [{{$earning[1] ?? 0}},{{$earning[2] ?? 0}},{{$earning[3] ?? 0}},{{$earning[4] ?? 0}},{{$earning[5] ?? 0}},{{$earning[6] ?? 0}},{{$earning[7] ?? 0}},{{$earning[8] ?? 0}},{{$earning[9] ?? 0}},{{$earning[10] ?? 0}},{{$earning[11] ?? 0}},{{$earning[12] ?? 0}}],
                                "backgroundColor": "#107980",
                                "hoverBackgroundColor": "#107980",
                                "borderColor": "#107980"
                              }]
                            },
                            "options": {
                              "legend": { "display": false },
                              "scales": {
                                "yAxes": [{
                                  "gridLines": { "color": "#e7eaf3", "drawBorder": false, "zeroLineColor": "#e7eaf3" },
                                  "ticks": { "beginAtZero": true, "fontSize": 11, "fontColor": "#97a4af", "padding": 8 }
                                }],
                                "xAxes": [{
                                  "gridLines": { "display": false, "drawBorder": false },
                                  "ticks": { "fontSize": 11, "fontColor": "#97a4af", "padding": 5 },
                                  "categoryPercentage": 0.55,
                                  "maxBarThickness": "12"
                                }]
                              },
                              "tooltips": { "mode": "index", "intersect": false }
                            }
                          }'></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if($dashOn('dash_channel_mix'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Channel Mix') }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('Details') }} →</a>
                </div>
                <div class="mf-card-b">
                    @if(count($channelMix))
                        @foreach($channelMix as $c)
                            <div style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div style="font-weight:900;font-size:12px">{{ translate($c['label']) }}</div>
                                    <div style="font-weight:900;font-size:12px">{{ (int)($c['pct'] ?? 0) }}%</div>
                                </div>
                                <div style="height:8px;border-radius:999px;background:#eef2f7;overflow:hidden;margin-top:6px">
                                    <div style="height:8px;width:{{ (int)($c['pct'] ?? 0) }}%;background:var(--primary-clr,#107980)"></div>
                                </div>
                                <div class="text-muted" style="font-size:11px;margin-top:6px">{{ (int)($c['count'] ?? 0) }} {{ translate('messages.orders') }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-muted" style="font-size:12px">{{ translate('no_data_found') }}</div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if($dashOn('dash_ai_pulse_live'))
        <div class="col-lg-3">
            <div class="mf-card mf-side h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('AI Pulse Live') }}</div>
                    @if($canAiPulse)
                        <a href="{{ route('vendor.ai_pulse') }}" style="color:#a7f3d0">{{ translate('View') }} →</a>
                    @else
                        <span style="color:rgba(167,243,208,.7);font-size:11px;font-weight:900">{{ translate('Disabled') }}</span>
                    @endif
                </div>
                <div class="mf-card-b">
                    @foreach($aiLive as $insight)
                        <div class="mf-cardx">
                            <div class="h">{{ data_get($insight,'title','') }}</div>
                            <div class="p">{{ data_get($insight,'body','') }}</div>
                            @if(!empty(data_get($insight,'action_url')) && !empty(data_get($insight,'action_label')))
                                <a href="{{ data_get($insight,'action_url') }}">{{ data_get($insight,'action_label') }} →</a>
                            @endif
                        </div>
                    @endforeach
                    @if($canAiPulse)
                        <div class="mf-cta">
                            <a href="{{ route('vendor.ai_pulse') }}">{{ translate('View All Insights') }}</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Boards row --}}
    <div class="row g-2 mb-3">
        @if($dashOn('dash_recent_orders'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Recent Orders') }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('View all') }} →</a>
                </div>
                <div class="mf-card-b">
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
                        @forelse($recentOrders as $o)
                            @php($buyerName = trim((string)($o?->customer?->f_name ?? '').' '.(string)($o?->customer?->l_name ?? '')))
                            <tr>
                                <td>
                                    <a href="{{ route('vendor.order.details', [$o->id]) }}"
                                       style="font-weight:900;color:var(--primary-clr,#107980);text-decoration:none">PO-{{ $o->id }}</a>
                                </td>
                                <td>{{ $buyerName !== '' ? $buyerName : translate('Customer') }}</td>
                                <td>{{ \App\CentralLogics\Helpers::format_currency((float)($o->order_amount ?? 0)) }}</td>
                                <td>
                                    @php($st = (string)($o->order_status ?? ''))
                                    <span class="mf-badge {{ in_array($st,['delivered'],true) ? 'ok' : (in_array($st,['canceled','failed','refunded'],true) ? 'bad' : (in_array($st,['processing','pending','confirmed','accepted'],true) ? 'warn' : 'info')) }}">
                                        {{ translate(ucwords(str_replace('_',' ',$st))) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ translate('no_data_found') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if($dashOn('dash_inventory_alerts'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Inventory Alerts') }}</div>
                    <a href="{{ route('vendor.item.stock-limit-list') }}">{{ translate('Manage stock') }} →</a>
                </div>
                <div class="mf-card-b">
                    @forelse($lowStockItems as $it)
                        <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                            <div>
                                <div style="font-weight:900">{{ \Illuminate\Support\Str::limit((string)($it->name ?? ''), 24) }}</div>
                                <div class="text-muted" style="font-size:11px">{{ translate('Stock') }}: {{ (int)($it->stock ?? 0) }}</div>
                            </div>
                            <span class="mf-badge {{ ((int)($it->stock ?? 0)) > 0 ? 'warn' : 'bad' }}">
                                {{ ((int)($it->stock ?? 0)) > 0 ? translate('Low Stock') : translate('Out of Stock') }}
                            </span>
                        </div>
                    @empty
                        <div class="text-muted" style="font-size:12px">{{ translate('no_data_found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
        @if($dashOn('dash_today_tasks'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate("Today's Tasks") }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('All tasks') }} →</a>
                </div>
                <div class="mf-card-b">
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                        <div style="font-weight:900">{{ translate('Review new orders') }}</div>
                        <span class="mf-badge info">{{ (int) data_get($data,'pending',0) }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                        <div style="font-weight:900">{{ translate('Prepare processing orders') }}</div>
                        <span class="mf-badge warn">{{ (int) data_get($data,'processing',0) }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0">
                        <div style="font-weight:900">{{ translate('Check stock alerts') }}</div>
                        <span class="mf-badge bad">{{ max(0,$lowStock) }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Next row (match RMS): Store Activity / Top Loyal Buyers / Omnichannel Summary --}}
    <div class="row g-2 mb-3">
        @if($dashOn('dash_store_activity'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Store Activity') }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('Details') }} →</a>
                </div>
                <div class="mf-card-b">
                    @forelse($activity as $a)
                        @php($st = (string) data_get($a,'status',''))
                        @php($dot = in_array($st,['delivered'],true) ? '#22c55e' : (in_array($st,['canceled','failed','refunded'],true) ? '#ef4444' : (in_array($st,['processing','pending','confirmed','accepted'],true) ? '#f59e0b' : '#3b82f6')))
                        <div style="display:flex;gap:10px;align-items:flex-start;padding:10px 0;border-bottom:1px solid #f1f5f9">
                            <span style="width:10px;height:10px;border-radius:50%;background:{{ $dot }};margin-top:4px"></span>
                            <div class="w-0 flex-grow-1">
                                <div style="font-weight:900">{{ data_get($a,'title','') }}</div>
                                <div class="text-muted" style="font-size:11px">
                                    {{ data_get($a,'time','') }}
                                    @if(data_get($a,'amount') !== null)
                                        · {{ \App\CentralLogics\Helpers::format_currency((float) data_get($a,'amount',0)) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted" style="font-size:12px">{{ translate('no_data_found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @if($dashOn('dash_top_loyal_buyers'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Top Loyal Buyers') }}</div>
                    <a href="{{ \Illuminate\Support\Facades\Route::has('vendor.pos.customers') ? route('vendor.pos.customers') : 'javascript:void(0)' }}"
                       aria-disabled="{{ \Illuminate\Support\Facades\Route::has('vendor.pos.customers') ? 'false' : 'true' }}"
                       @unless(\Illuminate\Support\Facades\Route::has('vendor.pos.customers')) style="opacity:.6;pointer-events:none" @endunless
                    >{{ translate('Loyalty Program') }} →</a>
                </div>
                <div class="mf-card-b">
                    @forelse($topBuyers as $b)
                        @php($nm = trim((string)($b?->customer?->f_name ?? '').' '.(string)($b?->customer?->l_name ?? '')))
                        <div class="d-flex align-items-start justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                            <div>
                                <div style="font-weight:900">{{ $nm !== '' ? $nm : translate('Customer') }}</div>
                                <div class="text-muted" style="font-size:11px">{{ (int)($b->orders_count ?? 0) }} {{ translate('messages.orders') }}</div>
                            </div>
                            <div style="font-weight:900">{{ \App\CentralLogics\Helpers::format_currency((float)($b->total_spent ?? 0)) }}</div>
                        </div>
                    @empty
                        <div class="text-muted" style="font-size:12px">{{ translate('no_data_found') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @if($dashOn('dash_omnichannel_summary'))
        <div class="col-lg-4">
            <div class="mf-card h-100">
                <div class="mf-card-h">
                    <div class="ttl">{{ translate('Omnichannel Summary') }}</div>
                    <a href="{{ route('vendor.order.list',['all']) }}">{{ translate('Details') }} →</a>
                </div>
                <div class="mf-card-b">
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                        <div class="text-muted" style="font-size:12px">{{ translate('Online') }}</div>
                        <div style="font-weight:900">{{ (int) data_get($omni,'online',0) }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                        <div class="text-muted" style="font-size:12px">{{ translate('Click & Collect') }}</div>
                        <div style="font-weight:900">{{ (int) data_get($omni,'click_collect',0) }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #f1f5f9">
                        <div class="text-muted" style="font-size:12px">{{ translate('In-Store (POS)') }}</div>
                        <div style="font-weight:900">{{ (int) data_get($omni,'pos',0) }}</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between" style="padding:10px 0">
                        <div class="text-muted" style="font-size:12px">{{ translate('Cross-Channel Returns') }}</div>
                        <div style="font-weight:900">{{ (int) data_get($omni,'returns',0) }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Existing order stats grid (keeps module behavior) --}}
    <div class="mf-card">
        <div class="mf-card-h">
            <div class="ttl">{{ translate('messages.dashboard_order_statistics') }}</div>
            <div style="font-size:11px;font-weight:900;color:#64748b">{{ translate('Overall') }}</div>
        </div>
        <div class="mf-card-b">
            <div class="row g-2" id="order_stats">
                @include('vendor-views.partials._dashboard-order-stats',['data'=>$data])
            </div>
        </div>
    </div>
</div>

