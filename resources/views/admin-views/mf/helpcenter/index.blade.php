@extends('layouts.admin.manufuture')

@section('title', 'Help Center')
@section('page_title', 'Help Center')

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>Help Center</h1>
            <p>{{ translate('Customer contact messages and FAQs using your existing admin tools (embedded).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick actions') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.users.contact.contact-list') }}" target="mf-helpcenter-frame">{{ translate('Contact messages') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.landing-page-settings', ['faq']) }}" target="mf-helpcenter-frame">FAQ</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.terms-and-conditions') }}" target="mf-helpcenter-frame">{{ translate('Terms') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.business-settings.privacy-policy') }}" target="mf-helpcenter-frame">{{ translate('Privacy') }}</a>

                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-helpcenter-frame',
        'frameName' => 'mf-helpcenter-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection

