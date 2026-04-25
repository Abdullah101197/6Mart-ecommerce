@extends('layouts.vendor.app')

@section('title', translate('AI Pulse'))

@push('css_or_js')
    <style>
        .mf-ap{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-ap .mf-hero{background:linear-gradient(125deg,#312e81 0%,#4338ca 45%,#6d28d9 100%);border-radius:14px;padding:20px 22px;color:#fff;margin-bottom:16px;position:relative;overflow:hidden}
        .mf-ap .mf-hero h1{font-size:18px;font-weight:800;margin:0}
        .mf-ap .mf-hero p{font-size:12px;opacity:.85;margin:6px 0 0}
        .mf-ap .mf-kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px}
        .mf-ap .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .mf-ap .mf-kpi .t{font-size:10px;color:#64748b;font-weight:800;letter-spacing:.1em;text-transform:uppercase}
        .mf-ap .mf-kpi .v{font-size:24px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-ap .mf-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-top:14px}
        @media(max-width:1199.98px){.mf-ap .mf-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:767.98px){.mf-ap .mf-grid{grid-template-columns:1fr}}
        .mf-ap .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden}
        .mf-ap .mf-card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:10px}
        .mf-ap .mf-card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .mf-ap .mf-card-b{padding:12px 14px}
        .mf-ap .mf-it{padding:10px 0;border-bottom:1px solid #f1f5f9}
        .mf-ap .mf-it:last-child{border-bottom:0}
        .mf-ap .mf-it .t{font-weight:900;color:#0f172a;font-size:13px}
        .mf-ap .mf-it .d{color:#64748b;font-weight:800;font-size:12px;margin-top:4px}
        .mf-ap .mf-it a{font-weight:900;font-size:12px;color:#5566ff;text-decoration:none}
        .mf-ap .mf-it a:hover{text-decoration:underline}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-ap">
        <div class="mf-hero">
            <h1>{{ translate('AI Pulse') }}</h1>
            <p>{{ translate('Store Activity') }} — {{ translate('Recent Orders') }} &amp; {{ translate('Inventory alerts') }}</p>
            <div class="mt-3 d-flex flex-wrap" style="gap:8px">
                <a class="btn btn-sm btn-light" href="{{ route('vendor.order.list', ['status' => 'all']) }}">{{ translate('Review new orders') }}</a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('vendor.item.list') }}">{{ translate('messages.items') }}</a>
            </div>
        </div>
        <div class="mf-kpis">
            <div class="mf-kpi">
                <div class="t">{{ translate('Recent Orders') }} (7d)</div>
                <div class="v">{{ $orders7d }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Recent Orders') }} (30d)</div>
                <div class="v">{{ $orders30d }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Open and assign/unassigned orders.') }}</div>
                <div class="v">{{ $pendingOrders }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Total SKUs') }}</div>
                <div class="v">{{ $itemsCount }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('low_stock') }}</div>
                <div class="v">{{ $lowStock }}</div>
            </div>
        </div>

        <div class="mf-grid">
            @foreach(($insights ?? []) as $box)
                <div class="mf-card">
                    <div class="mf-card-h">
                        <div class="tt">{{ $box['group'] ?? '' }}</div>
                        <div style="font-weight:900">{{ $box['icon'] ?? '' }}</div>
                    </div>
                    <div class="mf-card-b">
                        @foreach(($box['items'] ?? []) as $it)
                            <div class="mf-it">
                                <div class="t">{{ $it['t'] ?? '' }}</div>
                                <div class="d">{{ $it['d'] ?? '' }}</div>
                                @if(!empty($it['a']))
                                    <div class="mt-1"><a href="{{ $it['a'] }}">{{ translate('View') }} →</a></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
