@extends('layouts.vendor.app')

@section('title', translate('Vendor Promotions'))

@push('css_or_js')
    <style>
        .mf-vp{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-vp .hero{background:linear-gradient(125deg,#0f766e 0%,#0d9488 45%,#14b8a6 100%);border-radius:14px;padding:18px 20px;color:#fff;margin-bottom:14px}
        .mf-vp .hero h1{font-size:18px;font-weight:900;margin:0}
        .mf-vp .hero p{font-size:12px;opacity:.85;margin:6px 0 0}
        .mf-vp .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .mf-vp .kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .mf-vp .kpi .t{font-size:10px;color:#64748b;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
        .mf-vp .kpi .v{font-size:24px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-vp .grid{display:grid;grid-template-columns:2fr 1fr;gap:12px;margin-top:14px}
        @media(max-width:1199.98px){.mf-vp .grid{grid-template-columns:1fr}}
        .mf-vp .card{background:#fff;border:1px solid #e2e8f0;border-radius:14px}
        .mf-vp .card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .mf-vp .card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-vp">
        <div class="hero">
            <h1>{{ translate('Vendor Promotions') }}</h1>
            <p>{{ translate('Plan offers to boost demand and clear slow movers') }}</p>
            <div class="mt-3 d-flex flex-wrap" style="gap:8px">
                <a class="btn btn-sm btn-light" href="{{ route('vendor.item.list') }}">{{ translate('messages.items') }}</a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('vendor.order.list', ['status' => 'all']) }}">{{ translate('Review orders') }}</a>
            </div>
        </div>

        <div class="kpis">
            <div class="kpi"><div class="t">{{ translate('Active promos') }}</div><div class="v">{{ (int) data_get($kpis,'activePromos',0) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Scheduled') }}</div><div class="v">{{ (int) data_get($kpis,'scheduledPromos',0) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Catalog size') }}</div><div class="v">{{ (int) data_get($kpis,'catalogSize',0) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Ready to boost') }}</div><div class="v">{{ (int) data_get($kpis,'readyToBoost',0) }}</div></div>
        </div>

        <div class="grid">
            <div class="card">
                <div class="card-h">
                    <div class="tt">{{ translate('Recommendations') }}</div>
                    <span class="text-muted small">{{ translate('Last 30 days') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('messages.item') }}</th>
                            <th class="text-center">{{ translate('Stock') }}</th>
                            <th class="text-right">{{ translate('messages.price') }}</th>
                            <th class="text-right">{{ translate('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse(($recommendations ?? []) as $it)
                            <tr>
                                <td class="font-weight-bold">{{ \Illuminate\Support\Str::limit($it->name, 32) }}</td>
                                <td class="text-center">{{ (int) ($it->stock ?? 0) }}</td>
                                <td class="text-right font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency((float) ($it->price ?? 0)) }}</td>
                                <td class="text-right">
                                    <a class="btn btn-sm btn-outline-primary disabled" href="javascript:" title="{{ translate('Coming soon') }}">{{ translate('Create promo') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">{{ translate('no_data_found') }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-h">
                    <div class="tt">{{ translate('Next steps') }}</div>
                </div>
                <div class="card-body">
                    <div class="text-muted small">
                        {{ translate('This RMS page is ready. Promotion engine actions will be added next.') }}
                    </div>
                    <div class="mt-3 d-flex flex-column" style="gap:10px">
                        <a class="btn btn--primary" href="{{ route('vendor.sponsored_ads') }}">{{ translate('Try Sponsored Ads') }}</a>
                        <a class="btn btn--reset" href="{{ route('vendor.item.stock-limit-list') }}">{{ translate('Low stock list') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

