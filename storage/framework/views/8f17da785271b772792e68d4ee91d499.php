

<?php $__env->startSection('title',translate('Update delivery-man')); ?>


<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header  mb-15 mt-2">
            <h1 class="page-header-title mb-0 fs-24 text-break">
                <span class="page-header-icon">
                    <img src="<?php echo e(asset('assets/admin/img/edit.png')); ?>" class="w--26" alt="">
                </span>
                <span><?php echo e(translate('messages.update_deliveryman')); ?></span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form class="validate-form global-ajax-form" action="<?php echo e(route('admin.users.delivery-man.update',[$deliveryMan['id']])); ?>" method="post"
                enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="card mb-20">
                <div class="card-header">
                   <div>
                        <h3 class="mb-1">
                            <?php echo e(translate('Basic Information')); ?>

                        </h3>
                        <p class="mb-0 fs-12">
                            <?php echo e(translate('Here you setup your all business information.')); ?>

                        </p>
                   </div>                    
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <div class="shadow-sm p-xxl-20 p-3 bg-white h-100">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-0">
                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.first_name')); ?> <span class="form-label-secondary text-danger"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                                </span>
                                                    </label>
                                                            <input type="text" value="<?php echo e($deliveryMan['f_name']); ?>" name="f_name"
                                                                    class="form-control" placeholder="<?php echo e(translate('messages.first_name')); ?>"
                                                                    required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.last_name')); ?> <span class="form-label-secondary text-danger"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                                </span>
                                                    </label>
                                                            <input type="text" value="<?php echo e($deliveryMan['l_name']); ?>" name="l_name"
                                                                    class="form-control" placeholder="<?php echo e(translate('messages.last_name')); ?>"
                                                                    required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.email')); ?> <span class="form-label-secondary text-danger"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                                </span>
                                                    </label>
                                                            <input type="email" value="<?php echo e($deliveryMan['email']); ?>" name="email" class="form-control"
                                                                    placeholder="<?php echo e(translate('messages.Ex:')); ?> ex@example.com"
                                                                    required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.deliveryman_type')); ?> <span class="form-label-secondary text-danger"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                                </span>
                                                    </label>
                                                            <select name="earning" class="form-control  js-select2-custom" required>
                                                                <option value="1" <?php echo e($deliveryMan->earning?'selected':''); ?>><?php echo e(translate('messages.freelancer')); ?></option>
                                                                <option value="0" <?php echo e($deliveryMan->earning?'':'selected'); ?>><?php echo e(translate('messages.salary_based')); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.Delivery zone')); ?> <span class="form-label-secondary text-danger"
                                                                data-toggle="tooltip" data-placement="right"
                                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> 
                                                                  <i class="tio-info text-muted"></i>
                                                                *
                                                                </span>
                                                            </label>
                                            <select name="zone_id" class="form-control  js-select2-custom">
                                            <?php $__currentLoopData = \App\Models\Zone::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(isset(auth('admin')->user()->zone_id)): ?>
                                                    <?php if(auth('admin')->user()->zone_id == $zone->id): ?>
                                                        <option value="<?php echo e($zone->id); ?>" <?php echo e($zone->id == $deliveryMan->zone_id?'selected':''); ?>><?php echo e($zone->name); ?></option>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                <option value="<?php echo e($zone->id); ?>" <?php echo e($zone->id == $deliveryMan->zone_id?'selected':''); ?>><?php echo e($zone->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.vehicle Type')); ?><span class="form-label-secondary text-danger"
                                                data-toggle="tooltip" data-placement="right"
                                                data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                                </span>
                                            </label>
                                            <select name="vehicle_id" class="form-control js-select2-custom h--45px">
                                                <option value="" readonly="true" hidden="true"><?php echo e(translate('messages.select_vehicle')); ?></option>
                                            <?php $__currentLoopData = \App\Models\DMVehicle::where('status',1)->get(['id','type']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($v->id); ?>" <?php echo e($v->id == $deliveryMan->vehicle_id?'selected':''); ?>><?php echo e($v->type); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="bg-light2 rounded h-100 d-center">
                                <div class="text-center">
                                    <div class="mb-1">
                                        <h4 class="mb-1"><?php echo e(translate('Deliveryman image')); ?> <span class="text-danger">*</span> </h4>
                                    </div>
                                    <div class="mx-auto text-center">
                                        <?php echo $__env->make('admin-views.partials._image-uploader', [
                                                'id' => 'image-input',
                                                'name' => 'image',
                                                'ratio' => '1:1',
                                                'isRequired' => true,
                                                'existingImage' => $deliveryMan['image_full_url'] ?? null,
                                                'imageExtension' => IMAGE_EXTENSION,
                                                'imageFormat' => IMAGE_FORMAT,
                                                'maxSize' => MAX_FILE_SIZE,
                                                ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                   <div>
                        <h3 class="mb-1">
                            <?php echo e(translate('General Setup')); ?>

                        </h3>
                        <p class="mb-0 fs-12">
                            <?php echo e(translate('Here you can manage time settings to match with your business criteria')); ?>

                        </p>
                   </div> 
                </div>
                <div class="card-body">
                    <div class="shadow-sm p-xxl-20 p-xl-3 p-2 bg-white mb-20">
                        <div class="mb-20">
                            <h4 class="mb-1">
                                <?php echo e(translate('Identity Info')); ?>

                            </h4>
                            <p class="mb-0 fs-12">
                                <?php echo e(translate('Setup your business time zone and format from here')); ?>

                            </p>
                        </div> 
                        <div class="bg-light2 rounded p-xxl-20 p-xl-3 p-3 mb-20">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.identity_type')); ?><span class="form-label-secondary text-danger"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                            </span>
                                        </label>
                                        <select name="identity_type" class="form-control  js-select2-custom">
                                            <option
                                                value="passport" <?php echo e($deliveryMan['identity_type']=='passport'?'selected':''); ?>>
                                                <?php echo e(translate('messages.passport')); ?>

                                            </option>
                                            <option
                                                value="driving_license" <?php echo e($deliveryMan['identity_type']=='driving_license'?'selected':''); ?>>
                                                <?php echo e(translate('messages.driving_license')); ?>

                                            </option>
                                            <option value="nid" <?php echo e($deliveryMan['identity_type']=='nid'?'selected':''); ?>><?php echo e(translate('messages.nid')); ?>

                                            </option>
                                            <option
                                                value="store_id" <?php echo e($deliveryMan['identity_type']=='store_id'?'selected':''); ?>>
                                                <?php echo e(translate('messages.store_id')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-6">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.identity_number')); ?><span class="form-label-secondary text-danger"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                            </span>
                                        </label>
                                        <input type="text" name="identity_number" value="<?php echo e($deliveryMan['identity_number']); ?>"
                                                class="form-control"
                                                placeholder="<?php echo e(translate('messages.Ex:')); ?> DH-23434-LS"
                                                required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-light2 rounded p-xxl-20 p-xl-3 p-3 mb-20">
                            <div class="mb-0">
                                <h4 class="mb-1">
                                    <?php echo e(translate('Update Identity Image')); ?> <span class="text-danger">*</span>
                                </h4>
                                <p class="mb-0 fs-12">
                                    <?php echo e(translate(' Jpg, jpeg, png, gif, webp. Less Than 2MB')); ?> <span class="text-dark">(2:1)</span>
                                </p>
                            </div>
                            <div class="identity_documnet_body multiple_coba-img tabs-slide-wrap position-relative">
                                <div class="tabs-inner pt-3 d-flex gap-3 identity_documnet_wrap" id="coba">
                                    <?php ($identity_images = json_decode($deliveryMan['identity_image'], true) ?? []); ?>
                                    <?php $__currentLoopData = $identity_images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="spartan_item_wrapper size--md existing_image" id="existing_image_<?php echo e($key); ?>">
                                            <div style="position: relative;">
                                                <label class="file_upload" style="width: 100%; height: 100px; border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;">
                                                    <div class="spartan_item_loader" data-spartanindexloader="0" style=" position: absolute; width: 100%; height: 100px; background: rgba(255,255,255, 0.7); z-index: 22; text-align: center; align-items: center; margin: auto; justify-content: center; flex-direction: column; display : none; font-size : 1.7em; color: #CECECE"><i class="fas fa-sync fa-spin"></i></div>
                                                       <img class="img--100 rounded border" style="width: 100%; margin: 0px auto; vertical-align: middle;" src="<?php echo e($deliveryMan['identity_image_full_url'][$key]); ?>">
                                                       <a href="javascript:void(0)" style="right: 3px; top: 3px; background: transparent; border-radius: 3px; width: 30px; height: 30px; line-height: 30px; text-align: center; text-decoration: none; color: rgb(255, 7, 0); position: absolute !important;" data-key="<?php echo e($key); ?>" data-img="<?php echo e(is_array($img) ? $img['img'] : $img); ?>" class="spartan_remove_row remove-existing-image-btn"><i class="tio-add-to-trash"></i></a>
                                                    </div>
                                                </label>
                                                
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="arrow-area">
                                    <div class="button-prev align-items-center">
                                        <button type="button"
                                            class="btn btn-click-prev mr-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                                            <i class="tio-chevron-left fs-24"></i>
                                        </button>
                                    </div>
                                    <div class="button-next align-items-center pt-5">
                                        <button type="button"
                                            class="btn btn-click-next ml-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                                            <i class="tio-chevron-right fs-24"></i>
                                        </button>
                                    </div>
                                </div>
                            </div> 
                        </div>

                    </div>
                    <div class="shadow-sm p-xxl-20 p-xl-3 p-2 bg-white">
                        <div class="mb-20">
                            <h4 class="mb-1">
                                <?php echo e(translate('Account Information')); ?>

                            </h4>
                            <p class="mb-0 fs-12">
                                <?php echo e(translate('Setup your business time zone and format from here')); ?>

                            </p>
                        </div> 
                        <div class="bg-light2 rounded p-xxl-20 p-xl-3 p-3">
                            <div class="row g-3">
                                <div class="col-sm-4">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1"><?php echo e(translate('messages.phone')); ?><span class="form-label-secondary text-danger"
                                            data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                            </span>
                                </label>
                                        <input type="tel" id="phone" name="phone" value="<?php echo e($deliveryMan['phone']); ?>" class="form-control"
                                                placeholder="<?php echo e(translate('messages.Ex:')); ?> 017********"
                                                required>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label" for="signupSrPassword"><?php echo e(translate('messages.password')); ?>

                                            <span class="form-label-secondary" data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters')); ?>"><i class="tio-info text-muted"></i></span>
                                        </label>

                                        <div class="input-group input-group-merge">
                                            <input type="password" class="js-toggle-password form-control" name="password" id="signupSrPassword"                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="<?php echo e(translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters')); ?>"
                                            placeholder="<?php echo e(translate('messages.password_length_placeholder', ['length' => '8+'])); ?>"
                                            aria-label="8+ characters required"
                                            data-msg="Your password is invalid. Please try again."
                                            data-hs-toggle-password-options='{
                                            "target": [".js-toggle-password-target-1"],
                                            "defaultClass": "tio-hidden-outlined",
                                            "showClass": "tio-visible-outlined",
                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                            }'>
                                            <div class="js-toggle-password-target-1 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                    <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="js-form-message form-group mb-0">
                                        <label class="input-label" for="signupSrConfirmPassword"><?php echo e(translate('messages.confirm_password')); ?></label>
                                        <div class="input-group input-group-merge">
                                        <input type="password" class="js-toggle-password form-control" name="confirmPassword" id="signupSrConfirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="<?php echo e(translate('messages.Must_contain_at_least_one_number_and_one_uppercase_and_lowercase_letter_and_symbol,_and_at_least_8_or_more_characters')); ?>"
                                        placeholder="<?php echo e(translate('messages.password_length_placeholder', ['length' => '8+'])); ?>"
                                        aria-label="8+ characters required"
                                                data-msg="Password does not match the confirm password."
                                                data-hs-toggle-password-options='{
                                                "target": [".js-toggle-password-target-2"],
                                                "defaultClass": "tio-hidden-outlined",
                                                "showClass": "tio-visible-outlined",
                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                }'>
                                            <div class="js-toggle-password-target-2 input-group-append">
                                                <a class="input-group-text" href="javascript:;">
                                                <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="btn--container justify-content-end mt-20">
                <button type="reset" id="reset_btn" class="btn btn--reset min-w-120px"><?php echo e(translate('messages.reset')); ?></button>
                    <button type="submit" class="btn btn--primary min-w-120px"><i class="tio-save"></i> <?php echo e(translate('messages.Save Information')); ?></button>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script src="<?php echo e(asset('assets/admin/js/spartan-multi-image-picker.js')); ?>"></script>
<script>
    "use strict";
        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
        $(function () {
            initSpatanImagePicker();
        });

        function initSpatanImagePicker() {
            let existingImages = $("#coba .existing_image").detach();

            let newCoba = $('<div class="tabs-inner pt-3 d-flex gap-3 identity_documnet_wrap" id="coba"></div>');
            
            $("#coba").replaceWith(newCoba);
            
            newCoba.append(existingImages);

            let existingCount = existingImages.length;
            let maxCount = 5 - existingCount;
            console.log('Existing: ' + existingCount + ', Max: ' + maxCount);

            if (maxCount > 0) {
                $("#coba").spartanMultiImagePicker({
                    fieldName: 'identity_image[]',
                    maxCount: maxCount,
                    rowHeight: '100px',
                    groupClassName: 'spartan_item_wrapper size--md',
                    maxFileSize: <?php echo e(MAX_FILE_SIZE); ?> * 1024 * 1024,
                    placeholderImage: {
                        image: '<?php echo e(asset('assets/admin/img/400x400/coba-placeholder.png')); ?>',
                        width: '100%'
                    },
                    dropFileLabel: "Drop Here",
                    onAddRow: function (index, file) {
                        // Handle logic after adding new image if needed
                    },
                    onRenderedPreview: function (index) {

                    },
                    onRemoveRow: function (index) {

                    },
                    onExtensionErr: function (index, file) {
                        toastr.error('Please only input png or jpg type file', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    },
                    onSizeErr: function (index, file) {
                        toastr.error('File size too big', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                });
            }
        }

        $(document).on('click', '.remove-existing-image-btn', function(){
            let key = $(this).data('key');
            let img = $(this).data('img');
            $('#existing_image_' + key).remove();
            $('form').append('<input type="hidden" name="delete_identity_image[]" value="' + img + '">');
            initSpatanImagePicker();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\delivery-man\edit.blade.php ENDPATH**/ ?>