@extends('layouts.admin.manufuture')

@section('title', translate('messages.pos'))
@section('page_title', translate('messages.pos'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.pos') }}</h1>
            <p>{{ translate('Your existing admin POS inside Manufuture (fully functional).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick actions') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.pos.index') }}" target="mf-pos-frame">{{ translate('Open POS') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.pos.customers') }}" target="mf-pos-frame">{{ translate('Customers') }}</a>
                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-pos-frame',
        'frameName' => 'mf-pos-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection
