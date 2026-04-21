@extends('layouts.vendor.manufuture')

@section('title', translate('messages.pos'))
@section('page_title', translate('messages.pos'))

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>{{ translate('messages.pos') }}</h1>
            <p>{{ translate('Your existing vendor POS page inside Manufuture (fully functional).') }}</p>
        </div>
    </div>

    @include('partials.mf.embedded-frame', [
        'frameId' => 'mf-vendor-pos-frame',
        'frameName' => 'mf-vendor-pos-frame',
        'frameSrc' => $src,
        'frameOffset' => 220,
    ])
@endsection

