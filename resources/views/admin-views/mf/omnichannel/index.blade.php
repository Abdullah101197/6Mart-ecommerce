@extends('layouts.admin.manufuture')

@section('title', 'Omnichannel')
@section('page_title', 'Omnichannel')

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>Omnichannel</h1>
            <p>{{ translate('Campaigns + messaging configuration using your existing admin tools (embedded).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick actions') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.campaign.list', ['basic']) }}" target="mf-omnichannel-frame">{{ translate('Campaigns (Basic)') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.fcm-config') }}" target="mf-omnichannel-frame">FCM</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.third-party.sms-module') }}" target="mf-omnichannel-frame">SMS</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.third-party.mail-config') }}" target="mf-omnichannel-frame">{{ translate('Mail') }}</a>

                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Type:') }} <code>{{ $type }}</code> —
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-omnichannel-frame',
        'frameName' => 'mf-omnichannel-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection

