@php
    $context = $context ?? 'admin';
    $currentModuleType = $currentModuleType ?? Config::get('module.current_module_type');
    $galleryRoute = $context === 'vendor' ? route('vendor.item.product_gallery') : route('admin.item.product_gallery');
@endphp

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{ asset('assets/admin/img/items.png') }}" class="w--22" alt="">
            </span>
            <span>{{ translate('messages.add_new_item') }}</span>
        </h1>
        <div class=" d-flex flex-sm-nowrap flex-wrap  align-items-end">
            <div class="text--primary-2 d-flex flex-wrap align-items-center mr-2">
                <a href="{{ $galleryRoute }}"
                    class="btn btn-outline-primary btn--primary d-flex align-items-center bg-not-hover-primary-ash rounded-8 gap-2">
                    <img src="{{ asset('assets/admin/img/product-gallery.png') }}" class="w--22" alt="">
                    <span>{{ translate('Add Info From Gallery') }}</span>
                </a>
            </div>

            @if ($currentModuleType == 'food')
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center mb-3 foodModalShow" type="button">
                    <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong>
                    <div><i class="tio-info-outined"></i></div>
                </div>
            @else
                <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center mb-3 attributeModalShow" type="button">
                    <strong class="mr-2">{{ translate('See_how_it_works!') }}</strong>
                    <div><i class="tio-info-outined"></i></div>
                </div>
            @endif
        </div>
    </div>
    <!-- End Page Header -->

    <form id="item_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
        <input type="hidden" id="request_type" value="{{ $context }}">
        @if ($context === 'vendor')
            <input type="hidden" id="vendor_store_id" name="store_id" value="{{ \App\CentralLogics\Helpers::get_store_id() }}">
        @endif
        <input type="hidden" id="module_type" value="{{ $currentModuleType }}">

        <div class="row g-2">
            @includeif('admin-views.product.partials._title_and_discription')

            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-body d-flex flex-wrap align-items-center">
                        <div class="w-100 d-flex gap-3 flex-wrap flex-lg-nowrap">
                            <div class="flex-grow-1 mx-auto overflow-x-auto scrollbar-primary">
                                <label class="text-dark d-block mb-4 mb-xl-5">
                                    {{ translate('messages.item_image') }}
                                    <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                                </label>
                                <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2" id="coba"></div>
                            </div>

                            <div class="flex-grow-1 mx-auto pb-2 flex-shrink-0">
                                <label class="text-dark d-block mb-4 mb-xl-5">
                                    {{ translate('messages.item_thumbnail') }}
                                    @if ($currentModuleType == 'food')
                                        <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                                    @else
                                        <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                                    @endif
                                </label>
                                <label class="d-inline-block m-0 position-relative error-wrapper">
                                    <img class="img--176 border" id="viewer"
                                        src="{{ asset('assets/admin/img/upload-img.png') }}" alt="thumbnail" />
                                    <div class="icon-file-group">
                                        <div class="icon-file"><input type="file" name="image" id="customFileEg1"
                                                class="custom-file-input d-none"
                                                accept=".webp, .jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                            <i class="tio-edit"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @includeif('admin-views.product.partials._category_and_general')
            @includeif('admin-views.product.partials._price_and_stock')

            @if ($currentModuleType == 'food')
                @includeif('admin-views.product.partials._food_variations')
            @else
                @includeif('admin-views.product.partials._other_variations')
            @endif

            @includeif('admin-views.product.partials._ai_sidebar')

            @if ($currentModuleType == 'ecommerce')
                @includeIf('admin-views.business-settings.landing-page-settings.partial._meta_data')
            @endif

            <div class="col-12">
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn"
                        class="btn btn--reset">{{ translate('messages.reset') }}</button>
                    <button type="submit" id="submitButton"
                        class="btn btn--primary">{{ translate('messages.submit') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal" id="food-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close foodModalClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/IkoF9gPH6zs"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="attribute-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close attributeModalClose" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/xG8fO7TXPbk"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>
