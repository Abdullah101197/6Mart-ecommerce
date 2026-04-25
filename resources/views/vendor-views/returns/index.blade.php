@extends('layouts.vendor.app')

@section('title', translate('Returns'))

@push('css_or_js')
    <style>
        .mf-ret{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-ret .mf-hero{background:linear-gradient(110deg,#065f46 0%,#0f766e 45%,#134e4a 100%);border-radius:14px;padding:18px 20px;color:#fff;margin-bottom:14px}
        .mf-ret .mf-hero h1{font-size:18px;font-weight:900;margin:0}
        .mf-ret .mf-hero p{font-size:12px;opacity:.86;margin:6px 0 0}
        .mf-ret .mf-strip{display:grid;grid-template-columns:repeat(5,minmax(0,1fr));gap:12px;margin-bottom:14px}
        @media(max-width:1199.98px){.mf-ret .mf-strip{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(max-width:767.98px){.mf-ret .mf-strip{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:575.98px){.mf-ret .mf-strip{grid-template-columns:1fr}}
        .mf-ret .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:12px 14px}
        .mf-ret .mf-kpi .t{font-size:10px;font-weight:900;color:#64748b;text-transform:uppercase;letter-spacing:.09em}
        .mf-ret .mf-kpi .v{font-size:22px;font-weight:900;color:#0f172a;margin-top:6px;display:flex;align-items:baseline;gap:8px}
        .mf-ret .mf-kpi .s{font-size:11px;color:#64748b;font-weight:800}
        .mf-ret .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px}
        .mf-ret .mf-card-h{padding:12px 14px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
        .mf-ret .mf-card-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .mf-ret .mf-tabs{display:flex;gap:8px;flex-wrap:wrap}
        .mf-ret .mf-tab{height:34px;border-radius:999px;border:1px solid #e2e8f0;background:#f8fafc;color:#0f172a;font-weight:900;font-size:12px;padding:0 12px;display:inline-flex;align-items:center;text-decoration:none}
        .mf-ret .mf-tab.on{background:#0f766e;color:#fff;border-color:#0f766e}
        .mf-ret .mf-search{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
        .mf-ret .mf-search .form-control{height:38px;border-radius:12px}
        .mf-ret .mf-search .btn{height:38px;border-radius:12px;font-weight:900}
        .mf-ret .mf-badge{display:inline-flex;align-items:center;border-radius:999px;padding:4px 10px;font-size:12px;font-weight:900;border:1px solid #e2e8f0;background:#f1f5f9;color:#0f172a}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-ret">
        <div class="mf-hero">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:10px">
                <div>
                    <h1>{{ translate('Returns & RMA') }}</h1>
                    <p class="mb-0">{{ translate('Manage refund/return requests, quality claims and cross-channel returns.') }}</p>
                </div>
                <a class="btn btn-sm btn-light" href="{{ route('vendor.order.list', ['status' => 'all']) }}">{{ translate('messages.orders') }}</a>
            </div>
        </div>

        <div class="mf-strip">
            <div class="mf-kpi">
                <div class="t">{{ translate('Pending') }}</div>
                <div class="v">{{ (int) ($pendingCount ?? 0) }} <span class="s">{{ translate('Awaiting action') }}</span></div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Approved') }}</div>
                <div class="v">{{ (int) ($approvedCount ?? 0) }} <span class="s">{{ translate('Ready to refund') }}</span></div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Rejected') }}</div>
                <div class="v">{{ (int) ($rejectedCount ?? 0) }} <span class="s">{{ translate('Closed') }}</span></div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Total Requests') }}</div>
                <div class="v">{{ (int) ($totalRequests ?? 0) }} <span class="s">{{ translate('All time') }}</span></div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Refunded Orders') }}</div>
                <div class="v">{{ (int) ($refundedOrders ?? 0) }} <span class="s">{{ translate('Completed') }}</span></div>
            </div>
        </div>

        <div class="mf-card">
            <div class="mf-card-h">
                <div>
                    <div class="tt">{{ translate('Returns Queue') }}</div>
                    <div class="text-muted small">{{ translate('Filter by status and review each request.') }}</div>
                </div>
                <div class="mf-search">
                    <div class="mf-tabs">
                        @php($curStatus = $status ?? request('status', 'all'))
                        <a class="mf-tab {{ $curStatus === 'pending' ? 'on' : '' }}" href="{{ request()->fullUrlWithQuery(['status'=>'pending','page'=>1]) }}">{{ translate('Pending') }}</a>
                        <a class="mf-tab {{ $curStatus === 'approved' ? 'on' : '' }}" href="{{ request()->fullUrlWithQuery(['status'=>'approved','page'=>1]) }}">{{ translate('Approved') }}</a>
                        <a class="mf-tab {{ $curStatus === 'rejected' ? 'on' : '' }}" href="{{ request()->fullUrlWithQuery(['status'=>'rejected','page'=>1]) }}">{{ translate('Rejected') }}</a>
                        <a class="mf-tab {{ $curStatus === 'all' ? 'on' : '' }}" href="{{ request()->fullUrlWithQuery(['status'=>'all','page'=>1]) }}">{{ translate('All') }}</a>
                    </div>
                    <form method="get" action="{{ url()->current() }}" class="d-flex" style="gap:8px;align-items:center">
                        <input type="hidden" name="status" value="{{ $curStatus }}">
                        <input type="text" name="search" value="{{ $search ?? request('search') }}" class="form-control" placeholder="{{ translate('Search by Order ID / reason / method') }}">
                        <button class="btn btn--primary" type="submit">{{ translate('Search') }}</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.order') }}</th>
                        <th>{{ translate('messages.customer') }}</th>
                        <th>{{ translate('Request') }}</th>
                        <th>{{ translate('messages.amount') }}</th>
                        <th>{{ translate('Method') }}</th>
                        <th>{{ translate('messages.status') }}</th>
                        <th class="text-right">{{ translate('messages.action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($refunds as $r)
                        <tr>
                            <td>
                                <div class="font-weight-bold">#{{ $r->order_id }}</div>
                                <div class="text-muted small">{{ $r->created_at?->format('M d, Y') }}</div>
                            </td>
                            <td>
                                <div class="text-muted small">—</div>
                            </td>
                            <td>
                                <div class="text-muted small">{{ \Illuminate\Support\Str::limit((string) ($r->refund_reason ?? ''), 40) }}</div>
                            </td>
                            <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency((float) ($r->refund_amount ?? 0)) }}</td>
                            <td><span class="mf-badge">{{ $r->payment_method ?? '—' }}</span></td>
                            <td>
                                @php($st = (string) ($r->refund_status ?? 'pending'))
                                <span class="badge {{ $st==='approved' ? 'badge-soft-success' : ($st==='rejected' ? 'badge-soft-danger' : 'badge-soft-warning') }}">{{ $st }}</span>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-sm btn-white" href="{{ route('vendor.returns.show', $r->id) }}">{{ translate('messages.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">{{ translate('no_data_found') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if ($refunds->total() > 0)
                <div class="card-footer d-flex justify-content-between align-items-center flex-wrap" style="gap:10px">
                    <div class="text-muted small">{{ translate('Avg resolution') }}: <b>{{ number_format((float) ($avgResolutionDays ?? 0), 1) }}</b>d</div>
                    <div>{!! $refunds->links() !!}</div>
                </div>
            @endif
        </div>
    </div>
@endsection
