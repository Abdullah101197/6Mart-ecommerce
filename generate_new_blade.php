<?php
$html = file_get_contents(__DIR__ . '/task2.html');
// Extract from <div class="tab-nav"> down to the end of <div class="content">
preg_match('/<!-- TAB NAV -->(.*?)<!-- \X*?TAB 2/s', $html, $matches_general_only); // Wait, I want all tabs.
preg_match('/<div class="tab-nav">(.*?)<script>/s', $html, $matches);
$tabs_content = '<div class="tab-nav">' . $matches[1];
// The end might include </div></div>, we can fix that later.

$blade_skeleton = <<<BLADE
@extends('layouts.admin.app')

@section('title', translate('messages.add_new_item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/css/new-product.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid new-product-form">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center mb-4">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                </span>
                <span>{{ translate('messages.add_new_item') }}</span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form id="item_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
            <input type="hidden" id="request_type" value="admin">
            <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">
            
            $tabs_content
            
            <div class="action-bar mt-4">
                <div class="action-info">Last saved: <strong id="lastSaved">Never</strong></div>
                <div class="btn-row">
                    <button type="button" class="btn btn-ghost" onclick="saveDraft()">💾 Save Draft</button>
                    <button type="button" class="btn btn-outline">👁️ Preview</button>
                    <button type="submit" id="submitButton" class="btn btn-primary">🚀 Publish</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/tags-input.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        function switchTab(btn, panelId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(panelId).classList.add('active');
        }
        
        // Include the rest of task2.html JS or a custom submit handler later
    </script>
@endpush
BLADE;

file_put_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php', $blade_skeleton);
echo "index_new.blade.php created";
