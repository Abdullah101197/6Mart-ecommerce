<?php
    $aspectRatio = match ($ratio ?? '1:1') {
        '1:1' => 'ratio-1',
        '2:1' => 'ratio-2-1',
        '3:1' => 'ratio-3-1',
        default => 'ratio-1',
    };
    $imageExtension = $imageExtension ?? IMAGE_EXTENSION;
    $maxSize = $maxSize ?? MAX_FILE_SIZE;
    $isRequired = $isRequired ?? false;
    $existingImage = $existingImage ?? '';
    $ratio = $ratio ?? '1:1';
    $id = $id ?? 'image-input';
    $name = $name ?? 'image';
    $imageFormat = $imageFormat ?? IMAGE_FORMAT;
    $pixel = isset($pixel) && $pixel !== '' ? $pixel . ' px' : null;
    $size = $pixel ?? $ratio;
    $textPosition = $textPosition ?? 'top';
?>
<div class="mx-auto text-center">
    <?php if($textPosition == 'top'): ?>
        <p class="mb-2 fs-12 gray-dark">
            <?php echo e(translate(($imageFormat) . '. Less Than ' . $maxSize . 'MB')); ?> <span
                class="font-medium text-title"><?php echo e(translate('(' . $size . ')')); ?></span>
        </p>
    <?php endif; ?>
    <div class="upload-file_custom <?php echo e($aspectRatio); ?> h-100px">
        <input type="hidden" name="<?php echo e($name); ?>_deleted" class="image-delete-flag" value="0">
        <input class="upload-file__input single_file_input" type="file" id="<?php echo e($id); ?>" name="<?php echo e($name); ?>"
            accept="<?php echo e($imageExtension); ?>" <?php echo e(!$existingImage && $isRequired ? 'required' : ''); ?>

            data-max-size="<?php echo e($maxSize); ?>">
        <?php if(!$isRequired): ?>
            <button type="button" class="remove_btn remove_btn_outside btn icon-btn btn-circle btn-danger fs-14 lh-1"
                style="--size: 20px;">
                <i class="tio-clear"></i>
            </button>
        <?php endif; ?>
        <label for="<?php echo e($id); ?>" class="upload-file__wrapper w-100 h-100 m-0 <?php echo e($aspectRatio); ?>">
            <div class="upload-file-textbox text-center">
                <img width="27" class="svg" src="<?php echo e(asset('assets/admin/img/document-upload.svg')); ?>" alt="img">
                <h6 class="mt-1 color-656566 fw-medium fs-10 lh-base text-center">
                    <span class="theme-clr"><?php echo e(translate('Add')); ?></span>
                    <?php if($isRequired): ?>
                        <span class="text-danger d-none">*</span>
                    <?php endif; ?>
                </h6>
            </div>
            <img class="upload-file-img" loading="lazy" src="<?php echo e($existingImage); ?>" data-default-src="" alt=""
                style="display: none;">
        </label>
        <div class="overlay">
            <div class="d-flex gap-1 justify-content-center align-items-center h-100">
                <button type="button" class="btn btn-outline-info icon-btn view_btn">
                    <i class="tio-invisible"></i>
                </button>
                <button type="button" class="btn btn-outline-info icon-btn edit_btn">
                    <i class="tio-camera-enhance"></i>
                </button>
            </div>
        </div>
    </div>
    <?php if($textPosition == 'bottom'): ?>
        <p class="mt-3 mb-2 fs-12 gray-dark">
            <?php echo e(translate(strtoupper($imageFormat) . '. Less Than ' . $maxSize . 'MB')); ?> <span
                class="font-medium text-title"><?php echo e(translate('(' . $size . ')')); ?></span>
        </p>
    <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\partials\_image-uploader.blade.php ENDPATH**/ ?>