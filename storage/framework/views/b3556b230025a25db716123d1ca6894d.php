

<?php $__env->startSection('title', translate('messages.customer_settings')); ?>

<?php $__env->startPush('css_or_js'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="<?php echo e(asset('assets/admin/img/business.png')); ?>" class="w--26" alt="">
                </span>
                <span>
                    <?php echo e(translate('business_setup')); ?>

                </span>
            </h1>
            <?php echo $__env->make('admin-views.business-settings.partials.nav-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <form action="<?php echo e(route('admin.customer.update-settings')); ?>" method="post" enctype="multipart/form-data"
            id="update-settings">
            <?php echo csrf_field(); ?>
            <div class="row g-3">
                <div class="col-lg-12">
                    <div class="fs-12 color-656565 px-3 py-2 bg-opacity-10 rounded bg-info mb-20">
                        <div class="d-flex align-items-center gap-2 mb-0">
                            <span class="text-info fs-16">
                                <i class="tio-light-on"></i>
                            </span>
                            <span>
                                <?php echo e(translate('See all customer & manage them from')); ?> <a href="javascript:void(0)" class="text-primary text-underline fw-semibold"><?php echo e(translate('All Customer List ')); ?></a> <?php echo e(translate('page.')); ?>

                            </span>
                        </div>
                    </div>
                    <div class="card mb-20">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">
                                <div class="col-xxl-9 col-lg-8 col-md-7 col-sm-6">
                                    <div>
                                        <h4 class="mb-1">
                                            <?php echo e(translate('Guest Checkout')); ?>

                                        </h4>
                                        <p class="mb-0 fs-12">
                                            <?php echo e(translate('This option allows customers to checkout and complete their orders without logging in')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-lg-4 col-md-5 col-sm-6">
                                     <?php ($guest_checkout_status = \App\Models\BusinessSetting::where('key', 'guest_checkout_status')->first()); ?>
                                    <?php ($guest_checkout_status = $guest_checkout_status ? $guest_checkout_status->value : 0); ?>
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                    <span class="pr-1 d-flex align-items-center switch--label">
                                        <span class="line--limit-1">
                                            <?php echo e(translate('messages.Status')); ?>

                                        </span>
                                    </span>
                                            <input type="checkbox" data-id="guest_checkout_status" data-type="toggle"
                                                    data-image-on="<?php echo e(asset('/assets/admin/img/modal/dm-tips-on.png')); ?>"
                                                    data-image-off="<?php echo e(asset('/assets/admin/img/modal/dm-tips-off.png')); ?>"
                                                    data-title-on="<strong><?php echo e(translate('messages.Want_to_enable_guest_checkout?')); ?></strong>"
                                                    data-title-off="<strong><?php echo e(translate('messages.Want_to_disable_guest_checkout?')); ?></strong>"
                                                    data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_guest_checkout_will_be_visible_when_customer_is_not_logged_in.')); ?></p>"
                                                    data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_guest_checkout_will_not_be_visible_when_customer_is_not_logged_in.')); ?></p>"
                                                    class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                                    name="guest_checkout_status" id="guest_checkout_status" <?php echo e($guest_checkout_status == 1 ? 'checked' : ''); ?>>
                                            <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="fs-12 color-656565 px-3 py-2 bg-opacity-10 rounded bg-info mt-20">
                                <div class="d-flex align-items-center gap-2 mb-0">
                                    <span class="text-info fs-16">
                                        <i class="tio-light-on"></i>
                                    </span>
                                    <span>
                                        <?php echo e(translate('When you turn on this feature, you may increase your orders & order amount.')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-20">
                        <div class="card-body">
                            <div class="mb-20">
                                <h4 class="mb-1">
                                    <?php echo e(translate('General Setup')); ?>

                                </h4>
                                <p class="mb-0 fs-12">
                                    <?php echo e(translate('Configure options to customize services for your customers.')); ?>

                                </p>
                            </div>
                            <div class="bg-light rounded p-xxl-20 p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6 col-lg-4">
                                        <?php ($country_picker_status = \App\Models\BusinessSetting::where('key', 'country_picker_status')->first()); ?>
                                        <?php ($country_picker_status = $country_picker_status ? $country_picker_status->value : 0); ?>
                                        <div class="form-group mb-0">
                                            <span class="mb-10px d-flex align-items-center">
                                                <span class="text-title">
                                                    <?php echo e(translate('messages.country_picker')); ?>

                                                </span>
                                                <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.If_you_enable_this_option,_in_all_phone_no_field_will_show_a_country_picker_list.')); ?>"><i class="tio-info text-muted ps--3"></i>
                                                </span>
                                            </span>
                                            <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                <span class="pr-1 d-flex align-items-center switch--label">
                                                    <span class="line--limit-1">
                                                        <?php echo e(translate('messages.Status')); ?>

                                                    </span>
                                                </span>
                                                <input type="checkbox" data-id="country_picker_status" data-type="toggle"
                                                       data-image-on="<?php echo e(asset('/assets/admin/img/modal/mail-success.png')); ?>"
                                                       data-image-off="<?php echo e(asset('/assets/admin/img/modal/mail-warning.png')); ?>"
                                                       data-title-on="<strong><?php echo e(translate('messages.Want_to_enable_country_picker?')); ?></strong>"
                                                       data-title-off="<strong><?php echo e(translate('messages.Want_to_disable_country_picker?')); ?></strong>"
                                                       data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_user_can_select_country_from_country_picker')); ?></p>"
                                                       data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_user_can_not_select_country_from_country_picker,_default_country_will_be_selected')); ?></p>"
                                                       class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                                       name="country_picker_status" id="country_picker_status" <?php echo e($country_picker_status == 1 ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <?php ($vnv = \App\Models\BusinessSetting::where('key', 'toggle_veg_non_veg')->first()); ?>
                                        <?php ($vnv = $vnv ? $vnv->value : 0); ?>
                                        <div class="form-group mb-0">
                                            <span class="mb-10px d-flex align-items-center">
                                                <span class="text-title">
                                                    <?php echo e(translate('messages.Customer’s_Food_Preference')); ?>

                                                </span>
                                                <span class="form-label-secondary text-danger d-flex" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="<?php echo e(translate('messages.If_this_feature_is_active,_customers_can_filter_food_according_to_their_preference_from_the_Customer_App_or_Website.')); ?>"><i class="tio-info text-muted ps--3"></i></span>
                                            </span>
                                            <label
                                                class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                                <span class="pr-1 d-flex align-items-center switch--label">
                                                    <span class="line--limit-1">
                                                        <?php echo e(translate('messages.Status')); ?>

                                                    </span>
                                                </span>
                                                    <input type="checkbox" data-id="vnv1" data-type="toggle"
                                                        data-image-on="<?php echo e(asset('/assets/admin/img/modal/veg-on.png')); ?>"
                                                        data-image-off="<?php echo e(asset('/assets/admin/img/modal/veg-off.png')); ?>"
                                                        data-title-on="<?php echo e(translate('messages.Want_to_enable_the')); ?> <strong><?php echo e(translate('messages.‘Veg/Non-Veg’_feature?')); ?></strong>"
                                                        data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.the_Veg/Non-Veg_Feature?')); ?></strong>"
                                                        data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_customers_can_filter_food_items_by_choosing_food_from_the_Veg/Non-Veg_feature.')); ?></p>"
                                                        data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_the_Veg/Non-Veg_feature_will_be_hidden_in_the_Customer_App_&_Website.')); ?></p>"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle" value="1"
                                                        name="vnv" id="vnv1" <?php echo e($vnv == 1 ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="fs-12 text-dark px-3 py-2 rounded bg-warning-10 mt-20">
                                <div class="d-flex align-items-center gap-2 mb-0">
                                    <span class="text-warning fs-14">
                                        <i class="tio-info"></i>
                                    </span>
                                    <span class="color-656566">
                                        <?php echo e(translate('If you want to business multiple country you need to turn on country picker feature.')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-20 card-container">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-sm-nowrap flex-wrap">
                                <div>
                                    <h4 class="mb-1"><?php echo e(translate('Customer Wallet')); ?></h4>
                                    <p class="fs-12 m-0"><?php echo e(translate('When active this feature customer can Earn & Buy through wallet. See customer wallet from Customers Details page.')); ?></p>
                                </div>
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-3">
                                    <div class="view_toggle_btn fz--14px info-dark cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        <?php echo e(translate('messages.view')); ?>

                                        <i class="tio-chevron-down fs-22"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox"


                 

                                            name="customer_wallet" id="wallet_status" value="1"
                                                    <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? 'checked' : ''); ?>>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-details-body">
                                <div class="bg-light2  rounded p-xxl-20 p-3 mt-20">
                                    <div class="row g-3">
                                         <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                <span class="mb-2 d-flex align-items-center text-title"><?php echo e(translate('messages.refund_to_wallet')); ?><span
                                                    class="input-label-secondary" data-toggle="tooltip"
                                                    data-placement="right"
                                                    data-original-title="<?php echo e(translate('messages.If_it’s_enabled,_Customers_will_automatically_receive_the_refunded_amount_in_their_wallets._But_if_it’s_disabled,_the_Admin_will_handle_the_Refund_Request_in_his_convenient_transaction_channel.')); ?>"><i class="tio-info text-muted ps--3"></i></span>
                                                </span>
                                                <label
                                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'text-muted'); ?>">
                                                    <span class="pr-2"><?php echo e(translate('messages.Status')); ?></span>
                                                    <input type="checkbox"
                                                    <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'disabled'); ?>

                                                    data-id="refund_to_wallet" data-type="toggle"
                                                        data-image-on="<?php echo e(asset('/assets/admin/img/modal/refund-on.png')); ?>"
                                                        data-image-off="<?php echo e(asset('/assets/admin/img/modal/refund-off.png')); ?>"
                                                        data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.Refund_to_Wallet_feature?')); ?></strong>"
                                                        data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.Refund_to_Wallet_feature?')); ?></strong>"
                                                        data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_Customers_will_automatically_receive_the_refunded_amount_in_their_wallets.')); ?></p>"
                                                        data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_the_Admin_will_handle_the_Refund_Request_in_his_convenient_transaction_channel.')); ?></p>"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle "
                                                        name="refund_to_wallet" id="refund_to_wallet" value="1"
                                                        <?php echo e(isset($data['wallet_add_refund']) && $data['wallet_add_refund'] == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <p class="mb-0 mt-2 fs-12 color-656565"><?php echo e(translate('To add fund for a customer visit')); ?> <a href="javascript:void(0)" class="text-primary text-underline fw-semibold"><?php echo e(translate('Add Fund')); ?></a> <?php echo e(translate('page.')); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                <span class="mb-2 d-flex align-items-center text-title"><?php echo e(translate('customer_can_add_fund_to_wallet')); ?>

                                                    <span class="input-label-secondary" data-toggle="tooltip"
                                                        data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.With_this_feature,_customers_can_add_fund_to_wallet_if_the_payment_module_is_available.')); ?>">
                                                        <i class="tio-info text-muted ps--3"></i>
                                                    </span>
                                                </span>
                                                <label
                                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'text-muted'); ?>">
                                                    <span class="pr-2"><?php echo e(translate('Status')); ?>

                                                    </span>
                                                    <input <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'disabled'); ?>

                                                    type="checkbox" data-id="add_fund_status" data-type="toggle"
                                                        data-image-on="<?php echo e(asset('/assets/admin/img/modal/wallet-on.png')); ?>"
                                                        data-image-off="<?php echo e(asset('/assets/admin/img/modal/wallet-off.png')); ?>"
                                                        data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('add_fund_to_Wallet_feature?')); ?></strong>"
                                                        data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('add_fund_to_Wallet_feature?')); ?></strong>"
                                                        data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_Customers_can_add_fund_to_wallet_using_payment_module')); ?></p>"
                                                        data-text-off="<p><?php echo e(translate('messages.If_you_disable_this,_add_fund_to_wallet_will_be_hidden_from_the_Customer_App_&_Website.')); ?></p>"
                                                        class="status toggle-switch-input dynamic-checkbox-toggle "
                                                        name="add_fund_status" id="add_fund_status" value="1"
                                                        <?php echo e(isset($data['add_fund_status']) && $data['add_fund_status'] == 1 ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <p class="mb-0 mt-2 fs-12 color-656565"><?php echo e(translate('To add fund for a customer visit')); ?> <a href="javascript:void(0)" class="text-primary text-underline fw-semibold"><?php echo e(translate('Add Fund')); ?></a> <?php echo e(translate('page.')); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fs-12 color-656565 px-3 py-2 bg-opacity-10 rounded bg-info mt-20">
                                    <div class="d-flex align-items-center gap-2 mb-0">
                                        <span class="text-info fs-16">
                                            <i class="tio-light-on"></i>
                                        </span>
                                        <span>
                                            <?php echo e(translate('You can see customer wallet from Customers details page. Go to this path')); ?> <strong><?php echo e(translate('Customers > Customer List > View Details.')); ?></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="card mb-20 card-container">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-sm-nowrap flex-wrap">
                                <div>
                                    <h4 class="mb-1"><?php echo e(translate('Customer Loyalty Point')); ?></h4>
                                    <p class="fs-12 m-0"><?php echo e(translate('If enabled customers will earn a certain amount of points after each purchase.')); ?></p>
                                </div>
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-3">
                                    <div class="view_toggle_btn fz--14px info-dark cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        <?php echo e(translate('messages.view')); ?>

                                        <i class="tio-chevron-down fs-22"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox" data-type="toggle" class="status toggle-switch-input" name="customer_loyalty_point" id="customer_loyalty_point"
                                                    data-section="loyalty-point-section" value="1"
                                                    <?php echo e(isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'checked' : ''); ?>>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="card-details-body">
                                <div class="bg-light2  rounded p-xxl-20 p-3 mt-20">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                <label class="input-label" for="loyalty_point_exchange_rate">1
                                                    <?php echo e(\App\CentralLogics\Helpers::currency_code()); ?>

                                                    <?php echo e(translate('equivalent point amount')); ?>

                                                    <span class="input-label-secondary"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.Content Need')); ?>"><i class="tio-info text-muted"></i>
                                                    </span>
                                                </label>
                                                <input <?php echo e(isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'required' : 'readonly'); ?>

                                                id="loyalty_point_exchange_rate" type="number" class="form-control" name="loyalty_point_exchange_rate" step=".001" min="0"
                                                    value="<?php echo e($data['loyalty_point_exchange_rate'] ?? '0'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                <label class="input-label gap-0" for="item_purchase_point">
                                                    <?php echo e(translate('Loyalty_Point_Earn_Per_Order')); ?> (%)
                                                    <span class="input-label-secondary"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.On_every_purchase_this_percent_of_amount_will_be_added_as_loyalty_point_on_his_account')); ?>"><i class="tio-info text-muted"></i>
                                                    </span>
                                                </label>
                                                <input <?php echo e(isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'required' : 'readonly'); ?> id="item_purchase_point"
                                                    type="number" class="form-control" name="item_purchase_point" step=".001" min="0" value="<?php echo e($data['loyalty_point_item_purchase_point'] ?? '0'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="form-group mb-0">
                                                <label class="input-label" for="minimum_transfer_point">
                                                    <?php echo e(translate('Minimum_Point_Required_To_Convert')); ?>

                                                    <span class="input-label-secondary"
                                                        data-toggle="tooltip" data-placement="right"
                                                        data-original-title="<?php echo e(translate('messages.Content Need')); ?>"><i class="tio-info text-muted"></i>
                                                    </span>
                                                </label>
                                                <input <?php echo e(isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'required' : 'readonly'); ?> id="minimum_transfer_point"
                                                    type="number" class="form-control" name="minimun_transfer_point" min="0" step=".001" value="<?php echo e($data['loyalty_point_minimum_point'] ?? '0'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="fs-12 color-656565 px-3 py-2 bg-opacity-10 rounded bg-info mt-20">
                                    <div class="d-flex align-items-center gap-2 mb-0">
                                        <span class="text-info fs-16">
                                            <i class="tio-light-on"></i>
                                        </span>
                                        <span>
                                            <?php echo e(translate('To see customer loyalty point report visit')); ?> <a href="javascript:void(0)" class="text-primary text-underline fw-semibold"><?php echo e(translate('Loyalty Point Report.')); ?></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card card-container">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between gap-2 flex-sm-nowrap flex-wrap">
                                <div>
                                    <h4 class="mb-1"><?php echo e(translate('Customer Referral Earning Settings')); ?></h4>
                                    <p class="fs-12 m-0"><?php echo e(translate('Customers will receive this wallet balance rewards for sharing their referral code')); ?></p>
                                </div>
                                <div class="d-flex flex-sm-nowrap flex-wrap justify-content-end justify-content-end align-items-center gap-3">
                                    <div class="view_toggle_btn fz--14px info-dark cursor-pointer text-decoration-underline font-semibold d-flex align-items-center gap-1">
                                        <?php echo e(translate('messages.view')); ?>

                                        <i class="tio-chevron-down fs-22"></i>
                                    </div>
                                    <div class="mb-0">
                                        <label class="toggle-switch toggle-switch-sm mb-0">
                                            <input type="checkbox" data-type="toggle" class="status toggle-switch-input" name="ref_earning_status" id="ref_earning_status"
                                                        data-section="referrer-earning" value="1"
                                                        <?php echo e(isset($data['ref_earning_status']) && $data['ref_earning_status'] == 1 ? 'checked' : ''); ?>>
                                            <span class="toggle-switch-label text mb-0">
                                                <span
                                                    class="toggle-switch-indicator">
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-details-body mt-20">
                                <div class="bg-light rounded p-xxl-20 p-3">
                                    <div class="py-0">
                                        <div class="row g-3 align-items-end mb-3">

                                            <div class="align-self-center  col-md-4">
                                                <div class="text-left">
                                                    <h5 class="align-items-center">
                                                        <span>
                                                            <?php echo e(translate('Who_Share_the_code')); ?>

                                                        </span>
                                                    </h5>
                                                    <p class="fs-12 color-656565">
                                                        <?php echo e(translate('Customers_will_receive_this_wallet_balance_rewards_for_sharing_their_referral_code_with_friends,_who_use_the_code_when_signing_up_and_completing_their_first_order.')); ?>

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="bg-white rounded p-xxl-20 p-3 text-left">
                                                    <div class="card-body p-0">
                                                        <div class="form-group mb-0">
                                                            <label class="input-label" for="ref_earning_exchange_rate">
                                                                <?php echo e(translate('Earning Per Referral')); ?>

                                                                <?php echo e(\App\CentralLogics\Helpers::currency_code()); ?>


                                                                <span class="input-label-secondary" data-toggle="tooltip"
                                                                    data-placement="right"
                                                                    data-original-title="<?php echo e(translate('Content need')); ?>">
                                                                    <i class="tio-info text-muted"></i>
                                                                </span>
                                                            </label>
                                                            <input <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'readonly'); ?>

                                                            id="ref_earning_exchange_rate" type="number" step=".001" min="0" max="99999999999"
                                                                class="form-control" name="ref_earning_exchange_rate"
                                                                value="<?php echo e($data['ref_earning_exchange_rate'] ?? '0'); ?>" data-toggle="tooltip" data-placement="right" data-original-title="Refer amount add to wallet option is disabled. Kindly turn on the option from Customer Wallet section to complete this settings">
                                                            <p class="text-danger mt-1 mb-0 fs-12"><?php echo e(translate('Must Turn on')); ?> <strong><?php echo e(translate('Add Fund to Wallet')); ?></strong> <?php echo e(translate('option, otherwise customer can’t receive the reward amount.')); ?> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3 align-items-end">
                                            <div class="align-self-center col-md-4 text-center">
                                                <div class="text-left">

                                                    <h5 class="align-items-center">
                                                        <span>
                                                            <?php echo e(translate('Who_Use_the_code')); ?>

                                                        </span>
                                                    </h5>
                                                    <p class="fs-12 color-656565">
                                                        <?php echo e(translate('By_applying_the_referral_code_during_signup_and_when_making_their_first_purchase,_customers_will_enjoy_a_discount_for_a_limited_time.')); ?>

                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="bg-white rounded p-xxl-20 p-3 text-left">
                                                    <div class="card-body p-0">
                                                        <div>
                                                            <div class="form-group">
                                                                <span
                                                                    class="mb-2 text-title d-flex align-items-center"><?php echo e(translate('Customer_will_get_Discount_on_first_order ')); ?>

                                                                    <span class="input-label-secondary" data-toggle="tooltip"
                                                                        data-placement="right"
                                                                        data-original-title="<?php echo e(translate('messages.Configure_discounts_for_newly_registered_users_who_sign_up_with_a_referral_code._Customize_the_discount_type_and_amount_to_incentivize_referrals_and_encourage_user_engagement.')); ?>">
                                                                        <i class="tio-info text-muted"></i>
                                                                    </span>
                                                                </span>
                                                                <label
                                                                    class="toggle-switch toggle-switch-sm d-flex justify-content-between border border-secondary rounded px-4 form-control <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'text-muted'); ?>">
                                                                    <span
                                                                        class="pr-2"><?php echo e(translate('Status ')); ?>

                                                                    </span>
                                                                    <input <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 ? '' : 'disabled'); ?>

                                                                    type="checkbox" data-id="new_customer_discount_status"
                                                                        data-type="toggle"
                                                                        data-image-on="<?php echo e(asset('/assets/admin/img/modal/basic_campaign_on.png')); ?>"
                                                                        data-image-off="<?php echo e(asset('/assets/admin/img/modal/basic_campaign_off.png')); ?>"
                                                                        data-title-on="<?php echo e(translate('messages.Want_to_enable')); ?> <strong><?php echo e(translate('messages.new_customer_discount?')); ?></strong>"
                                                                        data-title-off="<?php echo e(translate('messages.Want_to_disable')); ?> <strong><?php echo e(translate('messages.new_customer_discount?')); ?></strong>"
                                                                        data-text-on="<p><?php echo e(translate('messages.If_you_enable_this,_Customers_will_get_discount_on_first_order.')); ?></p>"
                                                                        data-text-off="<p><?php echo e(translate('mo.If_you_disable_this,_Customers_won’t_get_any_discount_on_first_order.')); ?></p>"
                                                                        class="status toggle-switch-input dynamic-checkbox-toggle "
                                                                        name="new_customer_discount_status"
                                                                        id="new_customer_discount_status" value="1"
                                                                        <?php echo e(data_get($data, 'new_customer_discount_status') == 1 ? 'checked' : ''); ?>>
                                                                    <span class="toggle-switch-label text">
                                                                        <span class="toggle-switch-indicator"></span>
                                                                    </span>
                                                                </label>
                                                            </div>

                                                        </div>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-0">
                                                                    <label class="input-label" for="new_customer_discount_amount">
                                                                        <?php echo e(translate('Discount_Amount')); ?>


                                                                        <span class="<?php echo e(data_get($data, 'new_customer_discount_amount_type') != 'amount'  ? '': 'd-none'); ?> " id="percentage">(%)</span>
                                                                        <span  class=" <?php echo e(data_get($data, 'new_customer_discount_amount_type') == 'amount' ? '': 'd-none'); ?> " id='cuttency_symbol'>(<?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?>)
                                                                        </span>


                                                                        <span
                                                                            class="input-label-secondary" data-toggle="tooltip"
                                                                            data-placement="right"
                                                                            data-original-title="<?php echo e(translate('Enter_the_discount_value_for_referral-based_new_user_registrations.')); ?>">
                                                                            <i class="tio-info text-muted"></i>
                                                                        </span>
                                                                    </label>
                                                                    <div class="d-flex align-items-center gap-0 border rounded overflow-hidden">
                                                                        <input id="new_customer_discount_amount" type="number" step=".001" min="0"
                                                                        <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 && data_get($data, 'new_customer_discount_status') == 1 ? 'required' : 'readonly'); ?>

                                                                            class="form-control border-0 rounded-0" name="new_customer_discount_amount" max='<?php echo e(data_get($data, 'new_customer_discount_amount_type') != 'amount'  ? '100': '9999999999'); ?>'
                                                                            value="<?php echo e(data_get($data, 'new_customer_discount_amount') ?? '0'); ?>">
                                                                        <select   name="new_customer_discount_amount_type"  class="bg-modal-btn custom-select border-0 rounded-0 w-auto"  id="new_customer_discount_amount_type"
                                                                            <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 && data_get($data, 'new_customer_discount_status') == 1 ? 'required' : 'disabled'); ?>

                                                                            >
                                                                                <option <?php echo e(data_get($data, 'new_customer_discount_amount_type') == 'percentage' ? "selected": ''); ?> value="percentage">(%)</option>
                                                                                <option <?php echo e(data_get($data, 'new_customer_discount_amount_type') == 'amount' ? "selected": ''); ?>  value="amount"><?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?></option>
                                                                            </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-0">
                                                                    <label class="input-label" for="new_customer_discount_amount_validity">
                                                                        <?php echo e(translate('validity')); ?>

                                                                        <span class="input-label-secondary" data-toggle="tooltip"
                                                                            data-placement="right"
                                                                            data-original-title="<?php echo e(translate('Set_how_long_the_discount_remains_active_after_registration.')); ?>">
                                                                            <i class="tio-info text-muted"></i>
                                                                        </span>
                                                                    </label>
                                                                    <div class="d-flex align-items-center gap-0 border rounded overflow-hidden">
                                                                        <input id="new_customer_discount_amount_validity" type="number" step="1" min="0" max="999"
                                                                        <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 && data_get($data, 'new_customer_discount_status') == 1 ? 'required' : 'readonly'); ?>

                                                                            class="form-control border-0 rounded-0" name="new_customer_discount_amount_validity"
                                                                            value="<?php echo e(data_get($data, 'new_customer_discount_amount_validity') ?? '0'); ?>">
                                                                        <select name="new_customer_discount_validity_type" class="bg-modal-btn custom-select border-0 rounded-0 w-auto" id="new_customer_discount_validity_type"  <?php echo e(isset($data['wallet_status']) && $data['wallet_status'] == 1 &&  data_get($data, 'new_customer_discount_status') == 1 ? 'required' : 'disabled'); ?>>
                                                                            <option <?php echo e(data_get($data, 'new_customer_discount_validity_type') == 'day' ? "selected": ''); ?> value="day"><?php echo e(translate('messages.day')); ?></option>
                                                                            <option <?php echo e(data_get($data, 'new_customer_discount_validity_type') == 'month' ? "selected": ''); ?>  value="month"><?php echo e(translate('messages.month')); ?> </option>
                                                                            <option <?php echo e(data_get($data, 'new_customer_discount_validity_type') == 'year' ? "selected": ''); ?>  value="year"><?php echo e(translate('messages.year')); ?> </option>
                                                                        </select>
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
                        </div>
                    </div>


                    <div class="col-lg-12">
                        <div class="btn--container justify-content-end mt-20">
                            <button type="reset" id="reset_btn"
                                class="btn btn--reset location-reload"><?php echo e(translate('reset')); ?></button>
                            <button type="submit" id="submit"
                                class="btn btn--primary"><?php echo e(translate('Save Information')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- End Table -->
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script>
    "use strict";

    $('#new_customer_discount_amount_type').on('change', function() {
        if($('#new_customer_discount_amount_type').val() == 'amount')
        {
            $('#percentage').addClass('d-none');
            $('#cuttency_symbol').removeClass('d-none');
            $('#new_customer_discount_amount').attr('max',99999999999);

        }
        else
        {
            $('#percentage').removeClass('d-none');
            $('#cuttency_symbol').addClass('d-none');
            $('#new_customer_discount_amount').attr('max',100);

        }
    });

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\test\business-setting\customer-index.blade.php ENDPATH**/ ?>