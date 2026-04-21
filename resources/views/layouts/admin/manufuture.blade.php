<!DOCTYPE html>
<?php
$country= \App\CentralLogics\Helpers::get_business_settings('country');
$countryCode= strtolower($country?$country:'auto');
?>
<html dir="{{ session()->get('site_direction') }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{session()->get('site_direction') === 'rtl'?'active':'' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @php($logo=\App\Models\BusinessSetting::where(['key'=>'icon'])->first())
    <link rel="icon" type="image/x-icon" href="{{\App\CentralLogics\Helpers::get_full_url('business', $logo?->value?? '', $logo?->storage[0]?->value ?? 'public','favicon')}}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/vendor/icon-set/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/custom.css')}}">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/owl.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/bootstrap-tour-standalone.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/emogi-area.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/intltelinput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/upload-single-image.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/manufuture.css') }}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/toastr.css')}}">

    @stack('css_or_js')
    <script src="{{asset('assets/admin/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor.min.js')}}"></script>
    <script>
        if (typeof jQuery === 'undefined') {
            document.write('<script src=\"https://code.jquery.com/jquery-3.5.1.min.js\"><\\/script>');
        }
    </script>
</head>
<body class="mf-body footer-offset">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="loading" class="initial-hidden">
                    <div class="loader--inner">
                        <img width="200" src="{{asset('assets/admin/img/loader.gif')}}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mf-app">
        <aside class="mf-sidebar" id="mf-sidebar">
            <div class="mf-logo">
                <div class="mf-logo-icon">🏪</div>
                <div>
                    <div class="mf-logo-name">Manufuture</div>
                    <div class="mf-logo-sub">ADMIN PORTAL</div>
                </div>
            </div>

            <nav class="mf-nav">
                <div class="mf-nav-label">Overview</div>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.dashboard') ? 'active' : '' }}" href="{{ route('admin.mf.dashboard', array_filter(['module_id' => config('module.current_module_id')])) }}">Dashboard</a>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.aipulse') ? 'active' : '' }}" href="{{ route('admin.mf.aipulse', array_filter(['module_id' => config('module.current_module_id')])) }}">AI Pulse</a>
                <div class="mf-nav-label">Commerce</div>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.orders.*') ? 'active' : '' }}" href="{{ route('admin.mf.orders.index', array_filter(['module_id' => config('module.current_module_id')])) }}">Orders</a>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.products.*') ? 'active' : '' }}" href="{{ route('admin.mf.products.index', array_filter(['module_id' => config('module.current_module_id')])) }}">Products</a>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.pos') ? 'active' : '' }}" href="{{ route('admin.mf.pos', array_filter(['module_id' => config('module.current_module_id')])) }}">POS</a>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.returns') ? 'active' : '' }}" href="{{ route('admin.mf.returns', array_filter(['module_id' => config('module.current_module_id')])) }}">Returns &amp; RMA</a>
                <div class="mf-nav-label">Insights</div>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.omnichannel') ? 'active' : '' }}" href="{{ route('admin.mf.omnichannel', array_filter(['module_id' => config('module.current_module_id')])) }}">Omnichannel</a>
                <a class="mf-nav-item {{ request()->routeIs('admin.mf.helpcenter') ? 'active' : '' }}" href="{{ route('admin.mf.helpcenter', array_filter(['module_id' => config('module.current_module_id')])) }}">Help Center</a>
            </nav>

            <div class="mf-sidebar-footer">
                <div class="mf-user-pill">
                    <div class="mf-avatar">{{ strtoupper(substr(auth('admin')->user()->f_name ?? 'A', 0, 1)) }}</div>
                    <div>
                        <div class="mf-user-name">{{ auth('admin')->user()->f_name ?? '' }}</div>
                        <div class="mf-user-role">{{ translate('messages.admin') }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <main class="mf-main">
            <header class="mf-topbar">
                <div class="mf-topbar-left">
                    <button class="mf-icon-btn" type="button" id="mf-sidebar-toggle">☰</button>
                    <div class="mf-breadcrumb">
                        <span class="mf-bc-current">@yield('page_title', translate('messages.dashboard'))</span>
                    </div>
                </div>
                <div class="mf-topbar-right">
                    @php($mfModules = \App\Models\Module::active()->get(['id','module_name']))
                    @if($mfModules->count() > 1)
                        <div style="min-width:180px">
                            <select id="mf-module-switcher" class="form-control" style="height:34px;padding:0 10px">
                                @foreach($mfModules as $m)
                                    <option value="{{ $m->id }}" {{ (string)$m->id === (string)config('module.current_module_id') ? 'selected' : '' }}>
                                        {{ $m->module_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="mf-search">
                        <span class="mf-search-ico">🔍</span>
                        <input type="text" placeholder="Search…" />
                    </div>
                </div>
            </header>

            <section class="mf-content">
                @yield('content')
            </section>
        </main>
    </div>

    <script src="{{ asset('assets/admin/js/manufuture.js') }}"></script>
    <script>
        (function () {
            const sel = document.getElementById('mf-module-switcher');
            if (!sel) return;

            sel.addEventListener('change', function () {
                const url = new URL(window.location.href);
                url.searchParams.set('module_id', sel.value);
                window.location.href = url.toString();
            });
        })();
    </script>
    @stack('script')
    @stack('script_2')
</body>
</html>

