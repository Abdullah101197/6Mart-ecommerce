@extends('layouts.vendor.app')

@section('title',translate('Item Bulk Export'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        @php($store_data=\App\CentralLogics\Helpers::get_store_data())
        @php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
        @php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
        @php($productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))

        @if($productsRmsUi)
            @push('css_or_js')
                <style>
                    .mf-products{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif}
                    .mf-products .mf-welcome{
                        background: linear-gradient(125deg, var(--dark-clr, #005555) 0%, var(--primary, #006161) 45%, var(--primary-clr, #107980) 100%);
                        border-radius:14px;padding:18px 20px;color:#fff;position:relative;overflow:hidden;margin-bottom:14px
                    }
                    .mf-products .mf-welcome:before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.04)}
                    .mf-products .mf-welcome:after{content:'';position:absolute;right:60px;bottom:-40px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.03)}
                    .mf-products .mf-welcome h1{font-size:16px;font-weight:900;margin:0;color:#fff;position:relative}
                    .mf-products .mf-welcome p{font-size:12px;opacity:.82;margin:6px 0 0;color:#fff;position:relative}
                    .mf-products .mf-chips{display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;position:relative}
                    .mf-products .mf-chip{display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:7px 12px;font-weight:900;font-size:12px;border:1px solid rgba(255,255,255,.28);text-decoration:none}
                    .mf-products .mf-chip.primary{background:#fff;color:#0f172a;border-color:#fff}
                    .mf-products .mf-chip.ghost{background:rgba(255,255,255,.14);color:#fff}
                    .mf-products .mf-kpis{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin:0}
                    .mf-products .mf-kpi{background:#fff;border:0;border-radius:14px;box-shadow:0 6px 18px rgba(2,6,23,.08);padding:14px 16px}
                    .mf-products .mf-kpi .t{font-size:10px;color:#94a3b8;font-weight:900;letter-spacing:.1em;text-transform:uppercase}
                    .mf-products .mf-kpi .v{font-size:22px;font-weight:900;margin-top:6px;line-height:1.1;color:#0f172a}
                    .mf-products .mf-kpi .s{font-size:12px;color:#64748b;margin-top:6px}
                    .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                    .mf-products .mf-card .card-header{background:#fff;border-bottom:1px solid #eef2f7}
                </style>
            @endpush

            <div class="mf-products mb-3">
                <div class="mf-welcome">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1>{{ translate('messages.export_items') }}</h1>
                            <p>{{ translate('Export items by type, date, or id range') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip ghost" href="{{ route('vendor.item.list') }}"><i class="tio-arrow-backward"></i> {{ translate('messages.back') }}</a>
                                <a class="mf-chip primary" href="{{ route('vendor.item.add-new') }}"><i class="tio-add-circle"></i> {{ translate('messages.add_new_item') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3 mt-lg-0">
                            <div class="mf-kpis">
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Step') }}</div>
                                    <div class="v">1</div>
                                    <div class="s">{{ translate('Select type') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Step') }}</div>
                                    <div class="v">2</div>
                                    <div class="s">{{ translate('Choose range') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Format') }}</div>
                                    <div class="v">XLSX</div>
                                    <div class="s">{{ translate('Spreadsheet') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Module') }}</div>
                                    <div class="v">{{ $store_data->module->module_type }}</div>
                                    <div class="s">{{ translate('Current') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('assets/admin/img/category.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('messages.export_items')}}
                </span>
            </h1>
        </div>
        <div class="card mt-2 rest-part {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
            <div class="card-body">
                <div class="export-steps">
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 1') }}</h5>
                            <p>
                                {{ translate('Select Data Type') }}
                            </p>
                        </div>
                    </div>
                    <div class="export-steps-item">
                        <div class="inner">
                            <h5>{{ translate('STEP 2') }}</h5>
                            <p>
                                {{ translate('Select Data Range and Export') }}
                            </p>
                        </div>
                    </div>
                </div>
                <form class="product-form" action="{{route('vendor.item.bulk-export')}}" method="POST"
                        enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.type')}}<span
                                        class="input-label-secondary"></span></label>
                                <select name="type" id="type" data-placeholder="{{translate('messages.select_type')}}" class="form-control" required title="Select Type">
                                    <option value="all">{{translate('messages.all_data')}}</option>
                                    <option value="date_wise">{{translate('messages.date_wise')}}</option>
                                    <option value="id_wise">{{translate('messages.id_wise')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group id_wise">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.start_id')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="number" name="start_id" class="form-control">
                            </div>
                            <div class="form-group date_wise">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.from_date')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="date" name="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group id_wise">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.end_id')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="number" name="end_id" class="form-control">
                            </div>
                            <div class="form-group date_wise">
                                <label class="input-label text-capitalize" for="exampleFormControlSelect1">{{translate('messages.to_date')}}<span
                                        class="input-label-secondary"></span></label>
                                <input type="date" name="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button class="btn btn--reset" type="reset">{{translate('messages.reset')}}</button>
                                <button class="btn btn--primary" type="submit">{{translate('messages.export')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    "use strict";
    $(document).on('ready', function (){
        $('.id_wise').hide();
        $('.date_wise').hide();
        $('#type').on('change', function()
        {
            $('.id_wise').hide();
            $('.date_wise').hide();
            $('.'+$(this).val()).show();
        })
    });
</script>
@endpush
