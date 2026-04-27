@extends('layouts.vendor.app')

@section('title', translate('Sponsored Ads'))

@push('css_or_js')
    <style>
        .mf-sa{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-sa .hero{background:linear-gradient(125deg,#1e3a8a 0%,#2563eb 45%,#60a5fa 100%);border-radius:14px;padding:18px 20px;color:#fff;margin-bottom:14px}
        .mf-sa .hero h1{font-size:18px;font-weight:900;margin:0}
        .mf-sa .hero p{font-size:12px;opacity:.85;margin:6px 0 0}
        .mf-sa .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .mf-sa .kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .mf-sa .kpi .t{font-size:10px;color:#64748b;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
        .mf-sa .kpi .v{font-size:24px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-sa .card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;margin-top:14px}
        .mf-sa .card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .mf-sa .card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-sa">
        <div class="hero">
            <h1>{{ translate('Sponsored Ads') }}</h1>
            <p>{{ translate('Promote products to reach more buyers') }}</p>
            <div class="mt-3 d-flex flex-wrap" style="gap:8px">
                <a class="btn btn-sm btn-light" href="{{ route('vendor.vendor_promotions') }}">{{ translate('Vendor Promotions') }}</a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('vendor.item.list') }}">{{ translate('messages.items') }}</a>
            </div>
        </div>

        <div class="kpis">
            <div class="kpi"><div class="t">{{ translate('Active campaigns') }}</div><div class="v">{{ (int) data_get($kpis,'activeCampaigns',0) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Spend (30d)') }}</div><div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($kpis,'spend30',0)) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Clicks (30d)') }}</div><div class="v">{{ (int) data_get($kpis,'clicks30',0) }}</div></div>
            <div class="kpi"><div class="t">{{ translate('Orders (30d)') }}</div><div class="v">{{ (int) data_get($kpis,'orders30',0) }}</div></div>
        </div>

        <div class="card">
            <div class="card-h">
                <div class="tt">{{ translate('Suggested products to advertise') }}</div>
                <span class="text-muted small">{{ translate('Pick any SKU') }}</span>
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
                    @forelse(($suggested ?? []) as $it)
                        <tr>
                            <td class="font-weight-bold">{{ \Illuminate\Support\Str::limit($it->name, 32) }}</td>
                            <td class="text-center">{{ (int) ($it->stock ?? 0) }}</td>
                            <td class="text-right font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency((float) ($it->price ?? 0)) }}</td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-outline-primary disabled" href="javascript:" title="{{ translate('Coming soon') }}">{{ translate('Create ad') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">{{ translate('no_data_found') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

