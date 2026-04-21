@extends('layouts.admin.manufuture')

@section('title', translate('messages.orders'))
@section('page_title', translate('messages.orders'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.orders') }}</h1>
            <p>{{ translate('Orders list inside Manufuture portal (uses your existing order management).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick filters') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.mf.orders.index', array_filter(['status'=>'all','module_id'=>$moduleId])) }}">{{ translate('All') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.mf.orders.index', array_filter(['status'=>'processing','module_id'=>$moduleId])) }}">{{ translate('Processing') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.mf.orders.index', array_filter(['status'=>'delivered','module_id'=>$moduleId])) }}">{{ translate('Delivered') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.mf.orders.index', array_filter(['status'=>'canceled','module_id'=>$moduleId])) }}">{{ translate('Canceled') }}</a>

                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-orders-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection

