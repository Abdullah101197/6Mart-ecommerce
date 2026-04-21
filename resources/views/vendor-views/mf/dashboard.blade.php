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

