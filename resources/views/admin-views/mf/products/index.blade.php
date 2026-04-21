@extends('layouts.admin.manufuture')

@section('title', translate('messages.items'))
@section('page_title', translate('messages.items'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.items') }}</h1>
            <p>{{ translate('Manage your products with the existing system (embedded inside Manufuture).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick actions') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.item.list') }}" target="mf-products-frame">{{ translate('Open list') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.item.add-new') }}" target="mf-products-frame">{{ translate('Add new') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.item.bulk-import') }}" target="mf-products-frame">{{ translate('Bulk import') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.item.bulk-export-index') }}" target="mf-products-frame">{{ translate('Bulk export') }}</a>

                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-products-frame',
        'frameName' => 'mf-products-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection
