@extends('layouts.admin.app')

@section('title', translate('Pending Sellers'))

@push('css_or_js')
    <style>
        .mf-ps{background:#f4f7fb;margin:-1rem -1.5rem 0;padding:1.25rem 1.5rem 2rem}
        .mf-ps .mf-strip{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;margin-bottom:14px}
        @media(max-width:991.98px){.mf-ps .mf-strip{grid-template-columns:1fr}}
        .mf-ps .k{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:14px 16px}
        .mf-ps .k .t{font-size:10px;font-weight:900;color:#64748b;letter-spacing:.1em;text-transform:uppercase}
        .mf-ps .k .v{font-size:26px;font-weight:900;color:#0f172a;margin-top:6px}
        .mf-ps .cardx{background:#fff;border:1px solid #e5e7eb;border-radius:14px}
        .mf-ps .cardx-h{padding:14px 16px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .mf-ps .cardx-h .tt{font-size:12px;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:#64748b}
        .mf-ps .app{padding:14px 16px;border-bottom:1px solid #eef2f7;background:#fff7ed}
        .mf-ps .app:last-child{border-bottom:0}
        .mf-ps .app .name{font-weight:900;color:#0f172a}
        .mf-ps .app .meta{font-size:12px;color:#64748b;font-weight:800;margin-top:4px}
        .mf-ps .chip{display:inline-flex;align-items:center;border-radius:999px;padding:3px 10px;font-size:11px;font-weight:900;border:1px solid #e2e8f0;background:#fff}
        .mf-ps .chip.warn{border-color:#fed7aa;background:#fffbeb;color:#b45309}
        .mf-ps .chip.ok{border-color:#bbf7d0;background:#ecfdf5;color:#16a34a}
        .mf-ps .chip.blue{border-color:#bfdbfe;background:#eff6ff;color:#2563eb}
        .mf-ps .btn{font-weight:900}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-ps">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1 font-weight-bold text-dark">{{ translate('Pending Sellers') }}</h1>
                <div class="text-muted small">{{ translate('Seller applications — pending review') }}</div>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.all-sellers') }}">{{ translate('All Sellers') }}</a>
        </div>

        <div class="mf-strip">
            <div class="k">
                <div class="t">{{ translate('Pending Review') }}</div>
                <div class="v">{{ (int) ($pendingReview ?? 0) }}</div>
                <div class="text-muted small">{{ translate('Awaiting approval') }}</div>
            </div>
            <div class="k">
                <div class="t">{{ translate('Documents Submitted') }}</div>
                <div class="v">{{ (int) ($documentsSubmitted ?? 0) }}</div>
                <div class="text-muted small">{{ translate('Applications with details') }}</div>
            </div>
            <div class="k">
                <div class="t">{{ translate('Avg Review Time') }}</div>
                <div class="v">{{ number_format((float) ($avgReviewDays ?? 0), 1) }}d</div>
                <div class="text-muted small">{{ translate('Rolling average') }}</div>
            </div>
        </div>

        <div class="cardx">
            <div class="cardx-h">
                <div class="tt">{{ translate('Seller Applications — Pending Review') }}</div>
                <form method="get" class="d-flex gap-2 flex-wrap align-items-center">
                    <input type="search" name="search_by" value="{{ request('search_by') }}" class="form-control" style="min-width:260px;max-width:340px" placeholder="{{ translate('Search store / owner / phone / email') }}">
                    <button class="btn btn--primary" type="submit">{{ translate('Search') }}</button>
                </form>
            </div>

            @forelse($stores as $store)
                <div class="app">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div>
                            <div class="name">{{ $store->name }}</div>
                            <div class="meta">
                                <span class="text-muted">{{ $store->vendor?->email ?? $store->email ?? 'email protected' }}</span>
                                <span class="mx-2">·</span>
                                <span class="text-muted">{{ translate('Applied') }} {{ optional($store->created_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="d-flex flex-wrap mt-2" style="gap:8px">
                                @php($docsOk = !empty($store->email) || !empty($store->vendor?->email))
                                @php($gstPending = method_exists($store, 'getGstStatusAttribute') ? !$store->gst_status : false)

                                @if($gstPending)
                                    <span class="chip warn">{{ translate('GST Verification Pending') }}</span>
                                @else
                                    <span class="chip {{ $docsOk ? 'ok' : 'warn' }}">{{ $docsOk ? translate('Documents Verified') : translate('Documents Pending') }}</span>
                                @endif

                                <span class="chip blue">{{ \Illuminate\Support\Str::limit($store->module?->module_name ?? translate('Category'), 18) }}</span>
                                <span class="chip ok">{{ (int) ($store->items_count ?? 0) }}+ {{ translate('products planned') }}</span>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center" style="gap:8px">
                            <a class="btn btn-sm btn-white" href="{{ route('admin.store.view', $store->id) }}">{{ translate('View Docs') }}</a>
                            <a class="btn btn-sm btn-outline-danger" href="{{ route('admin.store.application', [$store->id, 0]) }}">{{ translate('Reject') }}</a>
                            <a class="btn btn-sm btn--primary" href="{{ route('admin.store.application', [$store->id, 1]) }}">{{ translate('Approve') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-muted">{{ translate('no_data_found') }}</div>
            @endforelse

            @if($stores->total() > 0)
                <div class="p-3">
                    {!! $stores->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection

