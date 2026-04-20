@extends('layouts.vendor.app')

@section('title', translate('messages.add_new_item'))

@section('content')
  @php
    $module_type = \App\CentralLogics\Helpers::get_store_data()->module->module_type;
    \Illuminate\Support\Facades\Config::set('module.current_module_type', $module_type);
    $npWizardContext = 'vendor';
  @endphp

  @include('partials.product._wizard', ['npWizardContext' => 'vendor'])
@endsection

