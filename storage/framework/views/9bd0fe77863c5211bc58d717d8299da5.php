

<?php $__env->startSection('title', translate('messages.Delivery Man Preview')); ?>

<?php $__env->startPush('css_or_js'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <?php echo $__env->make('admin-views.delivery-man.partials._page_header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="">
                <?php echo $__env->make('admin-views.delivery-man.partials._tab_menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <div class="card-header flex-wrap pt-3 pb-0 border-0 gap-2">
                <div class="search--button-wrapper">
                    <h4 class="card-title fs-16 text-dark"><?php echo e(translate('messages.order_transactions')); ?></h4>
                    <!-- <div class="min--260">
                                                    <input type="date" class="form-control set-filter" placeholder="<?php echo e(translate('mm/dd/yyyy')); ?>" data-url="<?php echo e(route('admin.users.delivery-man.preview',['id'=>$deliveryMan->id, 'tab'=> 'transaction'])); ?>" data-filter="date" value="<?php echo e($date); ?>">
                                                </div> -->
                    <form class="search-form min--260">
                        <div class="input-group input--group">
                            <input id="datatableSearch_" type="search" name="search" class="form-control h--40px text-muted"
                                placeholder="<?php echo e(translate('messages.Search Order ID')); ?>" value="<?php echo e(request()->search); ?>"
                                aria-label="Search" tabindex="1">

                            <button type="submit" class="btn btn--secondary bg-modal-btn"><i
                                    class="tio-search text-muted"></i></button>
                        </div>
                    </form>
                    <button type="button"
                        class="btn btn--primary h-40px btn-outline-primary py-2 offcanvas-trigger position-relative"
                        data-target="#transaction__list">
                        <i class="tio-tune-horizontal"></i>
                        <?php echo e(translate('messages.Filter')); ?>

                        <?php if(request()->input('date_range') && request()->input('date_range') != 'all_time'): ?>
                            <span class="badge-danger rounded-circle position-absolute"
                                style="top: -3px; right: -3px; width: 10px; height: 10px; padding: 0;"></span>
                        <?php endif; ?>
                    </button>
                </div>
                <!-- Unfold -->
                <div class="hs-unfold mr-2">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle min-height-40" href="javascript:;"
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
                            href="<?php echo e(route('admin.users.delivery-man.earning-export', ['type' => 'excel', 'id' => $deliveryMan->id, request()->getQueryString()])); ?>">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="<?php echo e(asset('assets/admin')); ?>/svg/components/excel.svg" alt="Image Description">
                            <?php echo e(translate('messages.excel')); ?>

                        </a>
                        <a id="export-csv" class="dropdown-item"
                            href="<?php echo e(route('admin.users.delivery-man.earning-export', ['type' => 'csv', 'id' => $deliveryMan->id, request()->getQueryString()])); ?>">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="<?php echo e(asset('assets/admin')); ?>/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                            .<?php echo e(translate('messages.csv')); ?>

                        </a>
                    </div>
                </div>
                <!-- End Unfold -->
            </div>
            <!-- Body -->
            <div class="p-xxl-20 p-3">
                <div class="shadow-sm rounded">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table id="datatable"
                                class="table table-borderless table-thead-bordered table-nowrap justify-content-between table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="border-0"><?php echo e(translate('sl')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.order_id')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.date')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.delivery_fee_earned')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.delivery_tips')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.total_amount')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php $__currentLoopData = $digital_transaction; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <tr>
                                            <td scope="row"><?php echo e($k + $digital_transaction->firstItem()); ?></td>
                                            <td class="w--1"><a
                                                    class="line--limit-1 fs-14 text-dark max-w--220px min-w-135px text-wrap"
                                                    href="<?php echo e(route((isset($dt->order) && $dt->order->order_type == 'parcel') ? 'admin.parcel.order.details' : 'admin.order.details', [$dt->order_id, 'module_id' => $dt->order->module_id])); ?>"><?php echo e($dt->order_id); ?></a>
                                            </td>
                                            <td> <?php echo e(\App\CentralLogics\Helpers::date_format($dt->created_at)); ?></td>
                                            <td><?php echo e(\App\CentralLogics\Helpers::format_currency($dt->original_delivery_charge)); ?>

                                            </td>
                                            <td><?php echo e(\App\CentralLogics\Helpers::format_currency($dt->dm_tips)); ?></td>
                                            <td><?php echo e(\App\CentralLogics\Helpers::format_currency($dt->original_delivery_charge + $dt->dm_tips)); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Body -->
                    <div class="card-footer">
                        <?php echo $digital_transaction->links(); ?>

                    </div>
                    <?php if(count($digital_transaction) === 0): ?>
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
        <!-- End Card -->
    </div>


    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
    <div id="transaction__list" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div>
            <form
                action="<?php echo e(route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'transaction'])); ?>"
                method="get">
                <div
                    class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
                    <h3 class="mb-0"><?php echo e(translate('messages.Filter')); ?></h2>
                        <button type="button"
                            class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0"
                            aria-label="Close">&times;</button>
                </div>
                <div class="custom-offcanvas-body p-20">
                    <?php echo $__env->make('admin-views.partials._date-range', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
        </div>
        <div class="offcanvas-footer p-3 d-flex align-items-center justify-content-center gap-3">
            <button type="reset" class="btn w-100 btn--reset h--40px redirect-url"
                data-url="<?php echo e(route('admin.users.delivery-man.preview', ['id' => $deliveryMan->id, 'tab' => 'transaction'])); ?>"><?php echo e(translate('messages.reset')); ?></button>
            <button type="submit" class="btn w-100 btn--primary h--40px"><?php echo e(translate('messages.Filter')); ?></button>
        </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\delivery-man\view\transaction.blade.php ENDPATH**/ ?>