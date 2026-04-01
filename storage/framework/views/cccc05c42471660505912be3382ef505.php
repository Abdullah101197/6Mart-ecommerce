

<?php $__env->startSection('title', translate('messages.websocket_settings')); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header mb-20 pb-0">
            <h1 class="font-bold mb-0">
                <?php echo e(translate('Websocket')); ?>

            </h1>
        </div>
        <!-- Page Header -->

        <div class="fs-12 px-3 py-2 rounded bg-info bg-opacity-10 mb-20">
            <div class="d-flex gap-2 align-items-baseline mb-2">
                <span class="text-info lh-1 fs-14">
                    <img src="<?php echo e(asset('assets/admin/img/svg/bulb.svg')); ?>" class="svg" alt="">
                </span>
                <h6 class="font-regular mb-0">
                    <?php echo e(translate('messages.WebSockets enable real-time communication between the server and your app.')); ?>

                </h6>
            </div>
            <ul class="d-flex flex-column gap-1 mb-0">
                <li>
                    <?php echo e(translate('messages.Instantly update business details across active sessions.')); ?>

                </li>
                <li>
                    <?php echo e(translate('messages.Receive live notifications for critical setting changes.')); ?>

                </li>
                <li>
                    <?php echo e(translate('messages.Get instant alerts for subscription status updates.')); ?>

                </li>
            </ul>
            <a target="_blank" href="https://6ammart.app/documentation/admin-application-configuration/3rd-party-setup/" class="theme-clr fs-12 text-underline"><?php echo e(translate('messages.Get Credential Setup')); ?></a>
        </div>

        <form action="<?php echo e(route('admin.business-settings.update-websocket')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="card card-body">
                <div class="d-flex align-items-center justify-content-between gap-2 mb-20">
                    <div class="">
                        <h4 class="mb-1"><?php echo e(translate('messages.Websocket')); ?></h4>
                        <p class="fs-12 mb-1">
                            <?php echo e(translate('messages.Enable real-time updates by configuring your WebSocket connection')); ?>

                        </p>
                    </div>
                     <?php ($websocket = \App\Models\BusinessSetting::where('key', 'websocket_status')->first()); ?>
                    <?php ($websocket = $websocket ? $websocket->value : 0); ?>
                    <label
                        class="toggle-switch toggle-switch-sm mb-0">
                        <input type="checkbox"
                            data-id="websocket"
                            data-type="toggle"
                            data-image-on="<?php echo e(asset('/assets/admin/img/modal/schedule-on.png')); ?>"
                            data-image-off="<?php echo e(asset('/assets/admin/img/modal/schedule-off.png')); ?>"
                            data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.websocket_?')); ?></strong>"
                            data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.websocket_?')); ?></strong>'"
                            data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,Deliveyman_last_location_will_be_recorded_by_websocket.')); ?></p>"
                            data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,Deliveyman_last_location_will_be_recorded_by_default_method.')); ?></p>"
                            class="status toggle-switch-input dynamic-checkbox-toggle"
                            value="1"
                            name="websocket_status" id="websocket"
                            <?php echo e($websocket == 1 ? 'checked' : ''); ?>>
                        <span class="toggle-switch-label text">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
                <div class="__bg-F8F9FC-card mb-20">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <?php ($websocket_url = \App\Models\BusinessSetting::where('key', 'websocket_url')->first()); ?>
                            <div class="form-group mb-0">
                                <label for="websocket_url" class="input-label text-capitalize d-flex gap-1 align-items-center">
                                    <?php echo e(translate('messages.websocket_url')); ?>

                                    <span class="tio-info text-light-gray fs-16" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('Provide the WebSocket server URL used for real-time communication')); ?>">
                                    </span>
                                </label>
                                <input type="text" id="websocket_url" name="websocket_url" value="<?php echo e($websocket_url->value ?? ''); ?>"
                                    class="form-control" placeholder="<?php echo e(translate('messages.Ex_:_ws://178.128.117.0')); ?>"
                                    required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                        <?php ($websocket_port = \App\Models\BusinessSetting::where('key', 'websocket_port')->first()); ?>
                            <div class="form-group mb-0">
                                <label for="websocket_port" class="input-label text-capitalize d-flex gap-1 align-items-center">
                                    <?php echo e(translate('messages.websocket_port')); ?>

                                    <span class="tio-info text-light-gray fs-16" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('Provide the port on which the WebSocket server listens')); ?>">
                                    </span>
                                </label>
                                <input id="websocket_port" type="number" value="<?php echo e($websocket_port->value ?? ''); ?>" name="websocket_port"
                                    class="form-control" placeholder="<?php echo e(translate('messages.Ex_:_6001')); ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset"><?php echo e(translate('messages.reset')); ?></button>
                    <button type="submit" id="submit" class="btn btn--primary"><?php echo e(translate('messages.save')); ?></button>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\websocket-index.blade.php ENDPATH**/ ?>