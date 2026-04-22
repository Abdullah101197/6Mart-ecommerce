@extends('layouts.vendor.app')

@section('title', translate('Coming Soon'))

@section('content')
    <div class="content container-fluid">
        <div class="card">
            <div class="card-body text-center py-5">
                <h3 class="mb-2">{{ translate('Coming Soon') }}</h3>
                <p class="text-muted mb-0">
                    {{ translate('This module is not available yet.') }}
                    @if(!empty($feature))
                        <span class="d-block mt-1">{{ translate('Module') }}: <strong>{{ ucfirst($feature) }}</strong></span>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection

