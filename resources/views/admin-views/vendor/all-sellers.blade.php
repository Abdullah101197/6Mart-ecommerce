@extends('layouts.admin.app')

@section('title', translate('All Registered Sellers'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .mf-as-wrap { background: #f4f7fb; margin: -1rem -1.5rem 0; padding: 1.25rem 1.5rem 2rem; }
        .mf-as-kpis { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 18px; }
        .mf-as-kpi { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; border-left: 4px solid #0d9488; }
        .mf-as-kpi .lbl { font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #64748b; }
        .mf-as-kpi .val { font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1.1; margin-top: 4px; }
        .mf-as-kpi .sub { font-size: 12px; color: #64748b; margin-top: 4px; }
        .mf-tab { display: inline-flex; align-items: center; padding: 6px 14px; border-radius: 999px; font-size: 12px; font-weight: 600; border: 1px solid #e2e8f0; color: #334155; background: #fff; }
        .mf-tab.on { background: #0d9488; border-color: #0d9488; color: #fff; }
        .mf-as-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; }
        .mf-as-card h5 { font-size: 11px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #64748b; margin-bottom: 14px; }
        .store-cell-link { text-decoration: none; color: inherit; display: flex; align-items: center; gap: 10px; }
        .store-cell-link:hover .text--title { color: #0d9488; }
        .mf-as-filters{display:flex;flex-wrap:wrap;gap:10px;align-items:center}
        .mf-as-filters .form-control{height:40px;border-radius:12px}
        .mf-as-filters .btn{height:40px;border-radius:12px;font-weight:900}
    </style>
@endpush

@section('content')
    <div class="content container-fluid mf-as-wrap">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h1 class="h3 mb-1 font-weight-bold text-dark">{{ translate('All Registered Sellers') }}</h1>
                <div class="text-muted small">{{ translate('Seller list with performance metrics') }}</div>
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.list') }}">{{ translate('messages.stores') }} {{ translate('list') }}</a>
                <a class="btn btn-sm btn-primary" href="{{ route('admin.store.add') }}">+ {{ translate('messages.add_store') }}</a>
            </div>
        </div>

        <div class="mf-as-kpis">
            <div class="mf-as-kpi" style="border-left-color:#0d9488">
                <div class="lbl">{{ translate('Total sellers') }}</div>
                <div class="val">{{ $totalSellers }}</div>
                <div class="sub">{{ translate('messages.stores') }}</div>
            </div>
            <div class="mf-as-kpi" style="border-left-color:#f59e0b">
                <div class="lbl">{{ translate('Pending') }}</div>
                <div class="val">{{ $pendingCount }}</div>
                <div class="sub">{{ translate('messages.pending') }}</div>
            </div>
            <div class="mf-as-kpi" style="border-left-color:#22c55e">
                <div class="lbl">{{ translate('Approved') }}</div>
                <div class="val">{{ $approvedCount }}</div>
                <div class="sub">{{ translate('messages.active_stores') }}</div>
            </div>
            <div class="mf-as-kpi" style="border-left-color:#94a3b8">
                <div class="lbl">{{ translate('Suspended') }}</div>
                <div class="val">{{ $suspendedCount }}</div>
                <div class="sub">{{ translate('messages.inactive_stores') }}</div>
            </div>
            <div class="mf-as-kpi" style="border-left-color:#5566ff">
                <div class="lbl">{{ translate('Avg products/seller') }}</div>
                <div class="val">{{ number_format((float) ($avgProductsPerSeller ?? 0), 1) }}</div>
                <div class="sub">{{ translate('messages.products') }}</div>
            </div>
        </div>

        <div class="mf-as-card mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div class="d-flex flex-wrap" style="gap:8px">
                    <a class="mf-tab {{ ($status ?? 'all') === 'all' ? 'on' : '' }}" href="{{ route('admin.store.all-sellers', array_filter(['status' => 'all'])) }}">{{ translate('All Status') }}</a>
                    <a class="mf-tab {{ ($status ?? 'all') === 'approved' ? 'on' : '' }}" href="{{ route('admin.store.all-sellers', array_filter(['status' => 'approved'])) }}">{{ translate('Approved') }}</a>
                    <a class="mf-tab {{ ($status ?? 'all') === 'pending' ? 'on' : '' }}" href="{{ route('admin.store.all-sellers', array_filter(['status' => 'pending'])) }}">{{ translate('Pending') }}</a>
                    <a class="mf-tab {{ ($status ?? 'all') === 'suspended' ? 'on' : '' }}" href="{{ route('admin.store.all-sellers', array_filter(['status' => 'suspended'])) }}">{{ translate('Suspended') }}</a>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.store.all-sellers') }}" class="mf-as-filters">
                @foreach (array_filter(request()->only(['status', 'zone_id', 'type', 'module_id'])) as $gk => $gv)
                    <input type="hidden" name="{{ $gk }}" value="{{ $gv }}">
                @endforeach
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" style="min-width:220px;max-width:320px" placeholder="{{ translate('Search sellers...') }}">
                <select name="vendor_type" class="form-control js-select2-custom" style="min-width:180px;max-width:240px">
                    <option value="">{{ translate('All Vendor Types') }}</option>
                    <option value="shopkeeper" {{ request('vendor_type') === 'shopkeeper' ? 'selected' : '' }}>{{ translate('Shopkeeper') }}</option>
                    <option value="manufacturer" {{ request('vendor_type') === 'manufacturer' ? 'selected' : '' }}>{{ translate('Manufacturer') }}</option>
                    <option value="wholesale" {{ request('vendor_type') === 'wholesale' ? 'selected' : '' }}>{{ translate('Wholesale Vendor') }}</option>
                    <option value="b2b" {{ request('vendor_type') === 'b2b' ? 'selected' : '' }}>{{ translate('B2B Vendor') }}</option>
                </select>
                <button type="submit" class="btn btn--secondary">{{ translate('messages.search') }}</button>
            </form>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap card-table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>{{ translate('Seller') }}</th>
                                <th>{{ translate('messages.store_information') }}</th>
                                <th>{{ translate('messages.email') }}</th>
                                <th>{{ translate('messages.products') }}</th>
                                <th>{{ translate('Revenue') }}</th>
                                <th>{{ translate('Commission') }} %</th>
                                <th>{{ translate('messages.rating') }}</th>
                                <th>{{ translate('messages.status') }}</th>
                                <th class="text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stores as $store)
                                @php
                                    $v = $store->vendor;
                                    $rev = (float) ($store->rms_store_revenue ?? 0);
                                    $commSum = (float) ($store->rms_store_commission_sum ?? 0);
                                    $pct = $rev + $commSum > 0 ? round(($commSum / max(0.0001, $rev + $commSum)) * 100, 1) : (float) ($store->comission ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.store.view', $store->id) }}" class="store-cell-link">
                                            <img class="img--50 circle onerror-image" data-onerror-image="{{ asset('assets/admin/img/160x160/img1.jpg') }}"
                                                src="{{ $store['logo_full_url'] ?? asset('assets/admin/img/160x160/img1.jpg') }}" alt="">
                                            <div>
                                                <div class="text--title font-weight-bold">{{ \Illuminate\Support\Str::limit(trim(($v->f_name ?? '') . ' ' . ($v->l_name ?? '')), 28) }}</div>
                                                <div class="font-size-sm text-muted">{{ translate('messages.rating') }}: {{ number_format((float) ($store->rating ?? 0), 1) }} · {{ translate('messages.joined') }} {{ optional($store->created_at)->format('M d, Y') }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.store.view', $store->id) }}" class="font-weight-semibold text-dark">{{ \Illuminate\Support\Str::limit($store->name, 32) }}</a>
                                    </td>
                                    <td><span class="font-size-sm">{{ $store->email ?? ($v->email ?? '—') }}</span></td>
                                    <td>{{ (int) ($store->items_count ?? 0) }}</td>
                                    <td class="font-weight-bold">{{ \App\CentralLogics\Helpers::format_currency($rev) }}</td>
                                    <td>{{ $pct }}%</td>
                                    <td>★ {{ number_format((float) ($store->rating ?? 0), 1) }}</td>
                                    <td>
                                        @if (isset($v->status) && $v->status === 1 && $store->status)
                                            <span class="badge badge-soft-success">{{ translate('Approved') }}</span>
                                        @elseif ($v && $v->status === null)
                                            <span class="badge badge-soft-dark">{{ translate('Pending') }}</span>
                                        @else
                                            <span class="badge badge-soft-secondary">{{ translate('Suspended') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.store.view', $store->id) }}">{{ translate('messages.view') }}</a>
                                            @if (isset($v->status) && $v->status)
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.store.status', [$store->id, $store->status ? 0 : 1]) }}">{{ $store->status ? translate('Suspend') : translate('Activate') }}</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">{{ translate('no_data_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($stores->total() > 0)
                    <div class="card-footer py-3">{!! $stores->withQueryString()->links() !!}</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>
@endpush
