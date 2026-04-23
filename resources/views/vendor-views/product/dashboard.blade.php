@extends('layouts.vendor.app')

@section('title', translate('Products Dashboard'))

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
                .mf-products .mf-grid{display:grid;grid-template-columns:repeat(12,minmax(0,1fr));gap:14px}
                .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                .mf-products .mf-card-h{padding:14px 16px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:10px}
                .mf-products .mf-card-t{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:900;margin:0}
                .mf-products .mf-card-b{padding:14px 16px}
                .mf-products .mf-kpis{grid-column:1/-1;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px}
                @media(max-width:991.98px){.mf-products .mf-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
                @media(max-width:575.98px){.mf-products .mf-kpis{grid-template-columns:1fr}}
                .mf-products .mf-kpi{position:relative;padding:14px 16px;border-radius:14px;border:1px solid #e2e8f0;background:#fff}
                .mf-products .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                .mf-products .mf-kpi .v{font-size:24px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                .mf-products .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
                .mf-products .mf-kpi .ico{position:absolute;right:12px;top:12px;width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;color:#0f172a}
                .mf-products .mf-split{display:flex;gap:8px;flex-wrap:wrap;color:#64748b;font-weight:800;font-size:11px;margin-top:6px}
                .mf-products .mf-pill{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:900;border:1px solid #e2e8f0}
                .mf-products .mf-pill.ok{background:#ecfdf5;border-color:#bbf7d0;color:#16a34a}
                .mf-products .mf-pill.warn{background:#fffbeb;border-color:#fde68a;color:#b45309}
                .mf-products .mf-list{display:flex;flex-direction:column;gap:10px}
                .mf-products .mf-row{display:flex;align-items:center;justify-content:space-between;gap:10px}
                .mf-products .mf-row .n{font-weight:900;color:#0f172a}
                .mf-products .mf-row .m{font-weight:900;color:#16a34a}
                .mf-products .mf-muted{color:#64748b;font-weight:800;font-size:11px}
                .mf-products .mf-actions{display:flex;flex-direction:column;gap:10px}
                .mf-products .mf-actions .btn{height:38px;border-radius:10px;font-weight:900;font-size:12px;display:inline-flex;align-items:center;justify-content:center;gap:8px}
                .mf-products .mf-actions .btn.btn-soft{background:#eef2f7;border:1px solid #e2e8f0;color:#0f172a}
                .mf-products .mf-actions .btn.btn--primary{border:0}
                /* Top row proportions (match RMS): chart wide, two side cards equal */
                .mf-products .mf-wide{grid-column:1/7}
                .mf-products .mf-mid{grid-column:7/10}
                .mf-products .mf-side{grid-column:10/-1}
                @media(max-width:1199.98px){.mf-products .mf-wide{grid-column:1/-1}.mf-products .mf-mid{grid-column:1/-1}.mf-products .mf-side{grid-column:1/-1}}
                .mf-products .mf-seg{display:inline-flex;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:12px;padding:3px;gap:3px}
                .mf-products .mf-seg button{border:0;background:transparent;padding:6px 10px;border-radius:10px;font-weight:900;font-size:12px;color:#64748b}
                .mf-products .mf-seg button.on{background:#fff;color:#0f172a;box-shadow:0 1px 2px rgba(2,6,23,.06)}
                .mf-products .mf-mini{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-bottom:10px}
                .mf-products .mf-mini .box{border:1px solid #e2e8f0;border-radius:12px;padding:10px 12px;background:#f8fafc}
                .mf-products .mf-mini .box.in{background:#eff6ff}
                .mf-products .mf-mini .box.sel{background:#ecfdf5}
                .mf-products .mf-mini .box .h{font-size:10px;letter-spacing:.1em;text-transform:uppercase;font-weight:900;color:#64748b}
                .mf-products .mf-mini .box .vv{font-size:18px;font-weight:900;color:#0f172a;margin-top:4px}
                .mf-products .mf-mini .box .ss{font-size:11px;color:#64748b}
                .mf-products .mf-chart{height:260px;position:relative}
                .mf-products .mf-chart canvas{height:100%!important;width:100%!important;display:block}
                .mf-products .mf-link{color:#5566ff;font-weight:900;font-size:12px;text-decoration:none}
                .mf-products .mf-link:hover{text-decoration:underline}
                .mf-products .mf-badge{display:inline-flex;align-items:center;gap:8px;border-radius:999px;padding:4px 10px;font-size:12px;font-weight:900;border:1px solid rgba(85,102,255,.35);background:rgba(85,102,255,.08);color:#5566ff}
                .mf-products .mf-amt{font-weight:900}
                .mf-products .mf-amt.c1{color:#16a34a}
                .mf-products .mf-amt.c2{color:#2563eb}
                .mf-products .mf-amt.c3{color:#7c3aed}
                .mf-products .mf-amt.c4{color:#ea580c}
                .mf-products .mf-amt.c5{color:#0891b2}
                .mf-products .mf-headlink{display:flex;align-items:center;gap:6px}
                .mf-products .mf-headlink .arr{font-weight:900}
                .mf-products .mf-i{width:24px;height:24px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;background:#eef2f7;border:1px solid #e2e8f0;color:#64748b;font-size:12px}
                .mf-products .mf-seller-row{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9}
                .mf-products .mf-seller-row:last-child{border-bottom:0}
                .mf-products .mf-seller-name{font-weight:900;color:#0f172a}
                .mf-products .mf-seller-sub{display:flex;align-items:center;gap:6px;font-weight:900;font-size:12px;color:#64748b;margin-top:4px}
                .mf-products .mf-seller-sub i{font-size:14px}
                .mf-products .mf-pillbtn{width:100%;height:40px;border-radius:12px;font-weight:900;font-size:13px;display:flex;align-items:center;justify-content:center;gap:8px;border:1px solid transparent;text-decoration:none}
                .mf-products .mf-pillbtn.all{background:#ecfdf5;border-color:#bbf7d0;color:#16a34a}
                .mf-products .mf-pillbtn.pending{background:#fffbeb;border-color:#fde68a;color:#b45309}
                .mf-products .mf-btnlite{width:100%;height:40px;border-radius:12px;font-weight:900;font-size:13px;display:flex;align-items:center;justify-content:center;border:1px solid #e2e8f0;background:#f1f5f9;color:#0f172a;text-decoration:none}
            </style>
        @endpush

        <div class="mf-products">
            <div class="mf-grid">
                <div class="mf-kpis">
                    <div class="mf-kpi">
                        <div class="ico">📦</div>
                        <div class="t">{{ translate('Total Products') }}</div>
                        <div class="v">{{ (int) ($totalProducts ?? 0) }}</div>
                        <div class="mf-split">
                            <span>{{ translate('In-house') }}: <b>{{ (int) ($regularProducts ?? 0) }}</b></span>
                            <span>·</span>
                            <span>{{ translate('Digital') }}: <b>{{ (int) ($digitalProducts ?? 0) }}</b></span>
                        </div>
                    </div>
                    <div class="mf-kpi">
                        <div class="ico">💰</div>
                        <div class="t">{{ translate('Total Sales') }}</div>
                        <div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) ($salesThisMonth ?? 0)) }}</div>
                        <div class="s">↑ {{ translate('This month') }}</div>
                    </div>
                    <div class="mf-kpi">
                        <div class="ico">🧾</div>
                        <div class="t">{{ translate('Orders Placed') }}</div>
                        <div class="v">{{ (int) ($ordersThisMonth ?? 0) }}</div>
                        <div class="s">{{ translate('Orders this month') }}</div>
                    </div>
                    <div class="mf-kpi">
                        <div class="ico">👥</div>
                        <div class="t">{{ translate('Total Customers') }}</div>
                        <div class="v">{{ (int) ($customersThisMonth ?? 0) }}</div>
                        <div class="s">{{ translate('This month') }}</div>
                    </div>
                </div>

                <div class="mf-card mf-wide">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Sales Performance — Yearly') }}</div>
                        <div class="mf-seg" role="tablist" aria-label="Sales toggle">
                            <button type="button" class="on" data-mf-sales="inhouse">{{ translate('In-house') }}</button>
                            <button type="button" data-mf-sales="sellers">{{ translate('Sellers') }}</button>
                        </div>
                    </div>
                    <div class="mf-card-b">
                        <div class="mf-mini">
                            <div class="box in">
                                <div class="h">{{ translate('In-house Sales') }}</div>
                                <div class="vv">{{ \App\CentralLogics\Helpers::format_currency((float) ($salesThisMonth ?? 0)) }}</div>
                                <div class="ss">{{ translate('Sales this month') }}</div>
                            </div>
                            <div class="box sel">
                                <div class="h">{{ translate('Sellers Sales') }}</div>
                                <div class="vv">{{ \App\CentralLogics\Helpers::format_currency(0) }}</div>
                                <div class="ss">{{ translate('Sales this month') }}</div>
                            </div>
                        </div>
                        <div class="mf-chart">
                            <canvas id="mfProductsSalesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mf-card mf-mid">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Top Brands') }}</div>
                        <a class="mf-link mf-headlink" href="{{ route('vendor.item.brands') }}" title="{{ translate('All Brands') }}">
                            <span>{{ translate('All Brands') }}</span><span class="arr">→</span>
                        </a>
                    </div>
                    <div class="mf-card-b">
                        <div class="mf-badge">{{ (int) ($brandsCount ?? 0) }} {{ translate('Total Brands') }}</div>
                        <div style="height:10px"></div>
                        <div class="mf-list">
                            @foreach(($topBrands ?? []) as $b)
                                <div class="mf-row">
                                    <div style="display:flex;align-items:center;gap:8px">
                                        <span class="mf-i">🏷️</span>
                                        <div class="n">{{ $b['name'] }}</div>
                                    </div>
                                    <div class="m">
                                        @php($br = $b['revenue'] ?? null)
                                        @if($br === null || (float) $br <= 0)
                                            <span class="mf-muted">—</span>
                                        @else
                                            <span class="mf-amt c{{ min(($loop->index ?? 0)+1, 5) }}">{{ \App\CentralLogics\Helpers::format_currency((float) $br) }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if(empty($topBrands) || count($topBrands) === 0)
                                <div class="mf-muted">{{ translate('No brands found') }}</div>
                            @endif
                        </div>
                        <div style="height:10px"></div>
                        <a class="mf-btnlite" href="{{ route('vendor.item.brands') }}">{{ translate('Manage Brands') }}</a>
                    </div>
                </div>

                <div class="mf-card mf-side">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Top Sellers') }}</div>
                        <a class="mf-link mf-headlink" href="{{ route('vendor.item.seller') }}">
                            <span>{{ translate('All Sellers') }}</span><span class="arr">→</span>
                        </a>
                    </div>
                    <div class="mf-card-b">
                        <div class="mf-seller-row">
                            <div>
                                <div class="mf-seller-name">{{ $store_data?->name ?? translate('Your Store') }}</div>
                                <div class="mf-seller-sub">
                                    <i class="tio-star text-warning"></i>
                                    <span>{{ number_format((float) ($store_data?->rating ?? 0), 1) }}</span>
                                </div>
                            </div>
                            <div class="mf-amt c1">{{ \App\CentralLogics\Helpers::format_currency((float) ($salesThisMonth ?? 0)) }}</div>
                        </div>
                        <div style="height:12px"></div>
                        <a class="mf-pillbtn all" href="{{ route('vendor.item.seller') }}">
                            <span>🌿</span> <span>{{ translate('All Sellers') }}</span>
                        </a>
                        <div style="height:10px"></div>
                        <a class="mf-pillbtn pending" href="{{ route('vendor.item.pending_item_list') }}">
                            <span>⏳</span> <span>{{ translate('Pending Sellers') }} ({{ (int) ($pendingSellers ?? 0) }})</span>
                        </a>
                    </div>
                </div>

                <div class="mf-card" style="grid-column:1/7">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Total Categories') }}</div>
                        <a class="mf-muted" href="{{ route('vendor.category.add') }}">{{ translate('Manage') }} →</a>
                    </div>
                    <div class="mf-card-b">
                        <div style="display:flex;align-items:baseline;gap:10px;flex-wrap:wrap">
                            <div style="font-size:44px;font-weight:900;line-height:1;color:#5566ff">{{ (int) ($totalCategories ?? 0) }}</div>
                            <div class="mf-muted">{{ translate('Active categories') }}</div>
                        </div>
                        <div style="height:12px"></div>
                        <div class="mf-list">
                            @forelse(($topCategories ?? []) as $c)
                                <div class="mf-row">
                                    <div class="n">{{ $c['name'] }}</div>
                                    <div class="m">{{ \App\CentralLogics\Helpers::format_currency((float) ($c['revenue'] ?? 0)) }}</div>
                                </div>
                            @empty
                                <div class="mf-muted">{{ translate('No data yet') }}</div>
                            @endforelse
                        </div>
                        <div style="height:12px"></div>
                        <a class="btn btn--primary w-100" href="{{ route('vendor.category.add') }}">+ {{ translate('Add Category') }}</a>
                    </div>
                </div>

                <div class="mf-card" style="grid-column:7/10">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Orders Placed') }}</div>
                        <a class="mf-muted" href="{{ route('vendor.order.list', ['all']) }}">{{ translate('View Orders') }} →</a>
                    </div>
                    <div class="mf-card-b">
                        <div style="display:flex;align-items:baseline;gap:10px;flex-wrap:wrap">
                            <div style="font-size:44px;font-weight:900;line-height:1;color:#16a34a">{{ (int) ($ordersThisMonth ?? 0) }}</div>
                            <div class="mf-muted">{{ translate('Orders this month') }}</div>
                        </div>
                        <div style="height:12px"></div>
                        <div class="mf-list">
                            <div class="mf-row">
                                <div class="mf-muted">{{ translate('Order placed') }}</div>
                                <div class="mf-pill ok">{{ (int) ($ordersThisMonth ?? 0) }}</div>
                            </div>
                            <div class="mf-row">
                                <div class="mf-muted">{{ translate('Order delivered') }}</div>
                                <div class="mf-pill ok">{{ (int) ($ordersDeliveredThisMonth ?? 0) }}</div>
                            </div>
                            <div class="mf-row">
                                <div class="mf-muted">{{ translate('Order refunded') }}</div>
                                <div class="mf-pill warn">{{ (int) ($ordersRefundedThisMonth ?? 0) }}</div>
                            </div>
                            <div class="mf-row">
                                <div class="mf-muted">{{ translate('Pending') }}</div>
                                <div class="mf-pill warn">{{ (int) ($ordersPendingThisMonth ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mf-card" style="grid-column:10/-1">
                    <div class="mf-card-h">
                        <div class="mf-card-t">{{ translate('Quick Actions') }}</div>
                    </div>
                    <div class="mf-card-b">
                        <div class="mf-actions">
                            <a class="btn btn--primary" href="{{ route('vendor.item.add-new') }}">+ {{ translate('Add New Product') }}</a>
                            <a class="btn btn-soft" href="{{ route('vendor.item.seller') }}">{{ translate('View Seller Products') }}</a>
                            <a class="btn btn-soft" href="{{ route('vendor.item.digital') }}">{{ translate('Digital Products') }}</a>
                            <a class="btn btn-soft" href="{{ route('vendor.item.bulk-import') }}">{{ translate('Bulk Import') }}</a>
                            <a class="btn btn-soft" href="{{ route('vendor.item.bulk-export-index') }}">{{ translate('Bulk Export') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('script_2')
            <script src="{{ asset('assets/admin/vendor/chart.js/Chart.min.js') }}"></script>
            <script>
                (function () {
                    "use strict";
                    if (!window.Chart) return;
                    const el = document.getElementById('mfProductsSalesChart');
                    if (!el) return;
                    const ctx = el.getContext('2d');
                    const dataAll = @json($salesLast12Months ?? []);
                    const dataIn = dataAll;
                    const dataSel = dataAll.map(x => ({label:x.label, value:0}));
                    const labels = dataIn.map(x => x.label);
                    let active = 'inhouse';
                    function valuesFor(which){
                        const data = which === 'sellers' ? dataSel : dataIn;
                        return data.map(x => Number(x.value || 0));
                    }
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                label: 'Sales',
                                data: valuesFor(active),
                                borderColor: '#107980',
                                backgroundColor: 'rgba(16,121,128,.12)',
                                tension: .35,
                                fill: true,
                                pointRadius: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,.25)' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });

                    const seg = document.querySelector('.mf-seg');
                    if (!seg) return;
                    const btns = seg.querySelectorAll('button[data-mf-sales]');
                    btns.forEach(btn => btn.addEventListener('click', function () {
                        const which = this.getAttribute('data-mf-sales');
                        active = which || 'inhouse';
                        btns.forEach(b => b.classList.toggle('on', b === this));
                        const chart = Chart.getChart(el);
                        if (!chart) return;
                        chart.data.datasets[0].data = valuesFor(active);
                        chart.update();
                    }));
                })();
            </script>
        @endpush
    @else
        <div class="card">
            <div class="card-body">
                {{ translate('Products dashboard is available in RMS UI mode.') }}
            </div>
        </div>
    @endif
</div>
@endsection

