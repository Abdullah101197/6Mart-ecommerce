@extends('layouts.admin.manufuture')

@section('title', 'AI Pulse')
@section('page_title', 'AI Pulse')

@section('content')
    <div class="mf-card mf-welcome">
        <div>
            <h1>AI Pulse</h1>
            <p>Use AI tools already available in your system (Module: <code>{{ $module_type }}</code>).</p>
        </div>
    </div>

    <div class="mf-grid mf-grid-2">
        <div class="mf-card">
            <div class="mf-card-header">
                <div class="mf-card-title">Product title generator</div>
            </div>
            <div class="mf-card-body">
                <div class="form-group">
                    <label class="form-label">Product name</label>
                    <input id="ai-name" class="form-control" type="text" placeholder="Example: Organic Basmati Rice 5kg">
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                    <button id="ai-title-btn" class="btn btn--primary" type="button">Generate title</button>
                    <button id="ai-desc-btn" class="btn btn--primary" type="button">Generate description</button>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">Result</label>
                    <textarea id="ai-out" class="form-control" style="min-height:140px"></textarea>
                </div>

                <div class="mf-muted">
                    Uses existing endpoints from `Modules/AI` (Product Auto Fill). If you haven’t configured <code>OPENAI_API_KEY</code>,
                    you’ll see an error response.
                </div>
            </div>
        </div>

        <div class="mf-card">
            <div class="mf-card-header">
                <div class="mf-card-title">Status</div>
            </div>
            <div class="mf-card-body">
                <div class="mf-muted">Title endpoint:</div>
                <code>{{ route('admin.product.title-auto-fill') }}</code>
                <div class="mt-3"></div>
                <div class="mf-muted">Description endpoint:</div>
                <code>{{ route('admin.product.description-auto-fill') }}</code>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        "use strict";
        function mfAiCall(url) {
            const name = ($('#ai-name').val() || '').trim();
            if (!name) {
                $('#ai-out').val('Please enter a product name.');
                return;
            }
            $('#ai-out').val('Generating...');
            $.ajax({
                url,
                method: 'GET',
                data: { name, module_type: @json($module_type) },
                success: function (res) {
                    // res shape depends on ProductResponse; try common keys
                    const out = res?.data?.title || res?.data?.description || res?.title || res?.description || JSON.stringify(res, null, 2);
                    $('#ai-out').val(out);
                },
                error: function (xhr) {
                    let msg = xhr?.responseJSON?.message || xhr?.responseJSON?.errors?.[0]?.message || xhr?.responseText || 'Request failed';
                    $('#ai-out').val(msg);
                }
            });
        }

        $('#ai-title-btn').on('click', function () {
            mfAiCall(@json(route('admin.product.title-auto-fill')));
        });
        $('#ai-desc-btn').on('click', function () {
            mfAiCall(@json(route('admin.product.description-auto-fill')));
        });
    </script>
@endpush

