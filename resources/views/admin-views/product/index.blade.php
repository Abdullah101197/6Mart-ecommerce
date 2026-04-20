@extends('layouts.admin.app')
@section('title', translate('messages.add_new_item'))
@section('content')
  @include('partials.product._wizard', [
    'npWizardContext' => $npWizardContext ?? 'admin',
    'product' => $product ?? null,
  ])
@endsection