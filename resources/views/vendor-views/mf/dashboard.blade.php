@extends('layouts.vendor.manufuture')

@section('title', translate('messages.dashboard'))
@section('page_title', translate('messages.dashboard'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.welcome') }}, {{ auth('vendor')->user()->f_name }}</h1>
            <p>{{ translate('Overview of your store orders (live data).') }}</p>
        </div>
    </div>

    <div class="row g-2 mt-2">
        <div class="col-md-3 col-sm-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Sales Today') }}</div>
                </div>
                <div class="mf-card-body">
                    <div style="font-size:22px;font-weight:800">
                        {{ \App\CentralLogics\Helpers::format_currency($kpis['sales_today'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Sales This Month') }}</div>
                </div>
                <div class="mf-card-body">
                    <div style="font-size:22px;font-weight:800">
                        {{ \App\CentralLogics\Helpers::format_currency($kpis['sales_this_month'] ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Total Items') }}</div>
                </div>
                <div class="mf-card-body">
                    <div style="font-size:22px;font-weight:800">{{ $kpis['items_total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Out of Stock') }}</div>
                </div>
                <div class="mf-card-body">
                    <div style="font-size:22px;font-weight:800">{{ $kpis['items_out_of_stock'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Order stats') }}</div>
            <div>
                <select class="custom-select border-0 text-center w-auto" name="statistics" id="vendor-statistics">
                    <option value="overall" {{ ($params['statistics_type'] ?? 'overall') === 'overall' ? 'selected' : '' }}>{{ translate('messages.Overall') }}</option>
                    <option value="today" {{ ($params['statistics_type'] ?? 'overall') === 'today' ? 'selected' : '' }}>{{ translate('messages.today') }}</option>
                    <option value="this_month" {{ ($params['statistics_type'] ?? 'overall') === 'this_month' ? 'selected' : '' }}>{{ translate('messages.this_month') }}</option>
                </select>
            </div>
        </div>
        <div class="mf-card-body">
            <div class="row g-2" id="vendor-order-stats">
                @include('vendor-views.partials._dashboard-order-stats', ['data' => $data])
            </div>
        </div>
    </div>

    <div class="row g-2" style="margin-top:14px">
        <div class="col-lg-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Top Selling Items') }}</div>
                </div>
                <div class="mf-card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                            <tr>
                                <th>{{ translate('messages.item') }}</th>
                                <th class="text-right">{{ translate('messages.orders') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(($top_sell ?? []) as $item)
                                <tr>
                                    <td class="text-dark">{{ \Illuminate\Support\Str::limit($item->name ?? '', 35) }}</td>
                                    <td class="text-right">{{ (int)($item->order_count ?? 0) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">{{ translate('no_data_found') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="mf-card">
                <div class="mf-card-header">
                    <div class="mf-card-title">{{ translate('Most Rated Items') }}</div>
                </div>
                <div class="mf-card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                            <tr>
                                <th>{{ translate('messages.item') }}</th>
                                <th class="text-right">{{ translate('messages.rating') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse(($most_rated_items ?? []) as $item)
                                <tr>
                                    <td class="text-dark">{{ \Illuminate\Support\Str::limit($item->name ?? '', 35) }}</td>
                                    <td class="text-right">{{ number_format((float)($item->avg_rating ?? 0), 1) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center text-muted">{{ translate('no_data_found') }}</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        $('#vendor-statistics').on('change', function () {
            const type = $(this).val();
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.post({
                url: '{{ route('vendor.dashboard.order-stats') }}',
                data: { statistics_type: type },
                beforeSend: function () { $('#loading').show() },
                success: function (data) { $('#vendor-order-stats').html(data.view) },
                complete: function () { $('#loading').hide() }
            });
        });
    </script>
@endpush

