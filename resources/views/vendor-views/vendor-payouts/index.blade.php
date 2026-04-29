@extends('layouts.vendor.app')

@section('title', translate('Vendor Payouts'))

@push('css_or_js')
    <style>
        .mf-vpay{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-vpay .hero{background:linear-gradient(125deg,#111827 0%,#0f766e 45%,#14b8a6 100%);border-radius:14px;padding:18px 20px;color:#fff;margin-bottom:14px}
        .mf-vpay .hero h1{font-size:18px;font-weight:900;margin:0}
        .mf-vpay .hero p{font-size:12px;opacity:.85;margin:6px 0 0}
        .mf-vpay .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px}
        .mf-vpay .kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:14px 16px;box-shadow:0 1px 3px rgba(0,0,0,.04)}
        .mf-vpay .kpi .t{font-size:10px;color:#64748b;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
        .mf-vpay .kpi .v{font-size:20px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-vpay .kpi .s{font-size:12px;color:#64748b;margin-top:6px}
        .mf-vpay .chip{display:inline-flex;align-items:center;border-radius:999px;padding:4px 10px;font-size:11px;font-weight:900;border:1px solid #e2e8f0;background:#fff}
        .mf-vpay .chip.pending{background:#fff7ed;color:#9a3412;border-color:#fed7aa}
        .mf-vpay .chip.approved{background:#ecfdf5;color:#065f46;border-color:#a7f3d0}
        .mf-vpay .chip.denied{background:#fef2f2;color:#991b1b;border-color:#fecaca}
        .mf-vpay .card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;margin-top:14px}
        .mf-vpay .card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap}
        .mf-vpay .card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .mf-vpay .filters{display:flex;gap:10px;flex-wrap:wrap;align-items:center}
        .mf-vpay .filters .form-control{height:40px;border-radius:12px}
        .mf-vpay .filters .btn{height:40px;border-radius:12px;font-weight:900}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-vpay">
        <div class="hero">
            <h1>{{ translate('Vendor Payouts') }}</h1>
            <p>{{ translate('Track withdrawal requests and payout status') }}</p>
            <div class="mt-3 d-flex flex-wrap" style="gap:8px">
                <a class="btn btn-sm btn-light" href="{{ route('vendor.wallet.index') }}">{{ translate('My Wallet') }}</a>
                <a class="btn btn-sm btn-outline-light" href="{{ route('vendor.wallet.index') }}">{{ translate('Request payout') }}</a>
            </div>
        </div>

        <div class="kpis">
            <div class="kpi">
                <div class="t">{{ translate('Pending') }}</div>
                <div class="v">{{ (int) data_get($kpis,'pendingCount',0) }}</div>
                <div class="s">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($kpis,'pendingAmount',0)) }}</div>
            </div>
            <div class="kpi">
                <div class="t">{{ translate('Approved') }}</div>
                <div class="v">{{ (int) data_get($kpis,'approvedCount',0) }}</div>
                <div class="s">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($kpis,'approvedAmount',0)) }}</div>
            </div>
            <div class="kpi">
                <div class="t">{{ translate('Denied') }}</div>
                <div class="v">{{ (int) data_get($kpis,'deniedCount',0) }}</div>
                <div class="s">{{ translate('Requests') }}</div>
            </div>
            <div class="kpi">
                <div class="t">{{ translate('Total requested') }}</div>
                <div class="v">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($kpis,'totalAmount',0)) }}</div>
                <div class="s">{{ translate('All statuses') }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-h">
                <div class="tt">{{ translate('Withdrawal history') }}</div>
                <form class="filters" method="GET" action="{{ route('vendor.vendor_payouts') }}">
                    <select class="form-control" name="status" style="max-width:220px">
                        <option value="all" {{ ($status ?? 'all')==='all'?'selected':'' }}>{{ translate('All') }}</option>
                        <option value="pending" {{ ($status ?? 'all')==='pending'?'selected':'' }}>{{ translate('Pending') }}</option>
                        <option value="approved" {{ ($status ?? 'all')==='approved'?'selected':'' }}>{{ translate('Approved') }}</option>
                        <option value="denied" {{ ($status ?? 'all')==='denied'?'selected':'' }}>{{ translate('Denied') }}</option>
                    </select>
                    <button class="btn btn--primary" type="submit">{{ translate('Filter') }}</button>
                    <a class="btn btn--reset" href="{{ route('vendor.vendor_payouts') }}">{{ translate('Reset') }}</a>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('SL') }}</th>
                        <th>{{ translate('messages.amount') }}</th>
                        <th>{{ translate('messages.request_time') }}</th>
                        <th>{{ translate('messages.status') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse(($withdrawals ?? []) as $k => $w)
                        <tr>
                            <td>{{ $k + $withdrawals->firstItem() }}</td>
                            <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency((float) ($w->amount ?? 0)) }}</td>
                            <td class="text-muted small">
                                {{ $w->created_at ? \Carbon\Carbon::parse($w->created_at)->format('d M Y, h:i A') : '—' }}
                            </td>
                            <td>
                                @if((int) ($w->approved ?? 0) === 1)
                                    <span class="chip approved">{{ translate('Approved') }}</span>
                                @elseif((int) ($w->approved ?? 0) === 2)
                                    <span class="chip denied">{{ translate('Denied') }}</span>
                                @else
                                    <span class="chip pending">{{ translate('Pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">{{ translate('no_data_found') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if(($withdrawals ?? null) && $withdrawals->hasPages())
                <div class="card-footer">
                    {!! $withdrawals->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection

