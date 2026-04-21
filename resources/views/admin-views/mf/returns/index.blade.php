@extends('layouts.admin.manufuture')

@section('title', translate('Returns & RMA'))
@section('page_title', translate('Returns & RMA'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('Returns & RMA') }}</h1>
            <p>{{ translate('Refund/return management using your existing system (embedded inside Manufuture).') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Quick filters') }}</div>
        </div>
        <div class="mf-card-body">
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
                <a class="btn btn--primary" href="{{ route('admin.mf.returns', array_filter(['status' => 'pending', 'module_id' => $moduleId])) }}">{{ translate('Pending') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.mf.returns', array_filter(['status' => 'approved', 'module_id' => $moduleId])) }}">{{ translate('Approved') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.mf.returns', array_filter(['status' => 'rejected', 'module_id' => $moduleId])) }}">{{ translate('Rejected') }}</a>
                <a class="btn btn--primary" href="{{ route('admin.refund.refund_settings') }}" target="mf-returns-frame">{{ translate('Settings') }}</a>
                <div class="mf-muted" style="margin-left:auto">
                    {{ translate('Status:') }} <code>{{ $status }}</code> —
                    {{ translate('Module:') }} <code>{{ $moduleId ?? 'current' }}</code>
                </div>
            </div>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-returns-frame',
        'frameName' => 'mf-returns-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection

