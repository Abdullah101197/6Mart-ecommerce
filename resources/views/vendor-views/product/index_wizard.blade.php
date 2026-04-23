@extends('layouts.vendor.app')

@section('title', translate('messages.add_new_item'))

@section('content')
  @php
    $module_type = \App\CentralLogics\Helpers::get_store_data()->module->module_type;
    \Illuminate\Support\Facades\Config::set('module.current_module_type', $module_type);
    $npWizardContext = 'vendor';
    $store_data = \App\CentralLogics\Helpers::get_store_data();
    $sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application;
    $allowAll = ($store_data?->store_business_model ?? null) === 'commission';
    $productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1);
  @endphp

  @if($productsRmsUi)
    @push('css_or_js')
      <style>
        .mf-products{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
        .mf-products .mf-welcome{
          background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
          border-radius:14px;padding:18px 20px;color:#fff;position:relative;overflow:hidden;margin:14px 0
        }
        .mf-products .mf-welcome h1{font-size:16px;font-weight:900;margin:0;color:#fff}
        .mf-products .mf-welcome p{font-size:12px;opacity:.82;margin:6px 0 0;color:#fff}
      </style>
    @endpush
    <div class="mf-products">
      <div class="mf-welcome">
        <h1>{{ translate('messages.add_new_item') }}</h1>
        <p>{{ translate('Create item with RMS v12 UI') }}</p>
      </div>
    </div>
  @endif

  @include('partials.product._wizard', ['npWizardContext' => 'vendor'])
@endsection

