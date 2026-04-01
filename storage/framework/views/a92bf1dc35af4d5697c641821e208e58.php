<form action="<?php echo e(route('admin.business-settings.order-cancel-reasons.update')); ?>" method="post" class="d-flex flex-column h-100">
    <?php echo csrf_field(); ?>
    <?php echo method_field('put'); ?>
    <div>
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0"><?php echo e(translate('messages.order_cancellation_reason')); ?> <?php echo e(translate('messages.Update')); ?></h3>
            <button type="button"
                class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary offcanvas-close fz-15px p-0"
                aria-label="Close">&times;</button>
        </div>
        <div class="custom-offcanvas-body p-20">
            <div class="bg--secondary rounded p-20 mb-20">

                <?php ($language = \App\Models\BusinessSetting::where('key', 'language')->first()); ?>
                <?php ($language = $language->value ?? null); ?>
                <?php ($default_lang = 'en'); ?>

                <?php if($language): ?>
                    <?php ($default_lang = json_decode($language)[0]); ?>
                    <ul class="nav nav-tabs mb-4 border-0">
                        <li class="nav-item">
                            <a class="nav-link lang_link1 active" href="#"
                                id="default-link"><?php echo e(translate('messages.default')); ?></a>
                        </li>
                        <?php $__currentLoopData = json_decode($language); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link lang_link1" href="#"
                                    id="<?php echo e($lang); ?>-link"><?php echo e(\App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')'); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
                <input type="hidden" name="reason_id" value="<?php echo e($reason->id); ?>" />

                <div class="row">
                    <div class="col-12">
                        <div class="form-group lang_form1" id="default-form1">
                            <label class="input-label" for="reason"><?php echo e(translate('Order Cancellation Reason')); ?>

                                (<?php echo e(translate('messages.default')); ?>) </label>
                            <input id="reason" class="form-control" name='reason[]'
                                value="<?php echo e($reason?->getRawOriginal('reason')); ?>" type="text">
                        </div>
                        <input type="hidden" name="lang[]" value="default">

                        <?php if($language): ?>
                            <?php $__empty_1 = true; $__currentLoopData = json_decode($language); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                if ($reason?->translations) {
                                    $translate = [];
                                    foreach ($reason?->translations as $t) {
                                        if ($t->locale == $lang && $t->key == 'reason') {
                                            $translate[$lang]['reason'] = $t;
                                        }
                                    }
                                }
                                ?>
                                <div class="form-group d-none lang_form1" id="<?php echo e($lang); ?>-form1">
                                    <label class="input-label" for="reason<?php echo e($lang); ?>"><?php echo e(translate('Order Cancellation Reason')); ?>

                                        (<?php echo e(strtoupper($lang)); ?>)</label>
                                    <input id="reason<?php echo e($lang); ?>" class="form-control" name='reason[]'
                                        placeholder="<?php echo e(translate('Ex:_Item_is_Broken')); ?>"
                                        value="<?php echo e($translate[$lang]['reason']['value'] ?? null); ?>" type="text">
                                </div>
                                <input type="hidden" name="lang[]" value="<?php echo e($lang); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="form-group">
                            <label class="input-label" for="user_type"><?php echo e(translate('messages.user_type')); ?></label>
                            <select name="user_type" id="user_type" class="form-control h--45px" required>
                                <option value=""><?php echo e(translate('messages.select_user_type')); ?></option>
                                <option <?php echo e($reason->user_type == 'admin' ? 'selected' : ''); ?> value="admin">
                                    <?php echo e(translate('messages.admin')); ?></option>
                                <option <?php echo e($reason->user_type == 'store' ? 'selected' : ''); ?> value="store">
                                    <?php echo e(translate('messages.store')); ?></option>
                                <option <?php echo e($reason->user_type == 'customer' ? 'selected' : ''); ?> value="customer">
                                    <?php echo e(translate('messages.customer')); ?></option>
                                <option <?php echo e($reason->user_type == 'deliveryman' ? 'selected' : ''); ?> value="deliveryman">
                                    <?php echo e(translate('messages.deliveryman')); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="align-items-center bg-white bottom-0 d-flex gap-3 justify-content-center mt-auto offcanvas-footer p-3 position-sticky">
        <button type="button" class="btn w-100 btn--secondary offcanvas-close h--40px"><?php echo e(translate('Cancel')); ?></button>
        <button type="submit" class="btn w-100 btn--primary h--40px"><?php echo e(translate('Update')); ?></button>
    </div>
</form>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\settings\partials\_order-cancel-reason-edit.blade.php ENDPATH**/ ?>