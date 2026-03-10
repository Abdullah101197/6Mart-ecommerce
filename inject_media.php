<?php
$index_new = file_get_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php');

// 1. INJECT MEDIA
$media_blade = <<<BLADE
<div class="col-lg-12">
    <div class="card h-100">
        <div class="card-header"><span class="card-icon">📸</span><span class="card-title">Media & Assets</span></div>
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
                        @if (Config::get('module.current_module_type') == 'food')
                            <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                        @else
                            <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                        @endif
                    </label>
                    <label class="d-inline-block m-0 position-relative error-wrapper">
                        <img class="img--176 border" id="viewer"
                            src="{{ asset('public/assets/admin/img/upload-img.png') }}" style="width:176px;height:176px;object-fit:cover" alt="thumbnail" />
                        <div class="icon-file-group">
                            <div class="icon-file"><input type="file" name="image" id="customFileEg1"
                                    class="custom-file-input d-none"
                                    accept=".webp, .jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
BLADE;

// The current Media tab looks like `<div class="tab-panel" id="tab-media">` ... `</div>`
$pattern_media = '/<div class="tab-panel" id="tab-media">.*?<\/div>\s*<\/div>\s*<\/div>\s*(?=<!--)/s';
$replacement_media = '<div class="tab-panel" id="tab-media">' . "\n" . '<div class="grid">' . "\n" . $media_blade . "\n" . '</div>' . "\n" . '</div>' . "\n";
$index_new = preg_replace($pattern_media, $replacement_media, $index_new);

if (strpos($index_new, 'Media & Assets') !== false) {
    echo "Media tab injected successfully.\n";
} else {
    echo "Failed to inject Media tab.\n";
}

file_put_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php', $index_new);
