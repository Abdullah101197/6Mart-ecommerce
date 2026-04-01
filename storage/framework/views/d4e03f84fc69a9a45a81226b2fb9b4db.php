

<?php $__env->startSection('title', translate('messages.delivery_man_settings')); ?>


<?php $__env->startSection('content'); ?>
<?php use App\CentralLogics\Helpers; ?>
    <div class="content">
        <form action="<?php echo e(route('admin.business-settings.update-dm')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="d-flex align-items-center justify-content-between gap-1 w-100">
                        <h1 class="page-header-title mr-3">
                            <span class="page-header-icon">
                                <img src="<?php echo e(asset('assets/admin/img/business.png')); ?>" class="w--26" alt="">
                            </span>
                            <span>
                                <?php echo e(translate('business_setup')); ?>

                            </span>
                        </h1>
                        <?php if(!(Request::is('admin/business-settings/language') || Request::is('admin/business-settings/business-setup/refund-settings') || Request::is('admin/business-settings/business-setup/automated-message'))): ?>
                        <div class="d-flex flex-wrap justify-content-end align-items-center flex-grow-1">
                            <div class="blinkings active">
                                <i class="tio-info-outined"></i>
                                <div class="business-notes">
                                    <h6><img src="<?php echo e(asset('/assets/admin/img/notes.png')); ?>" alt=""> <?php echo e(translate('Note')); ?></h6>
                                    <div>
                                        <?php if(Request::is('admin/business-settings/business-setup/refund-settings')): ?>
                                        <?php echo e(translate('messages.*If_the_Admin_enables_the_‘Refund_Request_Mode’,_customers_can_request_a_refund.')); ?>

                                        <?php else: ?>
                                        <?php echo e(translate('messages.don’t_forget_to_click_the_‘Save Information’_button_below_to_save_changes.')); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php echo $__env->make('admin-views.business-settings.partials.nav-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <!-- Page Header -->
        
                <!-- End Page Header -->
           
                <div class="row g-2">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="rounded p-xxl-20 p-3 bg-light2">
    
                                    <div class="row g-3">
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($dm_tips_status = Helpers::get_business_settings('dm_tips_status')); ?>
                                            <div class="form-group mb-0">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-dark pr-1">
                                                        <?php echo e(translate('messages.Tips_for_Deliveryman')); ?>

                                                    </span>
                                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.Customer_can_give_tips_to_deliveryman_during_checkout_from_the_customer_app_&_website._From_this,_admin_has_no_commission.')); ?>">
                                                        <i class="tio-info text-light-gray"></i>
                                                    </span>
                                                </span>
                                                <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="line--limit-1 switch--label">
                                                        <?php echo e(translate('messages.Status')); ?>

                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="dm_tips_status"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/dm-tips-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/dm-tips-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Tips_for_Deliveryman_feature?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Tips_for_Deliveryman_feature?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_Customers_can_give_tips_to_a_deliveryman_during_checkout.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_the_Tips_for_Deliveryman_feature_will_be_hidden_from_the_Customer_App_and_Website.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                                            value="1"
                                                        name="dm_tips_status" id="dm_tips_status"
                                                        <?php echo e($dm_tips_status == '1' ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($show_dm_earning = Helpers::get_business_settings('show_dm_earning')  ); ?>
                                            <div class="form-group mb-0">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-dark pr-1">
                                                        <?php echo e(translate('messages.Show Earnings in App')); ?>

                                                    </span>
                                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.With_this_feature,_Deliverymen_can_see_their_earnings_on_a_specific_order_while_accepting_it.')); ?>">
                                                        <i class="tio-info text-light-gray"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="show_dm_earning"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/show-earning-in-apps-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/show-earning-in-apps-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Show_Earnings_in_App?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Show_Earnings_in_App?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_Deliverymen_can_see_their_earning_per_order_request_from_the_Order_Details_page_in_the_Deliveryman_App.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_the_feature_will_be_hidden_from_the_Deliveryman_App.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
    
                                                           value="1"
                                                        name="show_dm_earning" id="show_dm_earning"
                                                        <?php echo e($show_dm_earning == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
    
                                            <?php ($toggle_dm_registration =   Helpers::get_business_settings('toggle_dm_registration') ); ?>
                                            <div class="form-group mb-0">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-dark pr-1">
                                                        <?php echo e(translate('messages.dm_self_registration')); ?>

                                                    </span>
                                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.With_this_feature,_deliverymen_can_register_themselves_from_the_Customer_App,_Website_or_Deliveryman_App_or_Admin_Landing_Page._The_admin_will_receive_an_email_notification_and_can_accept_or_reject_the_request.')); ?>">
                                                        <i class="tio-info text-light-gray"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="dm_self_registration1"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/dm-self-reg-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/dm-self-reg-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Deliveryman_Self_Registration?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Deliveryman_Self_Registration?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_users_can_register_as_Deliverymen_from_the_Customer_App,_Website_or_Deliveryman_App_or_Admin_Landing_Page.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_this_feature_will_be_hidden_from_the_Customer_App,_Website_or_Deliveryman_App_or_Admin_Landing_Page.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
    
                                                           value="1"
                                                        name="toggle_dm_registration" id="dm_self_registration1"
                                                        <?php echo e($toggle_dm_registration == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($dm_maximum_orders =  Helpers::get_business_settings('dm_maximum_orders')   ); ?>
                                            <div class="form-group mb-0">
                                                <label class="form-label text-capitalize"
                                                    for="dm_maximum_orders">
                                                    <div class="d-flex align-items-center">
                                                        <span class="line--limit-1 flex-grow pr-1"><?php echo e(translate('Maximum Assigned Order Limit')); ?> </span>
                                                        <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.Set_the_maximum_order_limit_a_Deliveryman_can_take_at_a_time.')); ?>">
                                                            <i class="tio-info text-light-gray"></i>
                                                        </span>
                                                    </div>
                                                </label>
                                                <input type="number" name="dm_maximum_orders" class="form-control"
                                                    id="dm_maximum_orders" min="1"
                                                    value="<?php echo e($dm_maximum_orders ?? 1); ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($canceled_by_deliveryman = Helpers::get_business_settings('canceled_by_deliveryman')); ?>
                                            <div class="form-group mb-0">
                                                <label class="input-label text-capitalize d-flex align-items-center"><span
                                                        class="line--limit-1 pr-1"><?php echo e(translate('messages.Can_A_Deliveryman_Cancel_Order?')); ?></span>
                                                    <span class="form-label-secondary"
                                                    data-toggle="tooltip" data-placement="right"
                                                    data-original-title="<?php echo e(translate('messages.Admin_can_enable/disable_Deliveryman’s_order_cancellation_option_in_the_respective_app.')); ?>"><i class="tio-info text-light-gray"></i></span></label>
                                                <div class="resturant-type-group border">
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="1"
                                                            name="canceled_by_deliveryman" id="canceled_by_deliveryman"
                                                            <?php echo e($canceled_by_deliveryman == 1 ? 'checked' : ''); ?>>
                                                        <span class="form-check-label">
                                                            <?php echo e(translate('yes')); ?>

                                                        </span>
                                                    </label>
                                                    <label class="form-check form--check mr-2 mr-md-4">
                                                        <input class="form-check-input" type="radio" value="0"
                                                            name="canceled_by_deliveryman" id="canceled_by_deliveryman2"
                                                            <?php echo e($canceled_by_deliveryman == 0 ? 'checked' : ''); ?>>
                                                        <span class="form-check-label">
                                                            <?php echo e(translate('no')); ?>

                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($dm_picture_upload_status = Helpers::get_business_settings('dm_picture_upload_status')); ?>
                                            <div class="form-group mb-0">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-dark pr-1">
                                                        <?php echo e(translate('messages.Take_Picture_For_Completing_Delivery')); ?>

                                                    </span>
                                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.If_enabled,_deliverymen_will_see_an_option_to_take_pictures_of_the_delivered_products_when_he_swipes_the_delivery_confirmation_slide.')); ?>">
                                                        <i class="tio-info text-light-gray"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="dm_picture_upload_status"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/dm-self-reg-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/dm-self-reg-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.picture_upload_before_complete?')); ?></strong>"
                                                           data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.picture_upload_before_complete?')); ?></strong>"
                                                           data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_delivery_man_can_upload_order_proof_before_order_delivery.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_this_feature_will_be_hidden_from_the_delivery_man_app.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                                           value="1"
                                                        name="dm_picture_upload_status" id="dm_picture_upload_status"
                                                        <?php echo e($dm_picture_upload_status == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
    
    
    
    
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($cash_in_hand_overflow = Helpers::get_business_settings('cash_in_hand_overflow_delivery_man')); ?>
                                            <div class="form-label  mb-0 ">
                                                <span class="d-flex align-items-center mb-2">
                                                    <span class="text-dark pr-1">
                                                        <?php echo e(translate('messages.Cash_In_Hand_Overflow')); ?>

                                                    </span>
                                                    <span class="form-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('messages.If_enabled,_delivery_men_will_be_automatically_suspended_by_the_system_when_their_‘Cash_in_Hand’_limit_is_exceeded.')); ?>">
                                                        <i class="tio-info text-light-gray"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                    <span class="pr-1 d-flex align-items-center switch--label">
                                                        <span class="line--limit-1">
                                                            <?php echo e(translate('messages.Status')); ?>

                                                        </span>
                                                    </span>
                                                    <input type="checkbox"
                                                           data-id="cash_in_hand_overflow"
                                                           data-type="toggle"
                                                           data-image-on="<?php echo e(asset('/assets/admin/img/modal/show-earning-in-apps-on.png')); ?>"
                                                           data-image-off="<?php echo e(asset('/assets/admin/img/modal/show-earning-in-apps-off.png')); ?>"
                                                           data-title-on="<?php echo e(translate('Want_to_enable')); ?> <strong><?php echo e(translate('Cash_In_Hand_Overflow')); ?></strong>?"
                                                           data-title-off="<?php echo e(translate('Want_to_disable')); ?> <strong><?php echo e(translate('Cash_In_Hand_Overflow')); ?></strong>?"
                                                           data-text-on="<p><?php echo e(translate('If_enabled,_delivery_men_have_to_provide_collected_cash_by_themselves.')); ?></p>"
                                                           data-text-off="<p><?php echo e(translate('If_disabled,_delivery_men_do_not_have_to_provide_collected_cash_by_themselves.')); ?></p>"
                                                           class="status toggle-switch-input dynamic-checkbox-toggle"
                                                           value="1"
                                                           name="cash_in_hand_overflow_delivery_man" id="cash_in_hand_overflow"
                                                        <?php echo e($cash_in_hand_overflow == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($dm_max_cash_in_hand =  Helpers::get_business_settings('dm_max_cash_in_hand') ); ?>
                                            <div class="form-label mb-0">
                                                <label class="d-flex text-capitalize"
                                                       for="dm_max_cash_in_hand">
                                                    <span class="line--limit-1">
                                                        <?php echo e(translate('Delivery_Man_Maximum_Cash_in_Hand')); ?> (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)
                                                    </span>
                                                    <span data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('Deliveryman_can_not_accept_any_orders_when_the_Cash_In_Hand_limit_exceeds_and_must_deposit_the_amount_to_the_admin_before_accepting_new_orders')); ?>" class="input-label-secondary"><i class="tio-info text-light-gray"></i></span>
                                                </label>
                                                <input type="number" name="dm_max_cash_in_hand" class="form-control"
                                                       id="dm_max_cash_in_hand" min="0" step=".001"
                                                       value="<?php echo e($dm_max_cash_in_hand ?? ''); ?>" <?php echo e($cash_in_hand_overflow  == 1 ? 'required' : 'readonly'); ?> >
                                            </div>
                                        </div>
    
    
    
                                        <div class="col-sm-6 col-lg-4">
                                            <?php ($min_amount_to_pay_dm = Helpers::get_business_settings('min_amount_to_pay_dm')  ); ?>
                                            <div class="form-label mb-0">
                                                <label class="text-capitalize"
                                                       for="min_amount_to_pay_dm">
                                                    <span>
                                                        <?php echo e(translate('Minimum_Amount_To_Pay')); ?> (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)
    
                                                    </span>
    
                                                    <span class="form-label-secondary"
                                                          data-toggle="tooltip" data-placement="right"
                                                          data-original-title="<?php echo e(translate('Enter_the_minimum_cash_amount_delivery_men_can_pay')); ?>"><i class="tio-info text-light-gray"></i></span>
                                                </label>
                                                <input type="number" name="min_amount_to_pay_dm" class="form-control"
                                                       id="min_amount_to_pay_dm" min="0" step=".001"
                                                       value="<?php echo e($min_amount_to_pay_dm ?? ''); ?>"  <?php echo e($cash_in_hand_overflow  == 1 ? 'required' : 'readonly'); ?> >
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <?php ($dm_loyality_point_status = Helpers::get_business_settings('dm_loyality_point_status')  ); ?>
                        <?php ($dm_loyality_point_per_order = Helpers::get_business_settings('dm_loyality_point_per_order')  ); ?>
                        <?php ($dm_loyality_point_conversion_rate = Helpers::get_business_settings('dm_loyality_point_conversion_rate')  ); ?>
                        <?php ($dm_min_loyality_point_to_convert = Helpers::get_business_settings('dm_min_loyality_point_to_convert')  ); ?>
    
                        <div class="card mt-20 card-container">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-sm-nowrap flex-wrap">
                                    <div>
                                        <h4 class="mb-1"><?php echo e(translate('Loyalty Point')); ?></h4>
                                        <p class="fs-12 m-0"><?php echo e(translate('If enabled, deliverymen will earn a certain number of points for each successful delivery.')); ?></p>
                                    </div>
                                    <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-3">
                                        <div class="view_toggle_btn fz--14px info-dark cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                            <?php echo e(translate('messages.view')); ?>

                                            <i class="tio-chevron-down fs-22"></i>
                                        </div>
                                        <div class="mb-0">
                                            <label class="toggle-switch toggle-switch-sm mb-0">
                                                <input type="checkbox" data-type="toggle" class="status toggle-switch-input" name="dm_loyality_point_status" id="dm_loyality_point_status" value="1" <?php echo e($dm_loyality_point_status == 1 ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text mb-0">
                                                    <span
                                                        class="toggle-switch-indicator">
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-details-body <?php echo e(!$dm_loyality_point_status ? 'd-none' : ''); ?> ">
                                    <div class="bg-light2  rounded p-xxl-20 p-3 mt-20">
                                        <div class="row g-3">
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize" for="dm_loyality_point_per_order">
                                                        <div class="d-flex align-items-center">
                                                            <span class="line--limit-1 flex-grow pr-1"><?php echo e(translate('Loyalty Point Earn Per Order')); ?> </span>
                                                        </div>
                                                    </label>
                                                    <input type="number" name="dm_loyality_point_per_order" class="form-control" min="0"   max="9999999999"  id="dm_loyality_point_per_order" placeholder="1" value="<?php echo e($dm_loyality_point_per_order ?? ''); ?>" <?php echo e($dm_loyality_point_status == 1 ? 'required':'readonly'); ?>>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize" for="dm_loyality_point_conversion_rate">
                                                        <div class="d-flex align-items-center">
                                                            <span class="line--limit-1 flex-grow pr-1"><?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?> <?php echo e(translate('1.00 Equivalent To Points')); ?> </span>
                                                        </div>
                                                    </label>
                                                    <input type="number" name="dm_loyality_point_conversion_rate"  min="0" max="999999999"  class="form-control" id="dm_loyality_point_conversion_rate" placeholder="100" value="<?php echo e($dm_loyality_point_conversion_rate ?? ''); ?>" <?php echo e($dm_loyality_point_status == 1 ? 'required':'readonly'); ?>>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 col-lg-4">
                                                <div class="form-group mb-0">
                                                    <label class="form-label text-capitalize" for="dm_min_loyality_point_to_convert">
                                                        <div class="d-flex align-items-center">
                                                            <span class="line--limit-1 flex-grow pr-1"><?php echo e(translate('Minimum Point Required To Convert')); ?> </span>
                                                        </div>
                                                    </label>
                                                    <input type="number" name="dm_min_loyality_point_to_convert" min="0" max="999999999"  class="form-control" id="dm_min_loyality_point_to_convert" placeholder="200" value="<?php echo e($dm_min_loyality_point_to_convert ?? ''); ?>" <?php echo e($dm_loyality_point_status == 1 ? 'required':'readonly'); ?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
    
                        <?php ($dm_referal_status = Helpers::get_business_settings('dm_referal_status')  ); ?>
                        <?php ($dm_referal_amount = Helpers::get_business_settings('dm_referal_amount')  ); ?>
                        <?php ($dm_referal_bonus = Helpers::get_business_settings('dm_referal_bonus')  ); ?>
    
                        <div class="card mt-20 card-container">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 flex-sm-nowrap flex-wrap">
                                    <div>
                                        <h4 class="mb-1"><?php echo e(translate('Deliveryman Referral Earning Settings')); ?></h4>
                                        <p class="fs-12 m-0"><?php echo e(translate('Allow Drivers to refer your app to friends and family using a unique code and earn rewards.')); ?></p>
                                    </div>
                                    <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-3">
                                        <div class="view_toggle_btn fz--14px info-dark cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                            <?php echo e(translate('messages.view')); ?>

                                            <i class="tio-chevron-down fs-22"></i>
                                        </div>
                                        <div class="mb-0">
                                            <label class="toggle-switch toggle-switch-sm mb-0">
                                                <input type="checkbox" data-type="toggle" class="status toggle-switch-input" name="dm_referal_status" id="dm_referal_status" value="1" <?php echo e($dm_referal_status == 1 ? 'checked' : ''); ?> >
                                                <span class="toggle-switch-label text mb-0">
                                                    <span
                                                        class="toggle-switch-indicator">
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-details-body <?php echo e(!$dm_referal_status ? 'd-none' : ''); ?>">
                                    <div class="bg-light2 d-flex flex-column gap-4 rounded p-xxl-20 p-3 mt-20">
                                        <div class="row g-3">
                                            <div class="col-md-6 col-lg-4">
                                                <div>
                                                    <h4 class="mb-1"><?php echo e(translate('Who Share the Code')); ?></h4>
                                                    <p class="fs-12 m-0"><?php echo e(translate('Set the reward amount that drivers will earn for each successful referral. The reward will be given to the person who uses the referral code during signup and completes their first order.')); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-8">
                                                <div class="bg-white rounded p-xxl-20 p-2">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label text-capitalize" for="dm_referal_amount">
                                                            <div class="d-flex align-items-center">
                                                                <span class="line--limit-1 flex-grow pr-1"><?php echo e(translate('Earning Per Referral')); ?> (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)  <span class="text-danger">*</span> </span>
                                                            </div>
                                                        </label>
                                                        <input type="number" name="dm_referal_amount"   min="0" max="999999999" step="0.001" class="form-control " id="dm_referal_amount" placeholder="100" value="<?php echo e($dm_referal_amount??''); ?>" <?php echo e($dm_referal_status ? 'required' : 'readonly'); ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6 col-lg-4">
                                                <div>
                                                    <h4 class="mb-1"><?php echo e(translate('Who Use the Code')); ?></h4>
                                                    <p class="fs-12 m-0"><?php echo e(translate('Set the reward amount that drivers receive when signing up with a referral code & completes first order')); ?></p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-8">
                                                <div class="bg-white rounded p-xxl-20 p-2">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label text-capitalize" for="dm_referal_bonus">
                                                            <div class="d-flex align-items-center">
                                                                <span class="line--limit-1 flex-grow pr-1"><?php echo e(translate('Bonus In Wallet')); ?> (<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>) <span class="text-danger">*</span> </span>
                                                            </div>
                                                        </label>
                                                        <input type="number" name="dm_referal_bonus" min="0" max="999999999" step="0.001" class="form-control " id="dm_referal_bonus" placeholder="100" value="<?php echo e($dm_referal_bonus  ?? ''); ?>" <?php echo e($dm_referal_status ? 'required' : 'readonly'); ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                </div>
            </div>
            <div class="mt-0 footer-sticky">
                <div class="container-fluid">
                    <div class="btn--container justify-content-end py-3">
                        <button type="reset" id="reset_btn" class="btn min-w-120px btn--reset location-reload"><?php echo e(translate('messages.reset')); ?></button>
                        <button type="submit" id="submit" class="btn min-w-120px btn--primary"><?php echo e(translate('messages.save_information')); ?></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>

    <script>
        "use strict";
        $(document).on('ready', function () {

            function toggleFields(checkbox, fields) {
                if ($(checkbox).is(':checked')) {
                    $(fields).attr('required', true).removeAttr('readonly');
                } else {
                    $(fields).attr('required', false).attr('readonly', true);
                }
            }

            $('#dm_referal_status').on('change', function () {
                toggleFields(this, '#dm_referal_amount, #dm_referal_bonus');
            }).trigger('change');

            $('#dm_loyality_point_status').on('change', function () {
                toggleFields(this, '#dm_loyality_point_per_order, #dm_loyality_point_conversion_rate, #dm_min_loyality_point_to_convert');
            }).trigger('change');

        });

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\deliveryman-index.blade.php ENDPATH**/ ?>