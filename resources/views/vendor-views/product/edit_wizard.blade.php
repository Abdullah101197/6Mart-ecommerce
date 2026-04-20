@extends('layouts.vendor.app')

@section('title', translate('messages.update_item'))

@section('content')
  @php
    $module_type = \App\CentralLogics\Helpers::get_store_data()->module->module_type;
    \Illuminate\Support\Facades\Config::set('module.current_module_type', $module_type);
  @endphp

  @include('partials.product._wizard', [
    'npWizardContext' => 'vendor',
    'product' => $product ?? null,
  ])
@endsection

