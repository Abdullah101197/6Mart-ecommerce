@extends('layouts.vendor.manufuture')

@section('title', 'Manufuture · ' . ucfirst($feature))
@section('page_title', ucfirst($feature))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ ucfirst($feature) }}</h1>
            <p>{{ translate('Coming soon in Manufuture vendor portal.') }}</p>
        </div>
    </div>

    <div class="mf-card" style="margin-top:14px">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Status') }}</div>
        </div>
        <div class="mf-card-body">
            <div class="mf-muted">
                {{ translate('This section will be implemented after core parity.') }}
            </div>
        </div>
    </div>
@endsection

