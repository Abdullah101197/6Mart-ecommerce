@extends('layouts.admin.manufuture')

@section('title', translate('messages.dashboard'))
@section('page_title', translate('messages.dashboard'))

@section('content')
    <div class="mf-card">
        <div class="mf-card-header">
            <div class="mf-card-title">{{ translate('Not migrated yet') }}</div>
        </div>
        <div class="mf-card-body">
            <div class="mf-muted">
                {{ translate('This module dashboard is not migrated to Manufuture yet.') }}
                {{ translate('Current module type:') }} <code>{{ $module_type ?? 'unknown' }}</code>
            </div>
        </div>
    </div>
@endsection

