<div class="col-lg-6">
    <div class="card h-100">
        <div class="card-body d-flex flex-wrap align-items-center">
            <div class="w-100 d-flex gap-3 flex-wrap flex-lg-nowrap">
                <div class="flex-grow-1 mx-auto overflow-x-auto scrollbar-primary">
                    <label class="text-dark d-block">
                        {{ translate('messages.item_image') }}
                        <small>( {{ translate('messages.ratio') }} 1:1 )</small>
                    </label>

                    <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2" id="coba">
                        <input type="hidden" id="removedImageKeysInput" name="removedImageKeys" value="">
                        @foreach ($product->images as $key => $photo)
                            @php($photo = is_array($photo) ? $photo : ['img' => $photo, 'storage' => 'public'])
                            <div id="product_images_{{ $key }}"
                                class="spartan_item_wrapper min-w-176px max-w-176px">
                                <img class="img--square onerror-image"
                                    src="{{ \App\CentralLogics\Helpers::get_full_url('product', $photo['img'] ?? '', $photo['storage']) }}"
                                    data-onerror-image="{{ asset('assets/admin/img/upload-img.png') }}"
                                    alt="Product image">
                                <a href="#"
                                    data-key="{{ $key }}"
                                    data-photo="{{ $photo['img'] }}"
                                    class="spartan_remove_row function_remove_img"><i
                                        class="tio-add-to-trash"></i></a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex-grow-1 mx-auto pb-2 flex-shrink-0">
                    <label class="text-dark d-block">
                        {{ translate('messages.item_thumbnail') }}
                        <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                    </label>

                    <label class="d-inline-block m-0 position-relative error-wrapper">
                        <img class="img--176 border onerror-image" id="viewer"
                            src="{{ $product['image_full_url'] ?? asset('assets/admin/img/upload-img.png') }}"
                            data-onerror-image="{{ asset('assets/admin/img/upload-img.png') }}"
                            alt="thumbnail" />

                        <div class="icon-file-group">
                            <div class="icon-file">
                                <input type="file" name="image" id="customFileEg1"
                                    class="custom-file-input read-url"
                                    accept=".webp, .jpg, .png, .jpeg, .webp, .gif, .bmp, .tif, .tiff|image/*">
                                <i class="tio-edit"></i>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

