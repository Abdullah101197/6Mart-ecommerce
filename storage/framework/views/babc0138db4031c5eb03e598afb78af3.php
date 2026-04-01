

<?php $__env->startSection('title', translate('business_setup')); ?>


<?php $__env->startSection('content'); ?>
<?php use App\CentralLogics\Helpers; ?>
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title mr-3">
            <span class="page-header-icon">
                <img src="<?php echo e(asset('assets/admin/img/business.png')); ?>" class="w--26" alt="">
            </span>
            <span>
                <?php echo e(translate('messages.business_settings')); ?>

            </span>
        </h1>
        <?php echo $__env->make('admin-views.business-settings.partials.nav-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
    <!-- End Page Header -->
    <form action="<?php echo e(route('admin.business-settings.update-order')); ?>" method="post" enctype="multipart/form-data"
        id="order-settings-form">
        <?php echo csrf_field(); ?>

        <div class="row g-3">
            <?php ($default_location = Helpers::get_business_settings('default_location')); ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="info-notes-bg px-3 py-2 rounded fz-11  gap-2 align-items-center d-flex mb-20">
                            <img src="<?php echo e(asset('assets/admin/img/info-idea.svg')); ?>" alt="">
                            <span>
                                <?php echo e(translate('All order you can show & manage them from')); ?>

                                <a href="<?php echo e(route('admin.order.list', ['status' => 'all'])); ?>"
                                    class="fz-12px font-semibold info-dark"><?php echo e(translate('All Orders')); ?></a>
                                <?php echo e(translate('page.')); ?>

                            </span>
                        </div>
                        <div class="p-xxl-20 shadow-xxl bg-white rounded mb-20"id="order_type_section">
                                <div class="mb-20">
                                    <div>
                                        <h4 class="mb-1">
                                            <?php echo e(translate('Order Type')); ?>

                                        </h4>
                                        <p class="mb-0 fs-12">
                                            <?php echo e(translate('Which way customer order their food')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="bg-light rounded p-xxl-20 p-3">
                                    <div class="bg-white rounded p-3 border">
                                        <div class="row g-3">
                                            <div class="col-md-6 col-lg-4">
                                                <?php ($home_delivery_status = Helpers::get_business_settings('home_delivery_status')); ?>
                                                <div class="form-group m-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="home_delivery_status-id" value="1" name="home_delivery_status" <?php echo e($home_delivery_status ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label size-checkbox-20" for="home_delivery_status-id">
                                                            <h5 class="mb-1"><?php echo e(translate('Home Delivery')); ?></h5>
                                                            <p class="mb-0 fs-12">
                                                                <?php echo e(translate('If enabled customers can choose Home Delivery option from the customer app and website')); ?>

                                                            </p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4">
                                                <?php ($takeaway_status = Helpers::get_business_settings('takeaway_status')); ?>
                                                <div class="form-group m-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="takeaway_status-id" value="1" name="takeaway_status" <?php echo e($takeaway_status ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label size-checkbox-20" for="takeaway_status-id">
                                                            <h5 class="mb-1"><?php echo e(translate('Takeaway')); ?></h5>
                                                            <p class="mb-0 fs-12">
                                                                <?php echo e(translate('If enabled customers can use Takeaway feature during checkout from the Customer App/Website.')); ?>

                                                            </p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-4">
                                                <?php ($schedule_order = Helpers::get_business_settings('schedule_order')); ?>

                                                <div class="form-group m-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input schedule_order-in" value="1" id="schedule_order-id" name="schedule_order" <?php echo e($schedule_order ? 'checked' : ''); ?>>
                                                        <label class="custom-control-label size-checkbox-20" for="schedule_order-id">
                                                            <h5 class="mb-1"><?php echo e(translate('Scheduled Order')); ?></h5>
                                                            <p class="mb-0 fs-12">
                                                                <?php echo e(translate('If Enabled, customer can choose to order place in their preferable time from Customer App/Website')); ?>

                                                            </p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="order-type-box d-none">
                                        <div class="mt-20">
                                            <?php ($schedule_order_slot_duration = Helpers::get_business_settings('schedule_order_slot_duration')); ?>
                                            <?php ($schedule_order_slot_duration_time_format = Helpers::get_business_settings('schedule_order_slot_duration_time_format')); ?>
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"
                                                    for="schedule_order_slot_duration">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('messages.Time_Interval_for_Scheduled_Delivery')); ?>

                                                        </span>
                                                        <span class="form-label-secondary text-danger"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.By_activating_this_feature,_customers_can_choose_their_suitable_delivery_slot_according_to_a_30-minute_or_1-hour_interval_set_by_the_Admin.')); ?>"><i class="tio-info text-muted"></i></span>
                                                    </span>
                                                </label>
                                                <div class="d-flex border rounded overflow-hidden">
                                                    <input type="number" name="schedule_order_slot_duration" class="form-control rounded-0 border-0"
                                                    id="schedule_order_slot_duration"
                                                    value="<?php echo e($schedule_order_slot_duration ? $schedule_order_slot_duration_time_format == 'hour' ? $schedule_order_slot_duration / 60 : $schedule_order_slot_duration : 0); ?>"
                                                    min="0" required>
                                                    <select   name="schedule_order_slot_duration_time_format" class="custom-select rounded-0 border-0 bg-modal-btn form-control w-90px">
                                                        <option  value="min" <?php echo e($schedule_order_slot_duration_time_format == 'min' ? 'selected' : ''); ?>><?php echo e(translate('Min')); ?></option>
                                                        <option  value="hour" <?php echo e($schedule_order_slot_duration_time_format == 'hour' ? 'selected' : ''); ?>><?php echo e(translate('Hour')); ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex p-2 px-3 rounded gap-2 bg-opacity-warning-10 mt-20">
                                        <i class="tio-info text-warning"></i>
                                        <p class="fz-12px mb-0">
                                            <?php echo e(translate('At least one delivery method select for your business')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-xxl-20 shadow-xxl bg-white rounded mb-20" id="notification_setup_section">
                                <div class="mb-20">
                                    <div>
                                        <h4 class="mb-1">
                                            <?php echo e(translate('Notification Setup')); ?>

                                        </h4>
                                        <p class="mb-0 fs-12">
                                            <?php echo e(translate('Here you can manage the notification settings for this panel')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="bg-light rounded p-xxl-20 p-3">
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-lg-4 access_product_approval">
                                             <?php ($admin_order_notification = Helpers::get_business_settings('admin_order_notification')); ?>
                                            <div class="form-group mb-0">
                                                <span class="mb-2 d-flex align-items-center text-title">
                                                    <span class="text-title">
                                                        <?php echo e(translate('messages.Order_Notification_for_Admin')); ?>

                                                    </span>
                                                    <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.Admin_will_get_a_pop-up_notification_with_sounds_for_any_order_placed_by_customers.')); ?>">
                                                        <i class="tio-info text-muted top01"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox" data-id="aon1" data-type="toggle"
                                                        data-image-on="<?php echo e(asset('/assets/admin/img/modal/order-notification-on.png')); ?>"
                                                        data-image-off="<?php echo e(asset('/assets/admin/img/modal/order-notification-off.png')); ?>"
                                                        data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Order_Notification_for_Admin?')); ?></strong>"
                                                        data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Order_Notification_for_Admin?')); ?></strong>"
                                                        data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_the_Admin_will_receive_a_Notification_for_every_order_placed.')); ?></p>"
                                                        data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_the_Admin_will_NOT_receive_a_Notification_for_every_order_placed.')); ?></p>"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                                        name="admin_order_notification" id="aon1" <?php echo e($admin_order_notification == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4 access_product_approval">
                                            <?php ($order_notification_type = Helpers::get_business_settings('order_notification_type')); ?>
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center"><span
                                                        class="line--limit-1 text-title"><?php echo e(translate('Order_Notification_Type')); ?>

                                                        <span class="form-label-secondary" data-toggle="tooltip"
                                                            data-placement="right"
                                                            data-original-title="<?php echo e(translate('For_Firebase,_a_single_real-time_notification_will_be_sent_upon_order_placement,_with_no_repetition._For_the_Manual_option,_notifications_will_appear_at_10-second_intervals_until_the_order_is_viewed.')); ?>">
                                                            <i class="tio-info text-muted top01"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                                <div class="resturant-type-group bg-white border flex-sm-nowrap gap-1 flex-wrap">
                                                    <label class="form-check form--check w-100">
                                                        <input class="form-check-input" type="radio" value="firebase"
                                                            name="order_notification_type" <?php echo e($order_notification_type ? ($order_notification_type == 'firebase' ? 'checked' : '') : ''); ?>>
                                                        <span class="form-check-label">
                                                    <?php echo e(translate('firebase')); ?>

                                                </span>
                                                    </label>
                                                    <label class="form-check form--check w-100">
                                                        <input class="form-check-input" type="radio" value="manual"
                                                            name="order_notification_type" <?php echo e($order_notification_type ? ($order_notification_type == 'manual' ? 'checked' : '') : ''); ?>>
                                                        <span class="form-check-label">
                                                    <?php echo e(translate('manual')); ?>

                                                </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fs-12 text-dark px-3 py-2 rounded bg-warning-10 mt-20">
                                    <div class="d-flex gap-2 mb-1">
                                        <span class="text-warning lh-1 fs-14">
                                            <i class="tio-info"></i>
                                        </span>
                                        <span>
                                            <?php echo e(translate('To receive order notifications properly, select the notification type based on your preference:')); ?>

                                        </span>
                                    </div>
                                    <ul class="mb-0 gap-1 d-flex flex-column">
                                        <li><?php echo e(translate('Manual Notification: You need to send order notifications manually for each order update.')); ?> </li>
                                        <li>
                                            <?php echo e(translate('Firebase Notification: Order notifications will be sent automatically. Ensure')); ?> <a target="_blank" style="text-decoration: underline; color: #245BD1;" href="<?php echo e(route('admin.business-settings.fcm-config')); ?>" class="font-semibold text-primary"><?php echo e(translate('Firebase Configuration')); ?></a>  <?php echo e(translate('is completed and notification messages are set up in the Notification Message section.')); ?>

                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="p-xxl-20 p-3 shadow-sm bg-white rounded mb-20" id="free_delivery_section">
                                <div class="">
                                    <?php ($admin_free_delivery_status = Helpers::get_business_settings('admin_free_delivery_status')); ?>

                                    <div class="d-flex justify-content-between mb-20">
                                        <div>
                                            <h4 class="card-title fs-16 mb-1 text--title"><?php echo e(translate('Free Delivery Setup')); ?></h4>
                                            <p class="mb-0 fs-12">
                                                <?php echo e(translate('Enable this option to give customers a free delivery offer.')); ?>

                                            </p>
                                        </div>
                                        <label class="form-label d-flex justify-content-between text-capitalize mb-1"
                                               for="admin_free_delivery_status">
                                            <span class="toggle-switch toggle-switch-sm pr-sm-3">
                                                <input type="checkbox" data-id="admin_free_delivery_status" data-type="toggle"
                                                    data-image-on="<?php echo e(asset('/assets/admin/img/modal/info-warning.png')); ?>"
                                                    data-image-off="<?php echo e(asset('/assets/admin/img/modal/info-warning.png')); ?>"
                                                    data-title-on="<strong><?php echo e(translate('messages.Are you sure to enable Free Delivery?')); ?></strong>"
                                                    data-title-off="<strong><?php echo e(translate('messages.Are you sure to disable Free Delivery?')); ?></strong>"
                                                    data-text-on="<?php echo e(translate('After enable customers will not be charged a delivery fee for eligible orders.')); ?>"
                                                    data-text-off="<?php echo e(translate('After disable delivery charges will apply to all new orders based on your delivery settings.')); ?>"
                                                    data-footer-text-on="<div class='text-center text-info mt-5'><?php echo e(translate('Note : Don’t forget to save the information before leaving this page ')); ?></div>"
                                                    data-footer-text-off="<div class='text-center text-info mt-5'><?php echo e(translate('Note : Don’t forget to save the information before leaving this page ')); ?></div>"
                                                    class="status toggle-switch-input dynamic-checkbox-toggle"
                                                    name="admin_free_delivery_status" id="admin_free_delivery_status" value="1"
                                                    <?php echo e($admin_free_delivery_status ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text mb-0"><span
                                                        class="toggle-switch-indicator"></span></span>
                                            </span>
                                        </label>
                                    </div>


                                    <div class="bg-light rounded p-xxl-20 p-3">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-sm-6 col-lg-6">
                                                <?php ($free_delivery_over = Helpers::get_business_settings('free_delivery_over')); ?>
                                                <?php ($admin_free_delivery_option = Helpers::get_business_settings('admin_free_delivery_option')); ?>

                                                <div class="form-group mb-0">
                                                    <label
                                                        class="input-label text-capitalize d-flex alig-items-center add_text_mute <?php echo e($admin_free_delivery_status ? '' : 'text-muted'); ?> "><span
                                                            class="line--limit-1"><?php echo e(translate('Choose Free Delivery Option')); ?>

                                                        </span>
                                                            </label>
                                                            <div class="resturant-type-group gap-3 border bg-white">
                                                                <label class="form-check form--check">
                                                                    <input class="form-check-input radio-trigger" type="radio" <?php echo e($admin_free_delivery_status ? '' : 'disabled'); ?>

                                                                    value="free_delivery_to_all_store"
                                                                        name="admin_free_delivery_option" <?php echo e($admin_free_delivery_option == 'free_delivery_to_all_store' ? 'checked' : ''); ?>>
                                                                    <span class="form-check-label">
                                                                        <?php echo e(translate('Set free delivery for all store')); ?>

                                                                    </span>
                                                                </label>
                                                                <label class="form-check form--check">
                                                                    <input
                                                                        class="form-check-input radio-trigger"
                                                                        type="radio" <?php echo e($admin_free_delivery_status ? '' : 'disabled'); ?> value="free_delivery_by_order_amount"
                                                                        name="admin_free_delivery_option" <?php echo e($admin_free_delivery_option == 'free_delivery_by_order_amount' || $admin_free_delivery_option == null ? 'checked' : ''); ?>>
                                                                    <span class="form-check-label">
                                                                <?php echo e(translate('Set Specific Criteria')); ?>

                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>



                                            <div id="show_free_delivery_over"
                                                 class="col-sm-6 col-lg-6 <?php echo e($admin_free_delivery_option == 'free_delivery_by_order_amount' || $admin_free_delivery_option == null ? '' : 'd-none'); ?>">
                                                <div class="form-group mb-0">
                                                    <label
                                                        class="form-label d-flex justify-content-between text-capitalize mb-1 add_text_mute <?php echo e($admin_free_delivery_status ? '' : 'text-muted'); ?> "
                                                        for="">
                                                        <span
                                                            class="line--limit-1"><?php echo e(translate('messages.free_delivery_over')); ?>

                                                            (<?php echo e(Helpers::currency_symbol()); ?>) <span
                                                                class="text-danger"><span class="form-label-secondary"
                                                                                        data-toggle="tooltip" data-placement="right"
                                                                                        data-original-title="<?php echo e(translate('messages.Set_a_minimum_order_value_for_automated_free_delivery._If_the_minimum_amount_is_exceeded,_the_Delivery_Fee_is_deducted_from_Admin’s_commission_and_added_to_Admin’s_expense.')); ?>">
                                                                                        <i class="tio-info text-muted top-01"></i>
                                                                                    </span>
                                                                </span>
                                                        </span>
                                                    </label>
                                                    <input type="number" name="free_delivery_over" class="form-control"
                                                           id="free_delivery_over" placeholder="<?php echo e(translate('messages.Ex:_10')); ?>"
                                                           value="<?php echo e($free_delivery_over ? $free_delivery_over : 0); ?>"
                                                           min="1" step="<?php echo e(App\CentralLogics\Helpers::getDecimalPlaces()); ?>" <?php echo e($admin_free_delivery_option == 'free_delivery_by_order_amount' ? 'required' : ''); ?> <?php echo e($admin_free_delivery_status ? '' : 'readonly'); ?>>
                                                </div>
                                            </div>
                                            <div id="show_text_for_all_store_free_delivery"
                                                 class="col-sm-6 col-lg-6 <?php echo e($admin_free_delivery_option == 'free_delivery_to_all_store' ? '' : ' d-none'); ?>">
                                                <div class="alert fs-13 alert-primary-light text-dark mb-0  mt-md-0 add_text_mute text-muted"
                                                     role="alert">
                                                    <img src="<?php echo e(asset('/assets/admin/img/lnfo_light.png')); ?>" alt="">
                                                    <?php echo e(translate('Free delivery is active for all stores. Cost bearer for the free delivery is')); ?>

                                                    <strong><?php echo e(translate('Admin')); ?></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-xxl-20 p-3 shadow-sm bg-white rounded mb-20" id="extra_packaging_section">
                                <div class="">
                                    <div class="row g-1 align-items-center">
                                        <div class="col-xxl-9 col-lg-8 col-md-7 col-sm-6">
                                            <div>
                                                <h4 class="mb-1">
                                                    <?php echo e(translate('Enable Extra Packaging Charge')); ?>

                                                </h4>
                                                <p class="mb-0 fs-12">
                                                    <?php echo e(translate('Adds an extra fee for orders that need additional protection, such as fragile or bulky items.')); ?>

                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-lg-4 col-md-5 col-sm-6">
                                            <div class="">
                                                <?php ($extra_packaging_charge_status = Helpers::get_business_settings('extra_packaging_charge_status')); ?>
                                                <div class="form-group mb-0">
                                                    <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                        <span class="pr-1 d-flex align-items-center switch--label">
                                                            <span class="line--limit-1">
                                                                <?php echo e(translate('messages.Status')); ?>

                                                            </span>
                                                        </span>
                                                        <input type="checkbox" class="status toggle-switch-input" name="extra_packaging_charge_status" value="1" <?php echo e($extra_packaging_charge_status ? 'checked' : ''); ?> id="extra_packaging_charge_status">
                                                        <span class="toggle-switch-label text">
                                                            <span class="toggle-switch-indicator"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ($extra_packaging_data = Helpers::get_business_settings('extra_packaging_data')); ?>

                                <div class="mb-0 mt-20 access_product_approval" id="extra_packaging_charge_options">
                                    <label class="mb-2 input-label text-capitalize d-flex alig-items-center" for="">
                                        <?php echo e(translate('Enable Extra Packaging Charge')); ?>

                                        <span class="text-danger">*</span>
                                        <span class="form-label-secondary text-danger"
                                        data-toggle="tooltip" data-placement="right"
                                        data-original-title="<?php echo e(translate('messages.After_saving_information,_sellers_will_get_the_option_to_offer_extra_packaging_charge_to_the_customer')); ?>"><i class="tio-info text-muted ps--3"></i></span>
                                    </label>
                                    <div class="rounded border py-2 min-h-45px bg-white px-3">
                                        <div class="row g-lg-3 g-1">
                                            <?php $__currentLoopData = config('module.module_type'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($value != 'parcel' && $value != 'rental'): ?>
                                                    <div class="col-lg-3 col-sm-6">
                                                        <div class="custom-control custom-checkbox pt-1">
                                                            <input class="custom-control-input extra-packaging-option" type="checkbox" <?php echo e(isset($extra_packaging_data[$value]) && $extra_packaging_data[$value] == 1 ? 'checked' : ''); ?> id="inlineCheckbox<?php echo e($key); ?>" value="1" name="<?php echo e($value); ?>">
                                                            <label class="custom-control-label" for="inlineCheckbox<?php echo e($key); ?>"><?php echo e(translate($value)); ?></label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-xxl-20 p-3 shadow-sm bg-white rounded mb-20" id="other_setup_section">
                                <div class="mb-20">
                                    <div>
                                        <h4 class="mb-1">
                                            <?php echo e(translate('Other Setup')); ?>

                                        </h4>
                                        <p class="mb-0 fs-12">
                                            <?php echo e(translate('Setup your business time zone and format from here')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="bg-light rounded p-xxl-20 p-3">
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-lg-4">

                                            <?php ($prescription_order_status = Helpers::get_business_settings('prescription_order_status')); ?>
                                            <div class="form-group mb-0">
                                                <span class="mb-2 d-flex align-items-center">
                                                        <span class="text-title">
                                                            <?php echo e(translate('messages.Place_Order_by_Prescription')); ?>

                                                        </span>
                                                        <span class="form-label-secondary text-danger d-flex"
                                                            data-toggle="tooltip" data-placement="right"
                                                            data-original-title="<?php echo e(translate('messages.With_this_feature,_customers_can_place_an_order_by_uploading_prescription._Stores_can_enable/disable_this_feature_from_the_store_settings_if_needed.')); ?>">
                                                            <i class="tio-info text-muted ps--3"></i>
                                                        </span>
                                                    </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1 text-title">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>

                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="prescription_order_status"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/prescription-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/prescription-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Place_Order_by_Prescription?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Place_Order_by_Prescription?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If you enable this, customers can place an order by simply uploading their prescriptions in the Pharmacy module from the Customer App or Website. Stores can enable/disable this feature from store settings if needed.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If disabled, this feature will be hidden from the Customer App, Website, and Store App & Panel.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                                           value="1"
                                                        name="prescription_order_status" id="prescription_order_status"
                                                        <?php echo e($prescription_order_status == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                             <?php ($odc = Helpers::get_business_settings('order_delivery_verification')); ?>
                                            <div class="form-group mb-0">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-title">
                                                        <?php echo e(translate('messages.order_delivery_verification')); ?>

                                                    </span>
                                                    <span class="form-label-secondary text-danger d-flex"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.When_a_deliveryman_arrives_for_delivery,_Customers_will_get_a_4-digit_verification_code_on_the_order_details_section_in_the_Customer_App_and_needs_to_provide_the_code_to_the_delivery_man_to_verify_the_order.')); ?>">
                                                        <i class="tio-info text-muted ps--3"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1 text-title">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="odc1"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/order-delivery-verification-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/order-delivery-verification-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Delivery_Verification?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Delivery_Verification?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If you enable this, the Deliveryman has to verify the order during delivery through a 4-digit verification code.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If you disable this, the Deliveryman will deliver the order and update the status. He doesn’t need to verify the order with any code.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"

                                                           value="1"
                                                        name="odc" id="odc1" <?php echo e($odc == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-4 access_product_approval">

                                            <?php ($order_confirmation_model = Helpers::get_business_settings('order_confirmation_model') ?? 'deliveryman'); ?>
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex alig-items-center">
                                                    <span class="line--limit-1"><?php echo e(translate('messages.Who_Will_Confirm_Order?')); ?>

                                                        <span class="form-label-secondary" data-toggle="tooltip"
                                                              data-placement="right"
                                                              data-original-title="<?php echo e(translate('messages.After_a_customer_order_placement,_Admin_can_define_who_will_confirm_the_order_first-_Deliveryman_or_Store?_For_example,_if_you_choose_‘Delivery_man’,_the_deliveryman_nearby_will_confirm_the_order_and_forward_it_to_the_related_store_to_process_the_order._It_works_vice-versa_if_you_choose_‘Store’.')); ?>">
                                                            <i class="tio-info text-muted ps--3"></i>
                                                        </span>
                                                    </span>
                                                </label>
                                                <div class="resturant-type-group bg-white border flex-sm-nowrap flex-wrap">
                                                    <label class="form-check form--check w-100">
                                                        <input class="form-check-input" type="radio" value="store"
                                                               name="order_confirmation_model" id="order_confirmation_model" <?php echo e($order_confirmation_model == 'store' ? 'checked' : ''); ?>>
                                                        <span class="form-check-label">
                                                            <?php echo e(translate('messages.store')); ?>

                                                        </span>
                                                    </label>
                                                    <label class="form-check form--check w-100">
                                                        <input class="form-check-input" type="radio" value="deliveryman"
                                                               name="order_confirmation_model" id="order_confirmation_model2" <?php echo e($order_confirmation_model == 'deliveryman' ? 'checked' : ''); ?>>
                                                        <span class="form-check-label">
                                                            <?php echo e(translate('messages.deliveryman')); ?>

                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php echo $__env->make('admin-views.partials._floating-submit-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-4">
            <div class="card" id="order_cancellation_section">
                <div class="card-body">
                    <div class="mb-20">
                        <div>
                            <h4 class="mb-1">
                                <?php echo e(translate('Setup Order Cancellation Messages')); ?>

                            </h4>
                            <p class="mb-0 fs-12">
                                <?php echo e(translate('Set up cancellation messages here to allow customers to select a reason when canceling an order')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="bg-light rounded p-xxl-20 p-3 mb-20">
                        <form action="<?php echo e(route('admin.business-settings.order-cancel-reasons.store')); ?>" method="post">
                            <?php echo csrf_field(); ?>

                            <?php if($language): ?>
                                <div class="js-nav-scroller tabs-slide-wrap tabs-slide-space position-relative hs-nav-scroller-horizontal">
                                    <ul class="nav nav-tabs tabs-inner nav--tabs mb-4 border-bottom">
                                        <li class="nav-item">
                                            <a class="nav-link lang_link active" href="#"
                                                id="default-link"><?php echo e(translate('Default')); ?></a>
                                        </li>
                                        <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link lang_link" href="#"
                                                    id="<?php echo e($lang); ?>-link"><?php echo e(Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')'); ?></a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <div class="arrow-area">
                                        <div class="button-prev align-items-center">
                                            <button type="button"
                                                class="btn btn-click-prev mr-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                                                <i class="tio-chevron-left fs-24"></i>
                                            </button>
                                        </div>
                                        <div class="button-next align-items-center">
                                            <button type="button"
                                                class="btn btn-click-next ml-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                                                <i class="tio-chevron-right fs-24"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="row g-3">
                                <div class="col-sm-6 lang_form default-form">
                                    <label for="order_cancellation" class="form-label"><?php echo e(translate('Order Cancellation Reason')); ?>

                                        (<?php echo e(translate('messages.default')); ?>)</label>
                                    <input type="text" class="form-control h--45px" name="reason[]"
                                        id="order_cancellation" placeholder="<?php echo e(translate('Ex:_Item_is_Broken')); ?>">
                                    <input type="hidden" name="lang[]" value="default">
                                </div>
                                <?php if($language): ?>
                                    <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-sm-6 d-none lang_form" id="<?php echo e($lang); ?>-form">
                                            <label for="order_cancellation<?php echo e($lang); ?>" class="form-label"><?php echo e(translate('Order Cancellation Reason')); ?>

                                                (<?php echo e(strtoupper($lang)); ?>)</label>
                                            <input type="text" class="form-control h--45px" name="reason[]"
                                                id="order_cancellation<?php echo e($lang); ?>" placeholder="<?php echo e(translate('Ex:_Item_is_Broken')); ?>">
                                            <input type="hidden" name="lang[]" value="<?php echo e($lang); ?>">
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <div class="col-sm-6">
                                    <label for="user_type" class="form-label d-flex">
                                        <span class="line--limit-1"><?php echo e(translate('User Type')); ?> </span>
                                        <span class="form-label-secondary text-danger d-flex align-items-center" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="<?php echo e(translate('When this field is active, user can cancel an order with proper reason.')); ?>">
                                            <i class="tio-info text-muted ps--3 top-01"></i>
                                        </span>
                                    </label>
                                    <select id="user_type" name="user_type" class="form-control custom-select h--45px" required>
                                        <option value=""><?php echo e(translate('messages.select_user_type')); ?></option>
                                        <option value="admin"><?php echo e(translate('messages.admin')); ?></option>
                                        <option value="store"><?php echo e(translate('messages.store')); ?></option>
                                        <option value="customer"><?php echo e(translate('messages.customer')); ?></option>
                                        <option value="deliveryman"><?php echo e(translate('messages.deliveryman')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="btn--container justify-content-end mt-20">
                                <button type="reset" class="btn btn--reset"><?php echo e(translate('messages.reset')); ?></button>
                                <button type="<?php echo e(env('APP_MODE') != 'demo' ? 'submit' : 'button'); ?>"
                                    class="btn btn--primary call-demo"><?php echo e(translate('Submit')); ?></button>
                            </div>
                        </form>
                    </div>
                    <div class="card border-0">
                        <div class="card-body mb-3">
                            <div class="d-flex gap-2 flex-wrap justify-content-between align-items-center mb-20">
                                <div class="mx-1">
                                    <h4 class="fs-16 text-title mb-0">
                                        <?php echo e(translate('messages.order_cancellation_reason_list')); ?>

                                    </h4>
                                </div>
                                <div class="d-flex align-items-center gap-lg-3 gap-2 flex-md-nowrap flex-wrap">
                                    <select id="type" name="type" class="form-control custom-select py-1 h-40px set-filter" data-url="<?php echo e(url()->full()); ?>" data-filter="type">
                                        <option value="all" <?php echo e(request('type') == 'all' ? 'selected' : ''); ?>><?php echo e(translate('messages.all_user')); ?></option>
                                        <option value="admin" <?php echo e(request('type') == 'admin' ? 'selected' : ''); ?>><?php echo e(translate('messages.admin')); ?></option>
                                        <option value="store" <?php echo e(request('type') == 'store' ? 'selected' : ''); ?>><?php echo e(translate('messages.store')); ?></option>
                                        <option value="customer" <?php echo e(request('type') == 'customer' ? 'selected' : ''); ?>><?php echo e(translate('messages.customer')); ?></option>
                                        <option value="deliveryman" <?php echo e(request('type') == 'deliveryman' ? 'selected' : ''); ?>><?php echo e(translate('messages.deliveryman')); ?></option>
                                    </select>
                                    <form class="search-form order-search-wrap min--260">
                                        <!-- Search -->
                                        <div class="input-group input--group">
                                            <input id="" type="search" name="search" class="form-control h--40px" placeholder="Search here" value="">
                                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                                        </div>
                                        <!-- End Search -->
                                    </form>
                                </div>
                            </div>
                            <!-- Table -->
                            <div class="card-body p-0">
                                <div class="table-responsive datatable-custom">
                                    <table id="columnSearchDatatable"
                                        class="table table-borderless table-thead-bordered table-align-middle"
                                        data-hs-datatables-options='{
                                    "isResponsive": false,
                                    "isShowPaging": false,
                                    "paging":false,
                                }'>
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="border-0"><?php echo e(translate('messages.SL')); ?></th>
                                                <th class="border-0"><?php echo e(translate('messages.Reason')); ?></th>
                                                <th class="border-0"><?php echo e(translate('messages.User Type')); ?></th>
                                                <th class="border-0"><?php echo e(translate('messages.status')); ?></th>
                                                <th class="border-0 text-center"><?php echo e(translate('messages.action')); ?></th>
                                            </tr>
                                        </thead>

                                        <tbody id="table-div">
                                            <?php $__currentLoopData = $reasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="text-dark fs-14"><?php echo e($key + $reasons->firstItem()); ?></td>

                                                    <td>
                                                        <span class="d-block font-size-sm text-body min-w-176px line--limit-2 text-dark fs-14" title="<?php echo e($reason->reason); ?>">
                                                            <?php echo e(Str::limit($reason->reason, 25, '...')); ?>

                                                        </span>
                                                    </td>
                                                    <td class="text-dark fs-14"><?php echo e(Str::title($reason->user_type)); ?></td>
                                                    <td>
                                                        <label class="toggle-switch toggle-switch-sm"
                                                            for="stocksCheckbox<?php echo e($reason->id); ?>">
                                                            <input type="checkbox"
                                                                    data-url="<?php echo e(route('admin.business-settings.order-cancel-reasons.status', [$reason['id'], $reason->status ? 0 : 1])); ?>"
                                                                class="toggle-switch-input redirect-url"
                                                                id="stocksCheckbox<?php echo e($reason->id); ?>"
                                                                <?php echo e($reason->status ? 'checked' : ''); ?>>
                                                            <span class="toggle-switch-label">
                                                                <span class="toggle-switch-indicator"></span>
                                                            </span>
                                                        </label>
                                                    </td>

                                                    <td>
                                                        <div class="btn--container justify-content-center">

                                                            <a class="btn btn-sm btn-outline-base action-btn edit-reason offcanvas-trigger data-info-show"
                                                                title="<?php echo e(translate('messages.edit')); ?>"
                                                                data-url="<?php echo e(route('admin.business-settings.order-cancel-reasons.edit', [$reason['id']])); ?>"
                                                                data-id="<?php echo e($reason['id']); ?>"
                                                                data-target="#offcanvas__customBtn"
                                                                href="javascript:"><i class="tio-edit"></i>
                                                            </a>


                                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert"
                                                                href="javascript:"
                                                                data-id="order-cancellation-reason-<?php echo e($reason['id']); ?>"
                                                                data-message="<?php echo e(translate('messages.If_you_want_to_delete_this_reason,_please_confirm_your_decision.')); ?>"
                                                                title="<?php echo e(translate('messages.delete')); ?>">
                                                                <i class="tio-delete-outlined"></i>
                                                            </a>
                                                            <form
                                                                action="<?php echo e(route('admin.business-settings.order-cancel-reasons.destroy', $reason['id'])); ?>"
                                                                method="post" id="order-cancellation-reason-<?php echo e($reason['id']); ?>">
                                                                <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- End Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->


    <div class="modal fade" id="confirmation_modal_free_delivery_by_order_amount" tabindex="-1" role="dialog"
         aria-labelledby="modalLabel" aria-hidden="true">
        <div class=" modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img src="<?php echo e(asset('/assets/admin/img/subscription-plan/package-status-disable.png')); ?>"
                                     class="mb-20">

                                <h5 class="modal-title"></h5>
                            </div>
                            <div class="text-center">
                                <h3> <?php echo e(translate('Do You Want Active “Set Specific Criteria”?')); ?></h3>
                                <div>
                                    <p><?php echo e(translate('Are you sure to active “Set Specific Criteria”? If you active this delivery charge will not added to order when customer order more then your “Free Delivery Over” amount.')); ?>

                                    </p>
                                </div>
                            </div>



                            <div class="btn--container justify-content-center">
                                <button data-dismiss="modal"
                                        class="btn btn-soft-secondary min-w-120"><?php echo e(translate("Cancel")); ?></button>
                                <button data-dismiss="modal" type="button" id="confirmBtn_free_delivery_by_order_amount"
                                        class="btn btn--primary min-w-120"><?php echo e(translate('Yes')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="confirmation_modal_free_delivery_to_all_store" tabindex="-1" role="dialog"
         aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog-centered modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-5 pt-0">
                    <div class="max-349 mx-auto mb-20">
                        <div>
                            <div class="text-center">
                                <img src="<?php echo e(asset('/assets/admin/img/subscription-plan/package-status-disable.png')); ?>"
                                     class="mb-20">

                                <h5 class="modal-title"></h5>
                            </div>
                            <div class="text-center">
                                <h3> <?php echo e(translate('Do You Want Active “Free Delivery for All Stores”?')); ?></h3>
                                <div>
                                    <p><?php echo e(translate('Are you sure to active “Free delivery order for all Stores”? If you active this no delivery charge will added to order and the cost will be added to you.')); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="btn--container justify-content-center">
                                <button data-dismiss="modal"
                                        class="btn btn-soft-secondary min-w-120"><?php echo e(translate("Cancel")); ?></button>
                                <button data-dismiss="modal" type="button" id="confirmBtn_free_delivery_to_all_store"
                                        class="btn btn--primary min-w-120"><?php echo e(translate('Yes')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
    <div id="offcanvas__customBtn" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div id="data-view" class="h-100">
        </div>
    </div>
    <div id="global_guideline_offcanvas"
        class="custom-offcanvas d-flex flex-column justify-content-between global_guideline_offcanvas">
        <!-- Guidline Offcanvas -->
        
        <div>
            <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
                <h3 class="mb-0"><?php echo e(translate('messages.Order Settings Guideline')); ?></h3>
                <button type="button"
                    class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary offcanvas-close fz-15px p-0"
                    aria-label="Close">&times;</button>
            </div>

            <div class="custom-offcanvas-body offcanvas-height-100 py-3 px-md-4 px-3">
                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#order_type_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Order Type')); ?></span>
                        </button>
                        <a href="#order_type_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3 show" id="order_type_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Order Type')); ?></h5>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.This feature allows customers to place orders based on how they want to receive or consume their items.')); ?>

                                </p>
                                <ul class="fs-12">
                                    <li><strong><?php echo e(translate('messages.Home Delivery')); ?>:</strong> <?php echo e(translate('messages.It allows customers to place an order and have it delivered to their specified address. Delivery is handled by a deliveryman or third-party service. It supports real-time order tracking.')); ?></li>
                                    <li><strong><?php echo e(translate('messages.Takeaway')); ?>:</strong> <?php echo e(translate('messages.It allows customers to place an order in advance and pick it up directly from the vendor. No delivery charge is applied. The order is prepared for pickup from the vendor.')); ?></li>
                                    <li><strong><?php echo e(translate('messages.Scheduled')); ?>:</strong> <?php echo e(translate('messages.It allows customers to place an order to be delivered to their specific address at a selected time. Delivery is handled by a deliveryman or third-party service. It supports real-time order tracking.')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#notification_setup_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Notification Setup')); ?></span>
                        </button>
                        <a href="#notification_setup_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3" id="notification_setup_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Notification Setup')); ?></h5>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.The Admin Notification Setup allows the administrator to configure how system notifications are sent and managed. Admin notifications can be delivered either manually or through Firebase, depending on the selected configuration.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#free_delivery_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Free Delivery Setup')); ?></span>
                        </button>
                        <a href="#free_delivery_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3" id="free_delivery_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Free Delivery Setup')); ?></h5>
                                <ul class="fs-12">
                                    <li><strong><?php echo e(translate('messages.Free delivery over ($)')); ?>:</strong> <?php echo e(translate('messages.Admin can define the minimum order amount for the customer to receive free shipping automatically.')); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#extra_packaging_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Extra Packaging Charge')); ?></span>
                        </button>
                        <a href="#extra_packaging_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3" id="extra_packaging_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Extra Packaging Charge')); ?></h5>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.This option lets you select which modules require extra packaging fees.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#other_setup_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Other Setup')); ?></span>
                        </button>
                        <a href="#other_setup_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3" id="other_setup_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Other Setup')); ?></h5>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.Applying delivery verification methods to ensure successful delivery, and selecting the order confirmation model to define how orders are approved and processed within the system.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
                    <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                        <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                            type="button" data-toggle="collapse" data-target="#order_cancellation_guide"
                            aria-expanded="true">
                            <div
                                class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                <i class="tio-down-ui"></i>
                            </div>
                            <span
                                class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Set up Order Cancellation Messages')); ?></span>
                        </button>
                        <a href="#order_cancellation_section"
                            class="text-info text-underline fs-12 text-nowrap offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
                    </div>
                    <div class="collapse mt-3" id="order_cancellation_guide">
                        <div class="card card-body">
                            <div class="">
                                <h5 class="mb-3"><?php echo e(translate('Set up Order Cancellation Messages')); ?></h5>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.This section allows the admin to manage order cancellation reasons for different user types. You can:')); ?>

                                </p>
                                <ul class="fs-12">
                                    <li><?php echo e(translate('messages.Create and edit cancellation reasons')); ?></li>
                                    <li><?php echo e(translate('messages.Set a reason as active or inactive')); ?></li>
                                    <li><?php echo e(translate('messages.Mark a default cancellation reason')); ?></li>
                                </ul>
                                <p class="fs-12 mb-0">
                                    <?php echo e(translate('messages.These reasons will be shown to users when they try to cancel an order.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script_2'); ?>
    <script>
        $(document).ready(function() {
            $('.offcanvas-close-btn').on('click', function() {
                $('.offcanvas-close').trigger('click');
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script_2'); ?>
    <script src="<?php echo e(asset('assets/admin/js/view-pages/business-settings-order-page.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/view-pages/offcanvas-edit.js')); ?>"></script>

    <script>
        "use strict";
        $(document).ready(function () {
            let selectedRadio = null;

            // Function to update field validation based on selected option and status
            function updateFieldValidation() {
                const isEnabled = $('#admin_free_delivery_status').is(':checked');
                const selectedValue = $('input[name="admin_free_delivery_option"]:checked').val();

                if (!isEnabled) {
                    // When disabled, remove validation and make readonly
                    $('#free_delivery_over').removeAttr('required').prop('readonly', true);
                    $('.radio-trigger').prop('disabled', true);
                } else {
                    // When enabled, set validation based on selected radio
                    $('.radio-trigger').prop('disabled', false);

                    if (selectedValue === 'free_delivery_by_order_amount') {
                        $('#show_free_delivery_over').removeClass('d-none');
                        $('#show_text_for_all_store_free_delivery').addClass('d-none');
                        $('#free_delivery_over').prop('readonly', false).prop('required', true);
                    } else if (selectedValue === 'free_delivery_to_all_store') {
                        $('#show_free_delivery_over').addClass('d-none');
                        $('#show_text_for_all_store_free_delivery').removeClass('d-none');
                        $('#free_delivery_over').val('').prop('required', false).prop('readonly', true);
                    }
                }

                // Update text-muted classes
                if (isEnabled) {
                    $('.add_text_mute').removeClass('text-muted');
                } else {
                    $('.add_text_mute').addClass('text-muted');
                }
            }

            // Handle radio button clicks
            $(".radio-trigger").on("click", function (event) {
                event.preventDefault();
                selectedRadio = this;
                let selectedValue = $(this).val();

                if (selectedValue === 'free_delivery_to_all_store') {
                    $("#confirmation_modal_free_delivery_to_all_store").modal("show");
                } else {
                    $("#confirmation_modal_free_delivery_by_order_amount").modal("show");
                }
            });

            // Handle confirmation for "free delivery to all store"
            $("#confirmBtn_free_delivery_to_all_store").on("click", function () {
                if (selectedRadio) {
                    selectedRadio.checked = true;
                    updateFieldValidation();
                }
                $("#confirmation_modal_free_delivery_to_all_store").modal("hide");
            });

            // Handle confirmation for "free delivery by order amount"
            $("#confirmBtn_free_delivery_by_order_amount").on("click", function () {
                if (selectedRadio) {
                    selectedRadio.checked = true;
                    updateFieldValidation();
                }
                $("#confirmation_modal_free_delivery_by_order_amount").modal("hide");
            });

            // Handle toggle switch change - using multiple event listeners to catch all scenarios
            $('#admin_free_delivery_status').on('change', function() {
                // Use setTimeout to ensure this runs after any other handlers
                setTimeout(function() {
                    updateFieldValidation();
                }, 100);
            });

            // Also listen for click events on the toggle
            $('#admin_free_delivery_status').on('click', function() {
                setTimeout(function() {
                    updateFieldValidation();
                }, 100);
            });

            // Listen for changes on the parent toggle switch span (in case the event bubbles from there)
            $('.toggle-switch-input').on('change', function() {
                setTimeout(function() {
                    updateFieldValidation();
                }, 100);
            });

            // Initialize validation state on page load
            setTimeout(function() {
                updateFieldValidation();
            }, 200);

            $('#schedule_order-id').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.order-type-box').removeClass('d-none');
                } else {
                    $('.order-type-box').addClass('d-none');
                }
            });

            if ($('#schedule_order-id').is(':checked')) {
                $('.order-type-box').removeClass('d-none');
            }

            $('#home_delivery_status-id, #takeaway_status-id').on('change', function() {
                if (!$('#home_delivery_status-id').is(':checked') && !$('#takeaway_status-id').is(':checked')) {
                    toastr.error('<?php echo e(translate("At least one delivery method Home Delivery or Takeaway must be selected for your business")); ?>');
                    $(this).prop('checked', true);
                }
            });

            // Extra Packaging Charge Toggle
            $('#extra_packaging_charge_status').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#extra_packaging_charge_options').slideDown();
                } else {
                    $('#extra_packaging_charge_options').slideUp();
                }
            });

            // Initialize state on load
            if ($('#extra_packaging_charge_status').is(':checked')) {
                $('#extra_packaging_charge_options').show();
            } else {
                $('#extra_packaging_charge_options').hide();
            }

            $('#order-settings-form').on('submit', function(e) {
                if ($('#extra_packaging_charge_status').is(':checked')) {
                    let checkedOptions = $('.extra-packaging-option:checked').length;
                    if (checkedOptions === 0) {
                        e.preventDefault();
                        toastr.error('<?php echo e(translate('Please select at least one module for extra packaging charge')); ?>');
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\settings\order-index.blade.php ENDPATH**/ ?>