@extends('layouts.vendor.app')

@section('title', translate('Omnichannel'))

@push('css_or_js')
    <style>
        .mf-oc{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-oc .mf-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:14px}
        @media(max-width:991.98px){.mf-oc .mf-strip{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:575.98px){.mf-oc .mf-strip{grid-template-columns:1fr}}
        .mf-oc .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:12px 14px;display:flex;justify-content:space-between;gap:10px}
        .mf-oc .mf-kpi .t{font-size:10px;font-weight:900;color:#64748b;text-transform:uppercase;letter-spacing:.09em}
        .mf-oc .mf-kpi .v{font-size:22px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-oc .mf-kpi .s{font-size:11px;color:#94a3b8;font-weight:800}
        .mf-oc .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px}
        .mf-oc .mf-card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
        .mf-oc .mf-card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .mf-oc .mf-grid2{display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-bottom:14px}
        @media(max-width:1199.98px){.mf-oc .mf-grid2{grid-template-columns:1fr}}
        .mf-oc .mf-chart{height:260px;padding:12px 14px}
        .mf-oc .mf-chart canvas{height:100%!important;width:100%!important;display:block}
        .mf-oc .mf-donut{height:260px;padding:12px 14px}
        .mf-oc .mf-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 10px;font-size:12px;font-weight:900;border:1px solid #e2e8f0;background:#f1f5f9;color:#0f172a}
        .mf-oc .chip{display:inline-flex;align-items:center;border-radius:999px;padding:3px 10px;font-size:11px;font-weight:900;border:1px solid #e2e8f0;background:#fff}
        .mf-oc .chip.c1{border-color:rgba(99,102,241,.35);background:rgba(99,102,241,.08);color:#4f46e5}
        .mf-oc .chip.c2{border-color:rgba(34,197,94,.35);background:rgba(34,197,94,.10);color:#16a34a}
        .mf-oc .chip.c3{border-color:rgba(14,165,233,.35);background:rgba(14,165,233,.10);color:#0284c7}
        .mf-oc .chip.c4{border-color:rgba(249,115,22,.35);background:rgba(249,115,22,.10);color:#ea580c}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-oc">
        <div class="mf-strip">
            @foreach ($channels as $ch)
                <div class="mf-kpi">
                    <div>
                        <div class="t">{{ $ch['label'] }}</div>
                        <div class="v">{{ (int) $ch['count'] }}</div>
                    </div>
                    <div class="text-right">
                        <div class="mf-badge">{{ (int) $ch['pct'] }}%</div>
                        <div class="s mt-1">vs MoM</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mf-grid2">
            <div class="mf-card">
                <div class="mf-card-h">
                    <div class="tt">{{ translate('Channel Performance Trend') }}</div>
                    <div class="text-muted small">{{ translate('Last 6 months') }}</div>
                </div>
                <div class="mf-chart">
                    <canvas id="mfChannelTrend"></canvas>
                </div>
            </div>
            <div class="mf-card">
                <div class="mf-card-h">
                    <div class="tt">{{ translate('Fulfilment Methods') }}</div>
                    <a class="btn btn-sm btn-outline-primary" href="javascript:">{{ translate('Sync Channels') }}</a>
                </div>
                <div class="mf-donut">
                    <canvas id="mfChannelMix"></canvas>
                </div>
            </div>
        </div>

        <div class="mf-card">
            <div class="mf-card-h">
                <div class="tt">{{ translate('Omnichannel Order List') }}</div>
                <div class="text-muted small">{{ translate('Recent (30d)') }}</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.order_id') }}</th>
                        <th>{{ translate('Channel') }}</th>
                        <th>{{ translate('messages.amount') }}</th>
                        <th>{{ translate('Fulfilment') }}</th>
                        <th>{{ translate('messages.status') }}</th>
                        <th>{{ translate('messages.date') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(($recentOrders ?? []) as $o)
                        @php($ot = (string) ($o->order_type ?? 'delivery'))
                        <tr>
                            <td class="font-weight-bold">OM-{{ str_pad((string) $o->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($ot==='delivery')
                                    <span class="chip c1">{{ translate('Online') }}</span>
                                @elseif($ot==='take_away')
                                    <span class="chip c2">{{ translate('Click & Collect') }}</span>
                                @elseif($ot==='pos')
                                    <span class="chip c3">{{ translate('In-Store') }}</span>
                                @else
                                    <span class="chip c4">{{ translate('messages.parcel') }}</span>
                                @endif
                            </td>
                            <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency((float) ($o->order_amount ?? 0)) }}</td>
                            <td class="text-muted small">{{ $ot==='delivery' ? translate('Standard Delivery') : ($ot==='take_away' ? translate('In-Store Pickup') : ($ot==='pos' ? translate('In-Store Pickup') : translate('Express'))) }}</td>
                            <td>
                                <span class="badge badge-soft-info">{{ $o->order_status ?? '—' }}</span>
                            </td>
                            <td class="text-muted small">{{ $o->created_at?->format('M d') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">{{ translate('no_data_found') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('script_2')
        <script>
            (function () {
                function loadChartJs(cb) {
                    if (window.Chart) return cb();
                    var s = document.createElement('script');
                    s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
                    s.onload = cb;
                    document.head.appendChild(s);
                }

                loadChartJs(function () {
                    var labels = @json($trendLabels ?? []);
                    var online = @json($trendOnline ?? []);
                    var click = @json($trendClickCollect ?? []);
                    var instore = @json($trendInStore ?? []);

                    var ctx1 = document.getElementById('mfChannelTrend');
                    if (ctx1) {
                        new Chart(ctx1, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [
                                    {label: 'Online', data: online, borderColor: '#4f46e5', backgroundColor: 'rgba(79,70,229,.12)', tension: .35, fill: false},
                                    {label: 'Click & Collect', data: click, borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,.12)', tension: .35, fill: false},
                                    {label: 'In-Store', data: instore, borderColor: '#0284c7', backgroundColor: 'rgba(2,132,199,.12)', tension: .35, fill: false},
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {legend: {position: 'top', labels: {boxWidth: 10, usePointStyle: true}}},
                                scales: {y: {beginAtZero: true, grid: {color: 'rgba(148,163,184,.25)'}}, x: {grid: {display: false}}}
                            }
                        });
                    }

                    var mix = @json(collect($channels ?? [])->pluck('count'));
                    var mixLabels = @json(collect($channels ?? [])->pluck('label'));
                    var ctx2 = document.getElementById('mfChannelMix');
                    if (ctx2) {
                        new Chart(ctx2, {
                            type: 'doughnut',
                            data: {
                                labels: mixLabels,
                                datasets: [{
                                    data: mix,
                                    backgroundColor: ['#4f46e5','#16a34a','#0284c7','#ea580c'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '68%',
                                plugins: {legend: {position: 'bottom', labels: {boxWidth: 10, usePointStyle: true}}}
                            }
                        });
                    }
                });
            })();
        </script>
    @endpush
@endsection
