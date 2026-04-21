<!DOCTYPE html>
<?php
    $storeId = \App\CentralLogics\Helpers::get_store_id();
    $store = $storeId ? \App\Models\Store::find($storeId) : null;
?>
<html dir="{{ session()->has('vendor_site_direction') ? session()->get('vendor_site_direction') : 'ltr' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @php($logo = \App\Models\BusinessSetting::where(['key'=>'icon'])->first())
    <link rel="icon" type="image/x-icon" href="{{\App\CentralLogics\Helpers::get_full_url('business', $logo?->value?? '', $logo?->storage[0]?->value ?? 'public','favicon')}}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('assets/admin/css/emogi-area.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/intltelinput/css/intlTelInput.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/css/owl.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/manufuture.css') }}">
    <link rel="stylesheet" href="{{asset('assets/admin')}}/css/toastr.css">
    @stack('css_or_js')
    <script src="{{asset('assets/admin')}}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js"></script>
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
                    <div class="loading-inner">
                        <img width="200" src="{{asset('assets/admin/img/loader.gif')}}">
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
                    <div class="mf-logo-sub">VENDOR PORTAL</div>
                </div>
            </div>

            <nav class="mf-nav">
                <div class="mf-nav-label">Overview</div>
                <a class="mf-nav-item {{ request()->routeIs('vendor.mf.dashboard') ? 'active' : '' }}" href="{{ route('vendor.mf.dashboard') }}">Dashboard</a>
                <div class="mf-nav-label">Store</div>
                <a class="mf-nav-item {{ request()->routeIs('vendor.mf.orders') ? 'active' : '' }}" href="{{ route('vendor.mf.orders') }}">Orders</a>
                <a class="mf-nav-item {{ request()->routeIs('vendor.mf.products') ? 'active' : '' }}" href="{{ route('vendor.mf.products') }}">Products</a>
                <a class="mf-nav-item {{ request()->routeIs('vendor.mf.pos') ? 'active' : '' }}" href="{{ route('vendor.mf.pos') }}">POS</a>
            </nav>

            <div class="mf-sidebar-footer">
                <div class="mf-user-pill">
                    <div class="mf-avatar">{{ strtoupper(substr(auth('vendor')->user()->f_name ?? 'V', 0, 1)) }}</div>
                    <div>
                        <div class="mf-user-name">{{ auth('vendor')->user()->f_name ?? '' }}</div>
                        <div class="mf-user-role">{{ $store?->name ?? translate('messages.store') }}</div>
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
    @stack('script')
    @stack('script_2')
</body>
</html>

