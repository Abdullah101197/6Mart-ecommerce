@extends('layouts.vendor.app')

@section('title', translate('Returns'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">{{ translate('Returns') }} #{{ $refund->id }}</h1>
            <div class="page-header-select-wrapper">
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('vendor.returns') }}">{{ translate('messages.back') }}</a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">{{ translate('messages.order') }} ID</dt>
                    <dd class="col-sm-9">{{ $refund->order_id }}</dd>
                    <dt class="col-sm-3">{{ translate('messages.amount') }}</dt>
                    <dd class="col-sm-9">{{ \App\CentralLogics\Helpers::format_currency($refund->refund_amount) }}</dd>
                    <dt class="col-sm-3">{{ translate('messages.status') }}</dt>
                    <dd class="col-sm-9">{{ $refund->refund_status }}</dd>
                    <dt class="col-sm-3">{{ translate('messages.method') }}</dt>
                    <dd class="col-sm-9">{{ $refund->refund_method }}</dd>
                    <dt class="col-sm-3">{{ translate('messages.customer') }} {{ translate('messages.note') }}</dt>
                    <dd class="col-sm-9">{{ $refund->customer_note ?: '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
