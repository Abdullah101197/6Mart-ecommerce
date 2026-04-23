@extends('layouts.vendor.app')

@section('title', translate('Brands'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    @if($productsRmsUi ?? false)
        @push('css_or_js')
            <style>
                .mf-products{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
                .mf-products .mf-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:14px}
                @media(max-width:991.98px){.mf-products .mf-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
                @media(max-width:575.98px){.mf-products .mf-kpis{grid-template-columns:1fr}}
                .mf-products .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.03);padding:14px 16px}
                .mf-products .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                .mf-products .mf-kpi .v{font-size:22px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                .mf-products .mf-card-h{padding:14px 16px;border-bottom:1px solid #eef2f7;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
                .mf-products .mf-card-t{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:900;margin:0}
                .mf-products .mf-tools{display:flex;gap:10px;align-items:center;flex-wrap:wrap}
                .mf-products .mf-tools .form-control{height:38px;border-radius:10px}
                .mf-products .mf-table thead th{font-size:9px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0}
                .mf-products .mf-table th,.mf-products .mf-table td{padding:.65rem .75rem;vertical-align:middle}
                .mf-products .mf-pill{display:inline-flex;align-items:center;border-radius:999px;padding:3px 8px;font-size:11px;font-weight:900;border:1px solid #e2e8f0}
                .mf-products .mf-pill.ok{background:#ecfdf5;border-color:#bbf7d0;color:#16a34a}
                .mf-products .mf-pill.off{background:#fef2f2;border-color:#fecaca;color:#dc2626}
                .mf-products .mf-actions{display:inline-flex;gap:8px;align-items:center;justify-content:center}
                .mf-products .mf-actions .btn{height:32px;border-radius:10px;font-weight:900;font-size:12px}
            </style>
        @endpush

        <div class="mf-products">
            <div class="mf-kpis">
                <div class="mf-kpi">
                    <div class="t">{{ translate('Total Brands') }}</div>
                    <div class="v">{{ (int) data_get($kpis,'total_brands',0) }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t">{{ translate('Featured') }}</div>
                    <div class="v">{{ (int) data_get($kpis,'featured',0) }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t">{{ translate('Custom Labels') }}</div>
                    <div class="v">{{ (int) data_get($kpis,'custom_labels',0) }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t">{{ translate('Warranty Active') }}</div>
                    <div class="v">{{ (int) data_get($kpis,'warranty_active',0) }}</div>
                </div>
            </div>

            <div class="mf-card">
                <div class="mf-card-h">
                    <div class="mf-card-t">{{ translate('Brand Management') }}</div>
                    <div class="mf-tools">
                        <form action="{{ url()->current() }}" method="get">
                            <input class="form-control" type="search" name="search" placeholder="{{ translate('Search brands...') }}" value="{{ $search ?? '' }}">
                        </form>
                        <button class="btn btn--primary" data-toggle="modal" data-target="#mfAddBrand">+ {{ translate('Add Brand') }}</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-borderless table-thead-bordered table-nowrap card-table mf-table">
                        <thead>
                            <tr>
                                <th>{{ translate('Brand') }}</th>
                                <th>{{ translate('Category') }}</th>
                                <th class="text-center">{{ translate('Products') }}</th>
                                <th class="text-center">{{ translate('Sales') }}</th>
                                <th class="text-center">{{ translate('Warranty') }}</th>
                                <th class="text-center">{{ translate('Custom Label') }}</th>
                                <th class="text-center">{{ translate('Status') }}</th>
                                <th class="text-center">{{ translate('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                                @php($st = $stats->get($brand->id))
                                <tr>
                                    <td class="font-weight-bold">{{ $brand->name }}</td>
                                    <td class="text-muted">—</td>
                                    <td class="text-center">{{ (int) data_get($st,'products_count',0) }}</td>
                                    <td class="text-center">{{ \App\CentralLogics\Helpers::format_currency((float) data_get($st,'sales',0)) }}</td>
                                    <td class="text-center"><span class="mf-pill ok">—</span></td>
                                    <td class="text-center"><span class="mf-pill ok">—</span></td>
                                    <td class="text-center">
                                        <span class="mf-pill {{ (int) $brand->status === 1 ? 'ok' : 'off' }}">
                                            {{ (int) $brand->status === 1 ? translate('Active') : translate('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="mf-actions">
                                            <button class="btn btn-sm btn--primary" data-toggle="modal" data-target="#mfEditBrand{{ $brand->id }}">{{ translate('Edit') }}</button>
                                            <a class="btn btn-sm btn--secondary" href="{{ route('vendor.item.brands.status', [$brand->id, $brand->status ? 0 : 1]) }}">{{ $brand->status ? translate('Disable') : translate('Enable') }}</a>
                                        </div>

                                        <div class="modal fade" id="mfEditBrand{{ $brand->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ translate('Edit Brand') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <form method="post" action="{{ route('vendor.item.brands.update', $brand->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label class="input-label">{{ translate('Name') }}</label>
                                                                <input class="form-control" name="name" value="{{ $brand->name }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="input-label">{{ translate('Image') }}</label>
                                                                <input class="form-control" type="file" name="image" accept="image/*">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn--reset" data-dismiss="modal">{{ translate('Cancel') }}</button>
                                                            <button type="submit" class="btn btn--primary">{{ translate('Save') }}</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-center text-muted py-4">{{ translate('no_data_found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">
                    {!! $brands->withQueryString()->links() !!}
                </div>
            </div>
        </div>

        <div class="modal fade" id="mfAddBrand" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ translate('Add Brand') }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <form method="post" action="{{ route('vendor.item.brands.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="input-label">{{ translate('Name') }}</label>
                                <input class="form-control" name="name" required>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{ translate('Image') }}</label>
                                <input class="form-control" type="file" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn--reset" data-dismiss="modal">{{ translate('Cancel') }}</button>
                            <button type="submit" class="btn btn--primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="card"><div class="card-body">{{ translate('Brands page is available in RMS UI mode.') }}</div></div>
    @endif
</div>
@endsection

