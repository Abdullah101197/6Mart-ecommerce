

<?php $__env->startSection('title', translate('Delivery Man Preview')); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid pb-0">
        <?php echo $__env->make('admin-views.delivery-man.partials._page_header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="">
            <?php echo $__env->make('admin-views.delivery-man.partials._tab_menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="content container-fluid pt-0">
        <div class="card">
            <div class="card-body pb-5">
                <?php if($deliveryMan->application_status == 'approved'): ?>
                    <div
                        class="d-flex mb-xxl-4 mb-3 justify-content-between align-items-center gap-2 flex-wrap position-relative z-index-2">
                        <h4 class="card-title text-dark align-items-center flex-wrap gap-2">
                            <?php echo e(translate('messages.deliveryman Details')); ?>

                        </h4>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="javascript:"
                                class="btn request-alert py-2 <?php echo e($deliveryMan->status ? 'btn--danger' : 'btn-success'); ?> align-items-center d-flex"
                                data-url="<?php echo e(route('admin.users.delivery-man.status', [$deliveryMan['id'], $deliveryMan->status ? 0 : 1])); ?>"
                                data-message="<?php echo e($deliveryMan->status ? translate('messages.you_want_to_suspend_this_deliveryman') : translate('messages.you_want_to_unsuspend_this_deliveryman')); ?>">
                                <?php echo e($deliveryMan->status ? translate('messages.suspend_this_delivery_man') : translate('messages.unsuspend_this_delivery_man')); ?>

                            </a>
                            <div class="hs-unfold">

                                <div class="dropdown">
                                    <button class="btn btn--primary dropdown_after gap-0 fs-14 dropdown-toggle"
                                        type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <img src="<?php echo e(asset('assets/admin/img/icons/bx_edit.png')); ?>" alt=""
                                            class="mr-1">
                                        <?php echo e(translate('Edit')); ?>


                                    </button>
                                    <div class="dropdown-menu min-w-220 dropdown-menu-right text-capitalize"
                                        aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item fs-14 font-weight-medium text-dark"
                                            href="<?php echo e(route('admin.users.delivery-man.edit', [$deliveryMan->id])); ?>"><?php echo e(translate('messages.Edit Information')); ?></a>
                                        <a class="dropdown-item fs-14 font-weight-medium text-dark" data-toggle="modal"
                                            data-target="#work_switcher" href="javascript:">
                                            <?php echo e(translate('messages.Edit Delivery Type')); ?>

                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                <?php endif; ?>
                <div
                    class="d-flex flex-column flex-lg-nowrap flex-wrap flex-md-row align-items-center gap-3 border rounded p-3">
                    <div class="d-flex gap-3 justify-content-center position-relative w-115 rounded">
                        <img class="rounded" data-onerror-image="<?php echo e(asset('assets/admin/img/160x160/img1.jpg')); ?>"
                            src="<?php echo e($deliveryMan['image_full_url']); ?>" width="115" height="115"
                            alt="Delivery man image">
                        <span
                            class="suspend-badge bg-danger py-0 px-2 mb-2 fs-13 lh-1 text-white rounded position-absolute bottom-0 start-0"><?php echo e(!$deliveryMan['status'] && $deliveryMan['application_status'] == 'approved' ? translate('messages.suspended') : ''); ?></span>
                    </div>

                    <div class="flex-grow-1">
                        <div class="mb-3">
                            <h4 title="<?php echo e($deliveryMan['f_name'] . ' ' . $deliveryMan['l_name']); ?>"
                                class="d-flex justify-content-center justify-content-md-start mb-1 gap-2">
                                <?php echo e($deliveryMan['f_name'] . ' ' . $deliveryMan['l_name']); ?>

                                <?php if($deliveryMan->application_status == 'approved'): ?>
                                    <?php if($deliveryMan['status']): ?>
                                        <?php if($deliveryMan['active']): ?>
                                            <label
                                                class=" mb-0 badge badge-soft-primary"><?php echo e(translate('messages.online')); ?></label>
                                        <?php else: ?>
                                            <label
                                                class=" mb-0 badge badge-soft-danger"><?php echo e(translate('messages.offline')); ?></label>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <label
                                            class=" mb-0 badge badge-danger"><?php echo e(translate('messages.suspended')); ?></label>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <label
                                        class=" mb-0 badge badge-soft-<?php echo e($deliveryMan->application_status == 'pending' ? 'info' : 'danger'); ?>"><?php echo e(translate('messages.' . $deliveryMan->application_status)); ?></label>
                                <?php endif; ?>
                            </h4>
                            <div class="fs-12 text-title d-flex justify-content-center justify-content-md-start">
                                <?php if($deliveryMan->application_status == 'approved'): ?>
                                    <a href="mailto:<?php echo e($deliveryMan['email']); ?>" class="text-title">
                                        <?php echo e($deliveryMan['email']); ?></a>
                                    <span class="d-block mx-2 text-muted">|</span>
                                    <a href="tel:<?php echo e($deliveryMan['phone']); ?>" class="text-title">
                                        <?php echo e($deliveryMan['phone']); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div
                            class="bg-light2 d-flex align-items-center flex-xxl-nowrap flex-wrap rider_overview-info rounded">
                            <div class="d-flex justify-content-center justify-content-md-start gap-3">
                                <div class="">
                                    <h6 class="fs-13 mb-1 font-weight-normal text-dark">
                                        <?php echo e(translate('messages.Job_Type')); ?> </h6>
                                    <p class="mb-0 fs-14 font-weight-bold text-dark ">
                                        <?php echo e($deliveryMan->earning ? translate('messages.freelancer') : translate('messages.salary_based')); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="text-muted line-30"></div>
                            <div class="d-flex justify-content-center justify-content-md-start gap-3">
                                <div class="">
                                    <h6 class="fs-13 mb-1 font-weight-normal text-dark">
                                        <?php echo e(translate('messages.Vehicle_Type')); ?></h6>
                                    <p class="mb-0 fs-14 font-weight-bold text-dark ">
                                        <?php echo e($deliveryMan?->vehicle?->type ?? translate('messages.Unknown Vehicle')); ?></p>
                                </div>
                            </div>
                            <div class="text-muted line-30"></div>
                            <div class="d-flex justify-content-center justify-content-md-start gap-3">
                                <div class="">
                                    <h6 class="fs-13 mb-1 font-weight-normal text-dark"><?php echo e(translate('messages.Zone')); ?>

                                    </h6>
                                    <p class="mb-0 fs-14 font-weight-bold text-dark ">
                                        <?php echo e(isset($deliveryMan->zone) ? $deliveryMan->zone->name : translate('zone_deleted')); ?>

                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php if($deliveryMan->application_status == 'approved'): ?>
                        <?php ($total = $deliveryMan->reviews->count()); ?>
                        <div
                            class="d-flex flex-column flex-sm-nowrap flex-wrap flex-sm-row gap-3 flex-grow-1 border-lg-left">
                            <?php if($total > 0): ?>
                                <div class="d-flex flex-column align-items-center justify-content-center px-4">
                                    <img class=""
                                        src="<?php echo e(asset('assets/admin/img/icons/rating-stars.png')); ?>" alt="">

                                    <div class="d-block">
                                        <div class="rating--review">
                                            <h3 class="title mb-0">
                                                <?php echo e(count($deliveryMan->rating) > 0 ? number_format($deliveryMan->rating[0]->average, 1) : 0); ?><span
                                                    class="out-of">/5</span></h3>
                                            <div class="info">
                                                <span><?php echo e(translate('messages._of')); ?> <?php echo e($deliveryMan->reviews->count()); ?>

                                                    <?php echo e(translate('messages.reviews')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul
                                    class="list-unstyled list-unstyled-py-2 mb-0 rating--review-right py-3 flex-grow-1 review-color-progress">

                                    <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        <?php ($five = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 5)); ?>
                                        <span class="progress-name mr-3"><?php echo e(translate('excellent')); ?></span>
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo e($total == 0 ? 0 : ($five / $total) * 100); ?>%;"
                                                aria-valuenow="<?php echo e($total == 0 ? 0 : ($five / $total) * 100); ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ml-3"><?php echo e($five); ?></span>
                                    </li>
                                    <!-- End Review Ratings -->

                                    <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        <?php ($four = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 4)); ?>
                                        <span class="progress-name mr-3"><?php echo e(translate('good')); ?></span>
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo e($total == 0 ? 0 : ($four / $total) * 100); ?>%;"
                                                aria-valuenow="<?php echo e($total == 0 ? 0 : ($four / $total) * 100); ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ml-3"><?php echo e($four); ?></span>
                                    </li>
                                    <!-- End Review Ratings -->

                                    <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        <?php ($three = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 3)); ?>
                                        <span class="progress-name mr-3"><?php echo e(translate('average')); ?></span>
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo e($total == 0 ? 0 : ($three / $total) * 100); ?>%;"
                                                aria-valuenow="<?php echo e($total == 0 ? 0 : ($three / $total) * 100); ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ml-3"><?php echo e($three); ?></span>
                                    </li>
                                    <!-- End Review Ratings -->

                                    <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        <?php ($two = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 2)); ?>
                                        <span class="progress-name mr-3"><?php echo e(translate('below_average')); ?></span>
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo e($total == 0 ? 0 : ($two / $total) * 100); ?>%;"
                                                aria-valuenow="<?php echo e($total == 0 ? 0 : ($two / $total) * 100); ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ml-3"><?php echo e($two); ?></span>
                                    </li>
                                    <!-- End Review Ratings -->

                                    <!-- Review Ratings -->
                                    <li class="d-flex align-items-center font-size-sm">
                                        <?php ($one = \App\CentralLogics\Helpers::dm_rating_count($deliveryMan['id'], 1)); ?>
                                        <span class="progress-name mr-3"><?php echo e(translate('poor')); ?></span>
                                        <div class="progress flex-grow-1">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: <?php echo e($total == 0 ? 0 : ($one / $total) * 100); ?>%;"
                                                aria-valuenow="<?php echo e($total == 0 ? 0 : ($one / $total) * 100); ?>"
                                                aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="ml-3"><?php echo e($one); ?></span>
                                    </li>
                                    <!-- End Review Ratings -->
                                </ul>
                            <?php else: ?>
                                <div class="d-flex flex-column align-items-center justify-content-center px-4 m-auto">
                                    <img width="75" class=""
                                        src="<?php echo e(asset('assets/admin/img/icons/no_rating.png')); ?>" alt="">
                                    <p class="mb-0 font-weight-normal">
                                        <?php echo e(translate('messages.no_review/rating_given_yet')); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>


                    <?php endif; ?>
                </div>


                <div class="border rounded p-xxl-20 p-3 mt-20">
                    <div class="d-flex gap-2 align-items-center mb-20">
                        <?php if($deliveryMan->application_status == 'approved'): ?>
                            <h5 class="mb-0 fs-16 fw-bold"><?php echo e(translate('Identity_Documents')); ?></h5>
                        <?php else: ?>
                            <h5 class="mb-0 fs-16 fw-bold"><?php echo e(translate('Registration_Information')); ?></h5>
                        <?php endif; ?>
                    </div>
                    <div class="row g-3">
                        <?php if($deliveryMan->application_status == 'pending'): ?>
                            <div class="col-lg-4">
                                <div class="bg-light2 rounded p-3 h-100 d-flex flex-column gap-2">

                                    <div class="key-val-list-item d-flex gap-3">
                                        <div class="text-title fs-14 identity__info">
                                            <?php echo e(translate('messages.First_Name')); ?> </div>:
                                        <div class="text-dark fs-14"><?php echo e($deliveryMan['f_name']); ?></div>
                                    </div>
                                    <div class="key-val-list-item d-flex gap-3">
                                        <div class="text-title fs-14 identity__info"><?php echo e(translate('messages.Last_Name')); ?>

                                        </div>:
                                        <div class="text-dark fs-14"><?php echo e($deliveryMan['l_name']); ?></div>
                                    </div>
                                    <div class="key-val-list-item d-flex gap-3">
                                        <div class="text-title fs-14 identity__info"><?php echo e(translate('messages.email')); ?>

                                        </div>:
                                        <div class="text-dark fs-14"><?php echo e($deliveryMan['email']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-4">
                            <div class="bg-light2 rounded p-3 h-100 d-flex flex-column gap-2">

                                <div class="key-val-list-item d-flex gap-3">
                                    <div class="text-title fs-14 identity__info"><?php echo e(translate('Identity_Type')); ?></div>:
                                    <div class="text-dark fs-14"><?php echo e(translate($deliveryMan->identity_type)); ?></div>
                                </div>
                                <div class="key-val-list-item d-flex gap-3">
                                    <div class="text-title fs-14 identity__info">
                                        <?php echo e(translate('messages.identification_number')); ?></div>:
                                    <div class="text-dark fs-14"><?php echo e($deliveryMan->identity_number); ?></div>
                                </div>
                            </div>
                        </div>
                        <?php if($deliveryMan->application_status == 'pending'): ?>
                            <div class="col-lg-4">
                                <div class="bg-light2 rounded p-3 h-100 d-flex flex-column gap-2">

                                    <div class="key-val-list-item d-flex gap-3">
                                        <div class="text-title fs-14 identity__info"><?php echo e(translate('messages.Phone')); ?>

                                        </div>:
                                        <div class="text-dark fs-14"><?php echo e($deliveryMan->phone); ?></div>
                                    </div>
                                    <div class="key-val-list-item d-flex gap-3">
                                        <div class="text-title fs-14 identity__info"><?php echo e(translate('messages.Password')); ?>

                                        </div>:
                                        <div class="text-dark fs-14">**********</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class=" <?php echo e($deliveryMan->application_status == 'pending' ? 'col-12' : 'col-lg-8'); ?> ">
                            <div class="bg-light2 rounded p-3 h-100 identity_documnet_body tabs-slide-wrap">

                                <div class="tabs-inner d-flex gap-3 identity_documnet_wrap">
                                    <?php $__currentLoopData = $deliveryMan->identity_image_full_url; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button class="btn  p-0" data-toggle="modal"
                                            data-target="#image-<?php echo e($key); ?>">
                                            <div class="gallary-card">
                                                <img class="rounded mx-h150 mx-w-100"
                                                    data-onerror-image="<?php echo e(asset('/assets/admin/img/900x400/img1.jpg')); ?>"
                                                    src="<?php echo e($img); ?>" width="275" height="150"
                                                    alt="">
                                            </div>
                                        </button>
                                        <div class="modal fade" id="image-<?php echo e($key); ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="myModlabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="myModlabel">
                                                            <?php echo e(translate('messages.Identity_Image')); ?></h4>
                                                        <button type="button" class="close" data-dismiss="modal"><span
                                                                aria-hidden="true">&times;</span><span
                                                                class="sr-only"><?php echo e(translate('messages.Close')); ?></span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img data-onerror-image="<?php echo e(asset('/assets/admin/img/900x400/img1.jpg')); ?>"
                                                            src="<?php echo e($img); ?>" class="w-100 onerror-image">
                                                    </div>
                                                    <div class="modal-footer">
                                                    </div>
                                                </div>
                                            </div>
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
                                    <div class="button-next align-items-center">
                                        <button type="button"
                                            class="btn btn-click-next ml-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                                            <i class="tio-chevron-right fs-24"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="content container-fluid pt-0">
        <div class="card">
            <div class="card-body">
                <?php if($deliveryMan->application_status == 'approved'): ?>
                    <div class="row g-3 color-card-custom">
                        <div class="col-lg-3">
                            <div class="color-card h-100 align-items-center justify-content-center">
                                <div
                                    class="box d-flex flex-column text-center justify-content-center align-items-center gap-3">
                                    <div class="img-box">
                                        <img class="resturant-icon w--30"
                                            src="<?php echo e(asset('assets/admin/img/icons/color-icon-1.png')); ?>"
                                            alt="img">
                                    </div>
                                    <div>
                                        <h2 class="title fs-24 fw-bold mb-1">
                                            <?php echo e(count($deliveryMan['order_transaction'])); ?>

                                        </h2>
                                        <div class="subtitle text-title">
                                            <?php echo e(translate('messages.total_delivered_orders')); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row g-3 row-3">


                                <!-- Collected Cash Card Example -->
                                <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                    <div class="color-card color-2">
                                        <div class="img-box">
                                            <img class="resturant-icon w--30"
                                                src="<?php echo e(asset('/assets/admin/img/icons/color-icon-2.png')); ?>"
                                                alt="transactions">
                                        </div>
                                        <div>
                                            <h2 class="title fs-24 fw-bold mb-1">
                                                <?php echo e(\App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->collected_cash : 0.0)); ?>

                                            </h2>
                                            <div class="subtitle text-title">
                                                <?php echo e(translate('messages.cash_in_hand')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Earning Card Example -->
                                <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                    <div class="color-card color-3">
                                        <div class="img-box">
                                            <img class="resturant-icon w--30"
                                                src="<?php echo e(asset('/assets/admin/img/icons/color-icon-3.png')); ?>"
                                                alt="transactions">
                                        </div>
                                        <div>
                                            <h2 class="title fs-24 fw-bold mb-1">
                                                <?php echo e(\App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->total_earning : 0.0)); ?>

                                            </h2>
                                            <div class="subtitle text-title">
                                                <?php echo e(translate('messages.total_earning')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Total Earning Card Example -->

                                <?php
                                $balance = 0;
                                if ($deliveryMan->wallet) {
                                    $balance = $deliveryMan->wallet->total_earning - ($deliveryMan->wallet->total_withdrawn + $deliveryMan->wallet->pending_withdraw + $deliveryMan->wallet->collected_cash);
                                }

                                ?>
                                <?php if($deliveryMan->earning): ?>
                                    <?php if($balance > 0): ?>
                                        <div class="col-sm-6 col-lg-4">
                                            <div class="color-card colxxl-4">
                                                <div class="img-box">
                                                    <img class="resturant-icon w--30"
                                                        src="<?php echo e(asset('/assets/admin/img/icons/group.png')); ?>"
                                                        alt="transactions">
                                                </div>
                                                <div>
                                                    <h2 class="title fs-24 fw-bold mb-1">
                                                        <?php echo e(\App\CentralLogics\Helpers::format_currency(abs($balance))); ?>

                                                    </h2>
                                                    <div class="subtitle text-title">
                                                        <?php echo e(translate('messages.Withdraw_Able_Balance')); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif($balance < 0): ?>
                                        <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                            <div class="color-card color-4">
                                                <div class="img-box">
                                                    <img class="resturant-icon w--30"
                                                        src="<?php echo e(asset('/assets/admin/img/icons/color-icon-4.png')); ?>"
                                                        alt="transactions">
                                                </div>
                                                <div>
                                                    <h2 class="title fs-24 fw-bold mb-1">
                                                        <?php echo e(\App\CentralLogics\Helpers::format_currency(abs($deliveryMan->wallet->collected_cash))); ?>

                                                    </h2>
                                                    <div class="subtitle text-title">
                                                        <?php echo e(translate('messages.Payable_Balance')); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                            <div class="color-card color-4">
                                                <div class="img-box">
                                                    <img class="resturant-icon w--30"
                                                        src="<?php echo e(asset('/assets/admin/img/icons/group.png')); ?>"
                                                        alt="transactions">
                                                </div>
                                                <div>
                                                    <h2 class="title fs-24 fw-bold mb-1">
                                                        <?php echo e(\App\CentralLogics\Helpers::format_currency(0)); ?>

                                                    </h2>
                                                    <div class="subtitle text-title">
                                                        <?php echo e(translate('messages.Balance')); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                    <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                        <div class="color-card color-5">
                                            <div class="img-box">
                                                <img class="resturant-icon w--30"
                                                    src="<?php echo e(asset('/assets/admin/img/icons/color-icon-5.png')); ?>"
                                                    alt="transactions">
                                            </div>
                                            <div>
                                                <h2 class="title fs-24 fw-bold mb-1">
                                                    <?php echo e(\App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->total_withdrawn : 0.0)); ?>

                                                </h2>
                                                <div class="subtitle text-title">
                                                    <?php echo e(translate('messages.Total_withdrawn')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                        <div class="color-card color-6">
                                            <div class="img-box">
                                                <img class="resturant-icon w--30"
                                                    src="<?php echo e(asset('/assets/admin/img/icons/color-icon-6.png')); ?>"
                                                    alt="transactions">
                                            </div>
                                            <div>
                                                <h2 class="title fs-24 fw-bold mb-1">
                                                    <?php echo e(\App\CentralLogics\Helpers::format_currency($deliveryMan->wallet ? $deliveryMan->wallet->pending_withdraw : 0.0)); ?>

                                                </h2>
                                                <div class="subtitle text-title">
                                                    <?php echo e(translate('messages.Pending_withdraw')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xxl-4 col-xl-6 col-lg-6">
                                        <div class="color-card color-9">
                                            <div class="img-box">
                                                <img class="resturant-icon w--30"
                                                    src="<?php echo e(asset('/assets/admin/img/icons/loyalty-star.png')); ?>"
                                                    alt="transactions">
                                            </div>
                                            <div>
                                                <h2 class="title text--039D55 fs-24 fw-bold mb-1">
                                                    <?php echo e((int) $deliveryMan->loyalty_point); ?>

                                                </h2>
                                                <div class="subtitle text-title">
                                                    <?php echo e(translate('messages.Loyalty Point')); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php if($deliveryMan->application_status == 'approved'): ?>
        <div class="content container-fluid pt-0">
            <div class="card">
                <!-- Header -->
                <div class="card-header flex-sm-nowrap flex-wrap gap-2 pt-3 pb-0 border-0">
                    <h5 class="card-header-title d-flex align-items-center gap-2 text-nowrap line--limite-1">
                        <?php echo e(translate('messages.review_list')); ?>

                        <span class="badge badge-soft-dark ml-2" id="itemCount">
                            <?php echo e($reviews->total()); ?>

                        </span>
                    </h5>
                    <div class="search--button-wrapper justify-content-end">
                        <form class="search-form min--260">
                            <div class="input-group input--group">
                                <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                    placeholder="<?php echo e(translate('messages.search here')); ?>"
                                    value="<?php echo e(request()->search); ?>" aria-label="Search" tabindex="1">

                                <button type="submit" class="btn btn--secondary bg-modal-btn"><i
                                        class="tio-search text-muted"></i></button>
                            </div>
                        </form>
                        <!-- Unfold -->
                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40"
                                href="javascript:;"
                                data-hs-unfold-options='{
                                    "target": "#usersExportDropdown",
                                    "type": "css-animation"
                                }'>
                                <i class="tio-download-to mr-1"></i> <?php echo e(translate('messages.export')); ?>

                            </a>

                            <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header"><?php echo e(translate('messages.download_options')); ?></span>
                                <a id="export-excel" class="dropdown-item"
                                    href="<?php echo e(route('admin.users.delivery-man.review-export', ['type' => 'excel', 'id' => $deliveryMan->id, request()->getQueryString()])); ?>">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="<?php echo e(asset('assets/admin')); ?>/svg/components/excel.svg"
                                        alt="Image Description">
                                    <?php echo e(translate('messages.excel')); ?>

                                </a>
                                <a id="export-csv" class="dropdown-item"
                                    href="<?php echo e(route('admin.users.delivery-man.review-export', ['type' => 'csv', 'id' => $deliveryMan->id, request()->getQueryString()])); ?>">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="<?php echo e(asset('assets/admin')); ?>/svg/components/placeholder-csv-format.svg"
                                        alt="Image Description">
                                    .<?php echo e(translate('messages.csv')); ?>

                                </a>
                            </div>
                        </div>
                        <!-- End Unfold -->
                    </div>
                </div>
                <!-- End Header -->

                <!-- New Table -->

                <div class="p-xxl-20 p-3">
                    <div class="card-body shadow-sm rounded p-0">
                        <div class="table-responsive datatable-custom">
                            <table id="datatable" class="table table-border table-thead-bordered table-nowrap card-table"
                                data-hs-datatables-options='{
                            "columnDefs": [{
                                "targets": [0, 3, 6],
                                "orderable": false
                            }],
                            "order": [],
                            "info": {
                            "totalQty": "#datatableWithPaginationInfoTotalQty"
                            },
                            "search": "#datatableSearch",
                            "entries": "#datatableEntries",
                            "pageLength": 25,
                            "isResponsive": false,
                            "isShowPaging": false,
                            "pagination": "datatablePagination"
                        }'>
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.SL')); ?></th>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.order_ID')); ?></th>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.customer')); ?></th>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.Rating')); ?></th>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.Review ID')); ?></th>
                                        <th class="border-0 fs-14"><?php echo e(translate('messages.review')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fs-14 text-dark"><?php echo e($k + $reviews->firstItem()); ?></td>
                                            <td>
                                                <a class="line--limit-1 fs-14 text-dark max-w--220px min-w-135px text-wrap"
                                                    href="<?php echo e(route('admin.order.all-details', ['id' => $review->order_id])); ?>"><?php echo e($review->order_id); ?></a>
                                            </td>
                                            <td>
                                                <?php if($review->customer): ?>
                                                    <a class="d-flex align-items-center"
                                                        href="<?php echo e(route('admin.customer.view', [$review['user_id']])); ?>">
                                                        <span
                                                            class="text-dark fs-14 line--limit-1 max-w--220px min-w-135px text-wrap">
                                                            <?php echo e($review->customer ? $review->customer['f_name'] . ' ' . $review->customer['l_name'] : ''); ?>

                                                        </span>
                                                    </a>
                                                <?php else: ?>
                                                    <?php echo e(translate('messages.customer_not_found')); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <div class="d-flex gap-1 align-items-center">
                                                        <span class="d-inline-block mt-half"><?php echo e($review->rating); ?></span>
                                                        <i class="tio-star text-warning"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div
                                                    class="text-dark fs-14 line--limit-1 max-w--220px min-w-135px text-wrap">
                                                    <?php echo e($review->id); ?>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="fs-14 line--limit-2 max-w-390 min-w-220 text-dark text-wrap">
                                                    <?php echo e($review['comment']); ?>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table -->
                        <?php if(count($reviews) !== 0): ?>
                            <hr>
                        <?php endif; ?>
                        <div class="page-area">
                            <?php echo $reviews->links(); ?>

                        </div>
                        <?php if(count($reviews) === 0): ?>
                            <div class="empty--data">
                                <img src="<?php echo e(asset('/assets/admin/svg/illustrations/sorry.svg')); ?>"
                                    alt="public">
                                <h5>
                                    <?php echo e(translate('no_data_found')); ?>

                                </h5>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>

    </div>


    <div class="modal fade" id="work_switcher">
        <div class="modal-dialog modal-dialog-centered max-w-500px">
            <div class="modal-content">
                <div class="modal-header pr-3">
                    <button type="button" class="close border bg-modal-btn rounded-circle" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear text-light-gray"></span>
                    </button>
                </div>
                <div class="modal-body px-sm-4 px-3 pb-5 pt-0">
                    <div class="text-center">
                        <div>
                            <div class="text-center mb-20">
                                <img width="80"
                                    src="<?php echo e(asset('assets/admin/img/icons/deliveryman-type.png')); ?>"
                                    class="">
                                <h5 class="modal-title m-0"></h5>
                            </div>
                            <div class="text-center mb-4">
                                <h3 class="font-weight-normal text-dark">
                                    <?php echo e(translate('This deliveryman is currently on')); ?> <br>
                                    <strong><?php echo e($deliveryMan->earning ? translate('messages.freelancer') : translate('messages.salary_based')); ?></strong>
                                </h3>
                            </div>
                        </div>
                        <div class="bg-light2 rounded p-sm-4 p-3">
                            <p class="fs-14 mb-20 text-body"><?php echo e(translate('Do you want to change the delivery type?')); ?>

                            </p>
                            <div class="btn--container justify-content-center p-0">
                                <a href="<?php echo e(route('admin.users.delivery-man.earning', ['id' => $deliveryMan->id, 'status' => $deliveryMan->earning ? 0 : 1])); ?>"
                                    class="btn btn--primary min-w-120">
                                    <?php echo e($deliveryMan->earning ? translate('Switch to Salary Based') : translate('Switch to Freelanced Based')); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        "use strict";
        $('.request-alert').on('click', function() {
            let url = $(this).data('url');
            let message = $(this).data('message');
            request_alert(url, message);
        })

        function request_alert(url, message) {
            Swal.fire({
                title: '<?php echo e(translate('messages.are_you_sure')); ?>',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: '<?php echo e(translate('messages.no')); ?>',
                confirmButtonText: '<?php echo e(translate('messages.yes')); ?>',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href = url;
                }
            })
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\delivery-man\view\info.blade.php ENDPATH**/ ?>