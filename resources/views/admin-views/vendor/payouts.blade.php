@extends('layouts.admin.app')

@section('title', translate('Vendor Payouts'))

@push('css_or_js')
    <style>
        .mf-wrap{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px}
        .mf-head h1{font-size:18px;font-weight:900;margin:0;color:#0f172a}
        .mf-sub{font-size:12px;color:#64748b;margin-top:4px}
        .mf-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin:12px 0}
        @media (max-width: 991px){.mf-kpis{grid-template-columns:repeat(2,minmax(0,1fr));}}
        .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:14px 16px}
        .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
        .mf-kpi .v{font-size:20px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
        .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
        .mf-filters{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
        .mf-filters .form-control{height:40px;border-radius:12px}
        .mf-filters .btn{height:40px;border-radius:12px;font-weight:900}
        .mf-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:6px 10px;font-size:12px;font-weight:900}
        .mf-chip.pending{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa}
        .mf-chip.approved{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0}
        .mf-chip.denied{background:#fef2f2;color:#991b1b;border:1px solid #fecaca}
        .mf-table td, .mf-table th{vertical-align:middle}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-wrap">
        <div class="mf-head">
            <div>
                <h1>{{ translate('Vendor Payouts') }}</h1>
                <div class="mf-sub">{{ translate('Review and process vendor withdrawal requests') }}</div>
            </div>
            <div class="d-flex gap-2">
                <a class="btn btn--primary" href="{{ route('admin.store.withdraw_list') }}">{{ translate('Open legacy list') }}</a>
            </div>
        </div>

        <div class="mf-kpis">
            <div class="mf-kpi">
                <div class="t">{{ translate('Pending') }}</div>
                <div class="v">{{ $kpis['pendingCount'] ?? 0 }}</div>
                <div class="s">{{ \App\CentralLogics\Helpers::format_currency($kpis['pendingAmount'] ?? 0) }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Approved') }}</div>
                <div class="v">{{ $kpis['approvedCount'] ?? 0 }}</div>
                <div class="s">{{ \App\CentralLogics\Helpers::format_currency($kpis['approvedAmount'] ?? 0) }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Denied') }}</div>
                <div class="v">{{ $kpis['deniedCount'] ?? 0 }}</div>
                <div class="s">{{ translate('Requests') }}</div>
            </div>
            <div class="mf-kpi">
                <div class="t">{{ translate('Total Requested') }}</div>
                <div class="v">{{ \App\CentralLogics\Helpers::format_currency($kpis['totalAmount'] ?? 0) }}</div>
                <div class="s">{{ translate('All statuses') }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <form class="mf-filters w-100" method="GET" action="{{ route('admin.store.vendor-payouts') }}">
                    <select class="form-control" name="status" style="max-width: 220px;">
                        <option value="all" {{ ($status ?? 'all')==='all'?'selected':'' }}>{{ translate('All') }}</option>
                        <option value="pending" {{ ($status ?? 'all')==='pending'?'selected':'' }}>{{ translate('Pending') }}</option>
                        <option value="approved" {{ ($status ?? 'all')==='approved'?'selected':'' }}>{{ translate('Approved') }}</option>
                        <option value="denied" {{ ($status ?? 'all')==='denied'?'selected':'' }}>{{ translate('Denied') }}</option>
                    </select>

                    <input class="form-control" style="min-width: 240px;max-width: 420px;" name="search" value="{{ $search ?? '' }}" placeholder="{{ translate('ex_:_search_store_name') }}">

                    <button class="btn btn--primary" type="submit"><i class="tio-search"></i> {{ translate('Search') }}</button>
                    <a class="btn btn--reset" href="{{ route('admin.store.vendor-payouts') }}">{{ translate('Reset') }}</a>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-thead-bordered table-nowrap card-table mf-table mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('Store') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Method') }}</th>
                            <th>{{ translate('Request time') }}</th>
                            <th>{{ translate('Status') }}</th>
                            <th>{{ translate('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($withdraw_req as $k => $wr)
                            @php($store = $wr->vendor?->stores?->first())
                            <tr>
                                <td>{{ $k + $withdraw_req->firstItem() }}</td>
                                <td>
                                    @if($store)
                                        <a class="text--title" href="{{ route('admin.store.view', [$store->id, 'module_id' => $store->module_id]) }}">
                                            {{ \Illuminate\Support\Str::limit($store->name, 28) }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ \App\CentralLogics\Helpers::format_currency($wr->amount) }}</td>
                                <td>{{ $wr->method?->method_name ?? '—' }}</td>
                                <td>{{ $wr->created_at?->format('d M Y, h:i A') }}</td>
                                <td>
                                    @if((int) $wr->approved === 1)
                                        <span class="mf-chip approved">{{ translate('Approved') }}</span>
                                    @elseif((int) $wr->approved === 2)
                                        <span class="mf-chip denied">{{ translate('Denied') }}</span>
                                    @else
                                        <span class="mf-chip pending">{{ translate('Pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($wr->vendor && $store)
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.store.withdraw_view', [$wr->id, $wr->vendor_id]) }}">
                                            {{ translate('View') }}
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">{{ translate('No data found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($withdraw_req->hasPages())
                <div class="card-footer">
                    {!! $withdraw_req->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection

