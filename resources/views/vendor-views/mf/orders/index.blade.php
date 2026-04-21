@extends('layouts.vendor.manufuture')

@section('title', translate('messages.orders'))
@section('page_title', translate('messages.orders'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.orders') }}</h1>
            <p>{{ translate('Your existing vendor orders page inside Manufuture (fully functional).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick filters') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('vendor.mf.orders', ['status' => 'all']) }}">{{ translate('All') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.mf.orders', ['status' => 'processing']) }}">{{ translate('Processing') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.mf.orders', ['status' => 'delivered']) }}">{{ translate('Delivered') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.mf.orders', ['status' => 'canceled']) }}">{{ translate('Canceled') }}</a>
                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Status:') }} <code>{{ $status }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-vendor-orders-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection
