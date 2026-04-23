@extends('layouts.vendor.app')

@section('title',translate('messages.Product_Gallery'))

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
                    .mf-products .mf-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,.03)}
                    .mf-products .mf-card .card-header{background:#fff;border-bottom:1px solid #eef2f7}
                    .mf-products .mf-tools{display:flex;gap:10px;flex-wrap:wrap;align-items:center;justify-content:flex-end}
                    .mf-products .mf-tools .form-control{height:40px;border-radius:10px}
                    .mf-products .mf-tools .btn{height:40px;border-radius:10px;font-weight:900}
                    .mf-products .empty--data{padding:28px 0}
                    .mf-products .empty--data img{width:120px;margin-bottom:10px}
                    .mf-products .empty--data h5{font-size:13px;color:#64748b;font-weight:900;margin:0}
                </style>
            @endpush

            <div class="mf-products mb-3">
                <div class="mf-welcome">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h1>{{ translate('messages.Product_Gallery') }}</h1>
                            <p>{{ translate('search_product_and_use_its_info_to_create_own_product') }}</p>
                            <div class="mf-chips">
                                <a class="mf-chip ghost" href="{{ route('vendor.item.list') }}"><i class="tio-arrow-backward"></i> {{ translate('messages.back') }}</a>
                                <a class="mf-chip primary" href="{{ route('vendor.item.add-new') }}"><i class="tio-add-circle"></i> {{ translate('messages.add_new_item') }}</a>
                            </div>
                        </div>
                        <div class="col-lg-5 mt-3 mt-lg-0">
                            <form id="search-form" class="search-form mf-tools">
                                @csrf
                                <input type="hidden" value="1" name="product_gallery">
                                <input type="search" value="{{  request()?->search ?? null }}" name="search" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                                <button type="submit" class="btn btn--primary">{{ translate('messages.search') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Header -->
        <div class="page-header {{ $productsRmsUi ? 'd-none' : '' }}">
            <div class="btn--container align-items-center mb-0">
                <div class="d-flex gap-2">
                    <img class="h--50px"
                        src="{{ asset('assets/admin/img/group.png') }}" alt="Product_Gallery">
                    <div>
                        <h1 class="page-header-title"> {{translate('messages.Product_Gallery')}}<span class="badge badge-soft-dark ml-2" id="itemCount"></span></h1>
                    <p>{{ translate('search_product_and_use_its_info_to_create_own_product') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card mb-3 {{ $productsRmsUi ? 'mf-products mf-card d-none' : '' }}">
            <!-- Header -->
            <div class="card-body border-0">
                <form id="search-form" class="search-form">
                    @csrf
                    <input type="hidden" value="1" name="product_gallery">
                    <div class="row">
                        <div class="col-11">
                            <input id="datatableSearch" type="search" value="{{  request()?->search ?? null }}" name="search" class="form-control" placeholder="{{translate('messages.ex_search_name')}}" aria-label="{{translate('messages.search_here')}}">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn--primary">{{ translate('messages.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Header -->
        </div>
        <!-- End Card -->
        <div>
            <h2>{{ translate('messages.Product_List') }}</h2>
            <p>{{ translate('search_product_and_use_its_info_to_create_own_product') }}</p>
        </div>

                    <div class="row {{ $productsRmsUi ? 'mf-products' : '' }}" id="set-rows">
                        @include('vendor-views.product.partials._gallery', [
                            $items,
                        ])
                    </div>
                @if(count($items) === 0)
                <div class="empty--data {{ $productsRmsUi ? 'mf-products' : '' }}">
                    <img src="{{asset('/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif

            <!-- End Table -->
    </div>

@endsection

@push('script_2')
    <script>
        "use strict";
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
                    $('#itemCount').html(data.count);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
