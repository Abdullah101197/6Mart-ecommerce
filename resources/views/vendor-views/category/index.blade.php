@extends('layouts.vendor.app')

@section('title', translate('messages.category'))

@php($store_data = \App\CentralLogics\Helpers::get_store_data())
@php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
@php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
@php($categoryRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))

@push('css_or_js')
    @if ($categoryRmsUi)
        <style>
            .mf-cat-rms .mf-strip{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:14px}
            .mf-cat-rms .mf-kpi{background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:12px 14px}
            .mf-cat-rms .mf-kpi .t{font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em}
            .mf-cat-rms .mf-kpi .v{font-size:22px;font-weight:900;color:#0f172a;margin-top:4px}
            .mf-cat-rms .mf-bar{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
        </style>
    @endif
@endpush

@section('content')
    <div class="content container-fluid @if($categoryRmsUi) mf-cat-rms @endif">
        @if ($categoryRmsUi)
            <div class="mf-bar">
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('vendor.category.add-sub-category') }}">{{ translate('messages.sub_category') }}</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('vendor.item.list') }}">{{ translate('messages.items') }}</a>
            </div>
            <div class="mf-strip">
                <div class="mf-kpi">
                    <div class="t">{{ translate('messages.main') }} {{ translate('messages.categories') }}</div>
                    <div class="v">{{ $mainCategoriesCount ?? 0 }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t">{{ translate('messages.sub') }} {{ translate('messages.categories') }}</div>
                    <div class="v">{{ $subCategoriesCount ?? 0 }}</div>
                </div>
                <div class="mf-kpi">
                    <div class="t">{{ translate('messages.category_list') }}</div>
                    <div class="v">{{ $categories->total() }}</div>
                </div>
            </div>
        @endif
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('assets/admin/img/categories.png') }}" class="w--20" alt="">
                </span>
                <span>
                    {{ translate('messages.category_list') }} <span class="badge badge-soft-dark ml-2"
                        id="itemCount">{{ $categories->total() }}</span>
                </span>
            </h1>
        </div>
        <!-- End Page Header -->
        <div class="row">
            <div class="@if($categoryRmsUi) col-lg-8 @else col-md-12 @endif">
                <div class="card @if($categoryRmsUi) mb-3 mb-lg-0 @endif">
                    <div class="card-header py-2 border-0">
                        <div class="search--button-wrapper justify-content-end">
                            <form class="search-form">

                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input type="search" value="{{ request()?->search ?? null }}" name="search"
                                        class="form-control min-height-45"
                                        placeholder="{{ translate('messages.search_categories') }}"
                                        aria-label="{{ translate('messages.ex_:_categories') }}">
                                    <button type="submit" class="btn btn--secondary min-height-45"><i
                                            class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <!-- Unfold -->
                            <div class="hs-unfold mr-2">
                                <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px"
                                    href="javascript:"
                                    data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                    <i class="tio-download-to mr-1"></i> {{ translate('messages.export') }}
                                </a>

                                <div id="usersExportDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">

                                    <span class="dropdown-header">{{ translate('messages.download_options') }}</span>
                                    <a id="export-excel" class="dropdown-item"
                                        href="{{ route('vendor.category.export-categories', ['type' => 'excel', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('assets/admin/svg/components/excel.svg') }}"
                                            alt="Image Description">
                                        {{ translate('messages.excel') }}
                                    </a>
                                    <a id="export-csv" class="dropdown-item"
                                        href="{{ route('vendor.category.export-categories', ['type' => 'csv', request()->getQueryString()]) }}">
                                        <img class="avatar avatar-xss avatar-4by3 mr-2"
                                            src="{{ asset('assets/admin/svg/components/placeholder-csv-format.svg') }}"
                                            alt="Image Description">
                                        .{{ translate('messages.csv') }}
                                    </a>

                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive datatable-custom">
                            <table id="columnSearchDatatable"
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                data-hs-datatables-options='{
                                    "search": "#datatableSearch",
                                    "entries": "#datatableEntries",
                                    "isResponsive": false,
                                    "isShowPaging": false,
                                    "paging":false,
                                }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th class="w-33p border-0 text-center">{{ translate('messages.#') }}</th>
                                        <th class="w-33p border-0 text-center">{{ translate('messages.category_id') }}</th>
                                        <th class="w-33p border-0 text-center">{{ translate('messages.category_name') }}
                                        </th>

                                        @if ($categoryWiseTax)
                                            <th class="border-0 ">{{ translate('messages.Vat/Tax') }}</th>
                                        @endif
                                    </tr>
                                </thead>

                                <tbody id="table-div">
                                    @foreach ($categories as $key => $category)
                                        <tr>
                                            <td class="text-center">{{ $key + $categories->firstItem() }}</td>
                                            <td class="text-center">{{ $category->id }}</td>
                                            <td class="text-center">
                                                <span class="d-block font-size-sm text-body">
                                                    {{ Str::limit($category['name'], 20, '...') }}
                                                </span>
                                            </td>



                                            @if ($categoryWiseTax)
                                                <td>
                                                    <span class="d-block font-size-sm text-body">
                                                        @forelse ($category?->taxVats?->pluck('tax.name', 'tax.tax_rate')->toArray() as $key => $tax)
                                                            <span> {{ $tax }} : <span class="font-bold">
                                                                    ({{ $key }}%)
                                                                </span> </span>
                                                            <br>
                                                        @empty
                                                            <span> {{ translate('messages.no_tax') }} </span>
                                                        @endforelse
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer page-area">
                        <!-- Pagination -->
                        {!! $categories->links() !!}
                        <!-- Pagination -->
                        @if (count($categories) === 0)
                            <div class="empty--data">
                                <img src="{{ asset('/assets/admin/svg/illustrations/sorry.svg') }}" alt="public">
                                <h5>
                                    {{ translate('no_data_found') }}
                                </h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
            @if($categoryRmsUi)
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header py-2 border-0 d-flex justify-content-between align-items-center">
                            <div class="font-weight-bold">{{ translate('Category Discounts') }}</div>
                            <a class="btn btn-sm btn--primary" href="javascript:" title="{{ translate('Add Discount') }}">
                                + {{ translate('Add Discount') }}
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <span class="badge badge-soft-info">{{ (int) ($categoryDiscountsCount ?? 0) }} {{ translate('Category Discounts') }}</span>
                            </div>
                            @if(!empty($categoryDiscounts) && count($categoryDiscounts) > 0)
                                <div class="d-flex flex-column" style="gap:10px">
                                    @foreach($categoryDiscounts as $d)
                                        <div class="p-2" style="border:1px solid #e2e8f0;border-radius:12px;background:#f8fafc">
                                            <div class="d-flex justify-content-between align-items-start" style="gap:10px">
                                                <div class="font-weight-bold">{{ $d->category?->name ?? '—' }}</div>
                                                <div class="text-success font-weight-bold">
                                                    @if(($d->discount_type ?? 'percent') === 'amount')
                                                        {{ \App\CentralLogics\Helpers::format_currency((float) ($d->discount ?? 0)) }}
                                                    @else
                                                        {{ number_format((float) ($d->discount ?? 0), 1) }}%
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                {{ ($d->status ?? false) ? translate('Active') : translate('Inactive') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted small">{{ translate('No discounts yet') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
    </div>

@endsection
