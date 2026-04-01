<!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div class="d-flex gap-2 mb-0">
                    <div class="page-header-icon">
                        <img src="<?php echo e(asset('assets/admin/img/delivery-man.png')); ?>" class="w--26" alt="">
                    </div>
                    <div>
                        <h1 class="page-header-title text-break mb-1">
                            <span class="text-dark">
                                <?php echo e(translate('messages.deliveryman_preview')); ?>

                            </span>
                        </h1>

                        <p class="mb-0 fs-12"><?php echo e(translate('messages.Join at')); ?> <?php echo e(\App\CentralLogics\Helpers::time_date_format($deliveryMan?->created_at)); ?>

                        </p>
                    </div>
                </div>

                <?php if($deliveryMan?->application_status != 'approved'): ?>
                    <div class="btn-container">
                        <a class="btn btn-primary text-capitalize font-weight-medium fs-12" data-toggle="tooltip"
                            data-placement="top" data-original-title="<?php echo e(translate('messages.edit')); ?>"
                            href="<?php echo e(route('admin.users.delivery-man.edit', [$deliveryMan['id']])); ?>">
                            <i class="tio-edit"></i>
                            <?php echo e(translate('messages.edit-information')); ?>

                        </a>

                        <?php if($deliveryMan?->application_status != 'denied'): ?>
                            <a class="btn btn-danger text-capitalize font-weight-medium request-alert fs-12"
                                data-url="<?php echo e(route('admin.users.delivery-man.application', [$deliveryMan['id'], 'denied'])); ?>"
                                data-message="<?php echo e(translate('messages.you_want_to_deny_this_application')); ?>"
                                href="javascript:">
                                <?php echo e(translate('messages.reject')); ?>

                            </a>
                        <?php endif; ?>

                        <a class="btn btn-success text-capitalize font-weight-medium request-alert fs-12"
                            data-url="<?php echo e(route('admin.users.delivery-man.application', [$deliveryMan['id'], 'approved'])); ?>"
                            data-message="<?php echo e(translate('messages.you_want_to_approve_this_application')); ?>"
                            href="javascript:">
                            <?php echo e(translate('messages.approve')); ?>

                        </a>
                    </div>
                <?php endif; ?>
            </div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\delivery-man\partials\_page_header.blade.php ENDPATH**/ ?>