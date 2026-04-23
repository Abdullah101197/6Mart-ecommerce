
@extends('layouts.vendor.app')

@section('title',translate('messages.Low_Stock_List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
@php($store_data=\App\CentralLogics\Helpers::get_store_data())
@php($sub = $store_data?->store_sub ?? $store_data?->store_sub_update_application)
@php($allowAll = ($store_data?->store_business_model ?? null) === 'commission')
@php($productsRmsUi = $allowAll || ((int) data_get($sub, 'product_rms_ui', 1) === 1))
    <div class="content container-fluid">
        @if($productsRmsUi)
            @php($k = [
                'total' => $items->total(),
                'low' => $items->total(),
                'zero' => (int) collect($items->items())->where('stock', '<=', 0)->count(),
                'showing' => $items->count(),
            ])
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
                    .mf-products .mf-catalogue-h{display:flex;gap:12px;align-items:flex-start;justify-content:space-between;flex-wrap:wrap}
                    .mf-products .mf-catalogue-title{font-size:12px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:900;margin:0}
                    .mf-products .mf-tools{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
                    .mf-products .mf-tools .select-item{min-width:220px}
                    .mf-products .mf-tools .form-control{height:38px;border-radius:10px}
                    .mf-products .mf-tools .input-group .form-control{border-radius:10px 0 0 10px}
                    .mf-products .mf-tools .input-group .btn{height:38px;border-radius:0 10px 10px 0}
                    .mf-products .mf-table thead.thead-light th{font-size:9px;letter-spacing:.08em;text-transform:uppercase;padding:.55rem .7rem;color:#64748b;background:#f8fafc;border-bottom:1px solid #e2e8f0}
                    .mf-products .mf-table tbody td{padding:.6rem .7rem;border-bottom:1px solid #f1f5f9;vertical-align:middle}
                    .mf-products .mf-table tbody tr:hover td{background:#fafafa}
                    .mf-products .mf-namecard{display:flex;flex-direction:column;align-items:flex-start;gap:8px}
                    .mf-products .mf-namecard .avatar.avatar-lg{width:54px;height:54px;border-radius:12px;object-fit:cover}
                    .mf-products .mf-namecard .mf-n{font-weight:900;color:#0f172a;line-height:1.2}
                    .mf-products .mf-namecard .mf-n:hover{color:var(--primary-clr,#107980)}
                    .mf-products .mf-actions{display:inline-flex;gap:8px;align-items:center;justify-content:center;flex-wrap:nowrap}
                    .mf-products .mf-actions .action-btn{min-width:38px}
                    .mf-products .update-quantity-modal .modal-dialog{max-width:560px}
                    .mf-products .update-quantity-modal .modal-content{border-radius:14px}
                    .mf-products .update-quantity-modal .modal-header{border-bottom:0;padding:10px 14px}
                    .mf-products .update-quantity-modal .modal-body{padding:8px 14px 14px}
                    .mf-products .card-header{padding:.75rem 1rem}
                    .mf-products .empty--data{padding:28px 0}
                    .mf-products .empty--data img{width:120px;margin-bottom:10px}
                    .mf-products .empty--data h5{font-size:13px;color:#64748b;font-weight:900;margin:0}
                </style>
            @endpush

            <div class="mf-products mb-3">
                <div class="mf-welcome">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1>{{ translate('messages.Low_Stock_List') }}</h1>
                            <p>{{ translate('Monitor low stock items with RMS v12 UI') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip ghost" href="{{ route('vendor.item.list') }}"><i class="tio-arrow-backward"></i> {{ translate('messages.back') }}</a>
                                <a class="mf-chip primary" href="{{ route('vendor.item.add-new') }}"><i class="tio-add-circle"></i> {{ translate('messages.add_new_item') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3 mt-lg-0">
                            <div class="mf-kpis">
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Total') }}</div>
                                    <div class="v">{{ (int) data_get($k,'total',0) }}</div>
                                    <div class="s">{{ translate('Low stock items') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Zero Stock') }}</div>
                                    <div class="v">{{ (int) data_get($k,'zero',0) }}</div>
                                    <div class="s">{{ translate('Out of stock') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Showing') }}</div>
                                    <div class="v">{{ (int) data_get($k,'showing',0) }}</div>
                                    <div class="s">{{ translate('This page') }}</div>
                                </div>
                                <div class="mf-kpi">
                                    <div class="t">{{ translate('Filter') }}</div>
                                    <div class="v">{{ $type ?? 'all' }}</div>
                                    <div class="s">{{ translate('Veg/Non-veg') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> {{translate('messages.Low_Stock_List')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$items->total()}}</span></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card {{ $productsRmsUi ? 'mf-products mf-card' : '' }}">
            <!-- Header -->
            <div class="card-header py-2  border-0">
                @if($productsRmsUi)
                    <div class="mf-catalogue-h w-100">
                        <div class="mf-catalogue-title">{{ translate('messages.Low_Stock_List') }}</div>
                        <div class="mf-tools">
                            <form id="search-form" class="search-form">
                                @csrf
                                <div class="input-group input--group">
                                    <input type="search" name="search" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                                    <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                </div>
                            </form>

                            @if ($store_data->module->module_type == 'food' && $toggle_veg_non_veg)
                                <div class="select-item" style="min-width:180px">
                                    <select name="type" data-url="{{url()->full()}}" data-filter="type" data-placeholder="{{translate('messages.all')}}" class="form-control h--37px set-filter">
                                        <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                                        <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                                        <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                                    </select>
                                </div>
                            @endif

                            <div class="select-item" style="min-width:240px">
                                <select name="category_id" id="category" data-url="{{url()->full()}}" data-filter="category_id" data-placeholder="{{translate('messages.select_category')}}" class="js-data-example-ajax form-control set-filter">
                                    @if($category)
                                        <option value="{{$category->id}}" selected>{{$category->name}} ({{$category->position == 0?translate('messages.main'):translate('messages.sub')}})</option>
                                    @else
                                        <option value="all" selected>{{translate('messages.all_categories')}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="search--button-wrapper justify-content-end">
                        <form id="search-form" class="search-form">
                            @csrf
                            <div class="input-group input--group">
                                <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        </form>
                        @if ($store_data->module->module_type == 'food' && $toggle_veg_non_veg)
                        <div class="col-sm-auto mb-1 mb-sm-0">
                            <select name="type" data-url="{{url()->full()}}" data-filter="type" data-placeholder="{{translate('messages.all')}}" class="form-control h--37px set-filter">
                                <option value="all" {{$type=='all'?'selected':''}}>{{translate('messages.all')}}</option>
                                <option value="veg" {{$type=='veg'?'selected':''}}>{{translate('messages.veg')}}</option>
                                <option value="non_veg" {{$type=='non_veg'?'selected':''}}>{{translate('messages.non_veg')}}</option>
                            </select>
                        </div>
                        @endif
                        <div class="hs-unfold  min--250">
                            <select name="category_id" id="category" data-url="{{url()->full()}}" data-filter="category_id" data-placeholder="{{translate('messages.select_category')}}" class="js-data-example-ajax form-control set-filter">
                                @if($category)
                                    <option value="{{$category->id}}" selected>{{$category->name}} ({{$category->position == 0?translate('messages.main'):translate('messages.sub')}})</option>
                                @else
                                    <option value="all" selected>{{translate('messages.all_categories')}}</option>
                                @endif
                            </select>
                        </div>
                    </div>
                @endif

                    <!-- End Unfold -->

                    <!-- Unfold -->
                    <div class="hs-unfold d-none">
                        <div id="showHideDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right dropdown-card">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.name')}}</span>
                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_name">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_name" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.category')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_type">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_type" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    <!-- End Checkbox Switch -->
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.price')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_status">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_status" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.stock')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_price">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_price" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="mr-2">{{translate('messages.action')}}</span>

                                        <!-- Checkbox Switch -->
                                        <label class="toggle-switch toggle-switch-sm" for="toggleColumn_action">
                                            <input type="checkbox" class="toggle-switch-input" id="toggleColumn_action" checked>
                                            <span class="toggle-switch-label">
                                            <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <!-- End Checkbox Switch -->
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
            <!-- End Header -->


            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{ $productsRmsUi ? 'mf-products mf-table' : '' }}"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [],
                            "width": "5%",
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },

                        "entries": "#datatableEntries",
                        "isResponsive": false,
                        "isShowPaging": false,
                            "paging":false
                    }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('messages.#')}}</th>
                        <th class="border-0 w-20p">{{translate('messages.name')}}</th>
                        @if($productsRmsUi)
                            <th class="border-0">{{ translate('Added') }}</th>
                        @endif
                        <th class="border-0 w-20p">{{translate('messages.category')}}</th>
                        <th class="border-0">{{translate('messages.price')}}</th>
                        <th class="border-0 text-center">{{translate('messages.stock')}}</th>
                        <th class="border-0 text-center">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($items as $key=>$item)
                        <tr>
                            <td>{{$key+$items->firstItem()}}</td>
                            <td>
                                @if($productsRmsUi)
                                    <a class="mf-namecard" href="{{route('vendor.item.view',[$item['id']])}}">
                                        <img class="avatar avatar-lg onerror-image" src="{{ $item['image_full_url'] }}"
                                             data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->name}} image">
                                        <div class="mf-n">{{Str::limit($item['name'],24,'...')}}</div>
                                    </a>
                                @else
                                    <a class="media align-items-center" href="{{route('vendor.item.view',[$item['id']])}}">
                                        <img class="avatar avatar-lg mr-3 onerror-image" src="{{ $item['image_full_url'] }}"
                                             data-onerror-image="{{asset('assets/admin/img/160x160/img2.jpg')}}" alt="{{$item->name}} image">
                                        <div class="media-body">
                                            <h5 class="text-hover-primary mb-0">{{Str::limit($item['name'],20,'...')}}</h5>
                                        </div>
                                    </a>
                                @endif
                            </td>
                            @if($productsRmsUi)
                                <td>
                                    <span class="text-muted font-weight-bold">
                                        {{ \App\CentralLogics\Helpers::date_format($item->created_at) }}
                                    </span>
                                </td>
                            @endif
                            <td>
                            {{Str::limit($item->category?$item->category->name:translate('messages.category_deleted'),20,'...')}}
                            </td>
                            <td>
                                {{\App\CentralLogics\Helpers::format_currency($item['price'])}}
                            </td>
                            <td>
                                <div class="text-center">
                                    {{($item['stock'])}}
                                </div>
                            </td>
                            <td>
                                <div class="{{ $productsRmsUi ? 'mf-actions' : 'btn--container justify-content-center' }}">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn update_quantity"
                                        href="javascript:" title="{{translate('messages.edit_quantity')}}" data-id="{{ $item->id }}" data-toggle="modal" data-target="#update-quantity"><i class="tio-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="page-area">
                    <table>
                        <tfoot class="border-top">
                        {!! $items->links() !!}
                        </tfoot>
                    </table>
                </div>
                @if(count($items) === 0)
                <div class="empty--data">
                    <img src="{{asset('/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>

@endsection


<div class="modal fade update-quantity-modal" id="update-quantity" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">

                <form action="{{route('vendor.item.stock-update')}}" method="post">
                    @csrf
                    <div class="mt-2 rest-part w-100"></div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" data-dismiss="modal" aria-label="Close" class="btn btn--reset">{{translate('cancel')}}</button>
                        <button type="submit" id="submit_new_customer" class="btn btn--primary">{{translate('update_stock')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('script_2')

<script>
    "use strict";

    $(document).on('click', '.update_quantity', function (){
        let val = $(this).data('id');
        let $wrap = $('.rest-part');
        $wrap.html(
            '<div class="p-3 text-center text-muted" style="min-height:120px;display:flex;align-items:center;justify-content:center">' +
            '<span>Loading…</span>' +
            '</div>'
        );
        $.get({
            url: '{{url('/')}}/vendor-panel/item/get-variations?id='+val,
            dataType: 'json',
            success: function (data) {
                $wrap.empty().html((data && data.view) ? data.view : '');
                update_qty();
            },
            error: function () {
                $wrap.html(
                    '<div class="p-3 text-center text-danger" style="min-height:120px;display:flex;align-items:center;justify-content:center">' +
                    '<span>Something went wrong</span>' +
                    '</div>'
                );
            }
        });
    });

    function update_qty() {
            let total_qty = 0;
            let qty_elements = $('input[name^="stock_"]');
            for (let i = 0; i < qty_elements.length; i++) {
                total_qty += parseInt(qty_elements.eq(i).val());
            }
            if(qty_elements.length > 0)
            {

                $('input[name="current_stock"]').attr("readonly", 'readonly');
                $('input[name="current_stock"]').val(total_qty);
            }
            else{
                $('input[name="current_stock"]').attr("readonly", false);
            }
        }


        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
          select: {
            style: 'multi',
            classMap: {
              checkAll: '#datatableCheckAll',
              counter: '#datatableCounter',
              counterInfo: '#datatableCounterInfo'
            }
          },
          language: {
            zeroRecords: '<div class="text-center p-4">' +
                '<img class="w-7rem mb-3" src="{{asset('assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +

                '</div>'
          }
        });

        $('#datatableSearch').on('mouseup', function (e) {
          let $input = $(this),
            oldValue = $input.val();

          if (oldValue == "") return;

          setTimeout(function(){
            let newValue = $input.val();

            if (newValue == ""){
              // Gotcha
              datatable.search('').draw();
            }
          }, 1);
        });

        $('#toggleColumn_index').change(function (e) {
          datatable.columns(0).visible(e.target.checked)
        })
        $('#toggleColumn_name').change(function (e) {
          datatable.columns(1).visible(e.target.checked)
        })

        $('#toggleColumn_type').change(function (e) {
          datatable.columns(2).visible(e.target.checked)
        })

        $('#toggleColumn_status').change(function (e) {
          datatable.columns(4).visible(e.target.checked)
        })
        $('#toggleColumn_price').change(function (e) {
          datatable.columns(3).visible(e.target.checked)
        })
        $('#toggleColumn_action').change(function (e) {
          datatable.columns(5).visible(e.target.checked)
        })


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                let select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });

        $('#category').select2({
            ajax: {
                url: '{{route("vendor.category.get-all")}}',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        all:true,
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    let $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('vendor.item.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
