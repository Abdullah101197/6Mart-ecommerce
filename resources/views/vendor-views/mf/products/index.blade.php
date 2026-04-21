@extends('layouts.vendor.manufuture')

@section('title', translate('messages.items'))
@section('page_title', translate('messages.items'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.items') }}</h1>
            <p>{{ translate('Manage products using your existing vendor items system (embedded).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick actions') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('vendor.item.list') }}" target="mf-vendor-products-frame">{{ translate('Open list') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.item.add-new') }}" target="mf-vendor-products-frame">{{ translate('Add new') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.item.bulk-import') }}" target="mf-vendor-products-frame">{{ translate('Bulk import') }}</a>
                <a class="btn btn--primary" href="{{ route('vendor.item.bulk-export-index') }}" target="mf-vendor-products-frame">{{ translate('Bulk export') }}</a>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-vendor-products-frame',
        'frameName' => 'mf-vendor-products-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection
