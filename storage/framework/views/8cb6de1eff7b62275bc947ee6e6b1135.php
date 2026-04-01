


<form action="<?php echo e(route('admin.refund.reason-update')); ?>" method="post" class="d-flex flex-column h-100">
         <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>
    <div>
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0"><?php echo e(translate('Edit_Refund_Reason')); ?></h2>
                <button type="button"
                        class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary offcanvas-close fz-15px p-0"
                        aria-label="Close">&times;</button>
        </div>
        <div class="custom-offcanvas-body p-20">
            <div class="bg--secondary rounded p-20 mb-20">

                <?php if($language): ?>
                    <ul class="nav nav-tabs mb-4 border-0">
                        <li class="nav-item">
                            <a class="nav-link lang_link1 active" href="#"
                               id="default-link"><?php echo e(translate('messages.default')); ?></a>
                        </li>
                        <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link lang_link1" href="#"
                                   id="<?php echo e($lang); ?>-link"><?php echo e(\App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')'); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php endif; ?>
                <div class="row">
                    <div class="col-12">
                        <?php if($language): ?>
                            <div class="form-group lang_form1" id="default-form1">
                                <label class="input-label"
                                       for="exampleFormControlInput1"><?php echo e(translate('Refund Reason')); ?>

                                    (<?php echo e(translate('messages.default')); ?>)
                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('Write the related refund reasons that customers must select to request a refund. ')); ?>">
                                            <i class="tio-info text-muted"></i>
                                        </span>
                                    <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                          data-placement="right"
                                          data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                    </span>

                                </label>
                                <textarea id="reason" type="text" rows="5" class="form-control " name="reason[]" maxlength="150"
                                    placeholder="<?php echo e(translate('Ex:Enter_the_reason')); ?>"><?php echo e($reason?->getRawOriginal('reason')); ?></textarea>


                                         <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/150</span>
                            </div>
                            <input type="hidden" name="lang[]" value="default">
                            <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <?php
                            if($reason?->translations){
                                $translate = [];
                                foreach($reason?->translations as $t)
                                {
                                    if($t->locale == $lang && $t->key=="reason"){
                                        $translate[$lang]['reason'] = $t->value;
                                    }
                                }
                            }
                            ?>

                                <div class="form-group d-none lang_form1" id="<?php echo e($lang); ?>-form1">
                                    <label class="input-label"
                                           for="exampleFormControlInput1"><?php echo e(translate('Refund Reason')); ?>

                                        (<?php echo e(strtoupper($lang)); ?>)
                                    </label>



                               <textarea id="reason<?php echo e($lang); ?>" type="text" rows="5" class="form-control" name="reason[]" maxlength="150"
                                            placeholder="<?php echo e(translate('Ex:Enter_the_reason')); ?>"><?php echo e($translate[$lang]['reason'] ?? null); ?></textarea>
                                             <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/150</span>
                                </div>
                                <input type="hidden" name="lang[]" value="<?php echo e($lang); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php endif; ?>
                        <input type="hidden" name="reason_id" value="<?php echo e($reason->id); ?>" />
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
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\settings\partials\_refund_reason_edit.blade.php ENDPATH**/ ?>