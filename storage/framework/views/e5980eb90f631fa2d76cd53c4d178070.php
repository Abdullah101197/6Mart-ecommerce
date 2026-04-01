

<?php $__env->startSection('title',translate('messages.Order List')); ?>

<?php $__env->startPush('css_or_js'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <?php ($parcel_order = Request::is('admin/parcel/orders*')); ?>
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-3 mb-sm-0">
                    <h1 class="page-header-title text-capitalize m-0">
                        <span class="page-header-icon">
                            <img src="<?php echo e(asset('assets/admin/img/fi_273177.svg')); ?>" class="w--26" alt="">
                        </span>
                        <span>
                        <?php echo e(translate('messages.Verify_Offline_Payments')); ?>

                            <span class="badge badge-soft-dark ml-2"><?php echo e($orders->total()); ?></span>
                        </span>
                    </h1>
                    <span class="badge badge-soft-danger text-start text-body fw-medium gap-1 mt-20 mb-20 border py-2 px-3 d-flex align-itmes">
                       <i class="tio-warning text-danger"></i> <?php echo e(translate('For_offline_payments_please_verify_if_the_payments_are_safely_received_to_your_account._Customer_id_not_liable_if_you_confirm_and_deliver_the_orders_without_checking_payments_transactions')); ?>

                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="js-nav-scroller hs-nav-scroller-horizontal">
                        <!-- Nav -->
                        <ul class="nav nav-tabs mb-3 border-0 nav--tabs nav--pills">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($status ==  'all' ? 'active' : ''); ?>" href="<?php echo e(route('admin.order.offline_verification_list', ['all'])); ?>"   aria-disabled="true"><?php echo e(translate('messages.All')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($status ==  'pending' ? 'active' : ''); ?>" href="<?php echo e(route('admin.order.offline_verification_list', ['pending'])); ?>"  aria-disabled="true"><?php echo e(translate('messages.Pending')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($status ==  'verified' ? 'active' : ''); ?>" href="<?php echo e(route('admin.order.offline_verification_list', ['verified'])); ?>"  aria-disabled="true"><?php echo e(translate('messages.verified')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($status ==  'denied' ? 'active' : ''); ?>" href="<?php echo e(route('admin.order.offline_verification_list', ['denied'])); ?>"  aria-disabled="true"><?php echo e(translate('messages.Denied')); ?></a>
                            </li>
                        </ul>
                        <!-- End Nav -->
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card">
            <!-- Header -->
            <div class="card-header gap-2 flex-wrap pb-0 pt-3 border-0">
                <h5 class="m-0"><?php echo e(translate('messages.Offline Payments List')); ?></h5>
                <div class="search--button-wrapper justify-content-end">
                    <form class="search-form min--260">
                        <!-- Search -->
                        <div class="input-group input--group rounded overflow-hidden">
                            <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                    placeholder="<?php echo e(translate('messages.Ex:')); ?> 10010" value="<?php echo e(request()?->search ?? null); ?>" aria-label="<?php echo e(translate('messages.search')); ?>">
                            <button type="submit" class="btn bg-modal-btn rounded-0"><i class="tio-search"></i></button>

                        </div>
                        <!-- End Search -->
                    </form>
                    <?php if(request()->get('search')): ?>
                    <button type="reset" class="btn btn--primary ml-2 location-reload-to-base" data-url="<?php echo e(url()->full()); ?>"><?php echo e(translate('messages.reset')); ?></button>
                    <?php endif; ?>


                    <!-- Datatable Info -->
                    <div id="datatableCounterInfo" class="mr-2 mb-2 mb-sm-0 initial-hidden">
                        <div class="d-flex align-items-center">
                                <span class="font-size-sm mr-3">
                                <span id="datatableCounter">0</span>
                                <?php echo e(translate('messages.selected')); ?>

                                </span>
                        </div>
                    </div>
                    <!-- End Datatable Info -->

                    <!-- Unfold -->
                    <div class="hs-unfold mr-2">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle h--40px" href="javascript:;"
                            data-hs-unfold-options='{
                                "target": "#usersExportDropdown",
                                "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> <?php echo e(translate('messages.export')); ?>

                        </a>

                        <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <span class="dropdown-header"><?php echo e(translate('messages.options')); ?></span>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header"><?php echo e(translate('messages.download_options')); ?></span>
                            <a id="export-excel" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="<?php echo e(asset('assets/admin')); ?>/svg/components/excel.svg"
                                        alt="Image Description">
                                <?php echo e(translate('messages.excel')); ?>

                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:;">
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

            <div class="card-body">
                <div class="shadow-sm">
                    <!-- Table -->
                    <div class="table-responsive m-0 datatable-custom">
                        <table id="datatable"
                                class="table table-hover table-border table-thead-bordered table-nowrap table-align-middle card-table fz--14px"
                                data-hs-datatables-options='{
                                "columnDefs": [{
                                    "targets": [0],
                                    "orderable": false
                                }],
                                "order": [],
                                "info": {
                                "totalQty": "#datatableWithPaginationInfoTotalQty"
                                },
                                "search": "#datatableSearch",
                                "entries": "#datatableEntries",
                                "isResponsive": false,
                                "isShowPaging": false,
                                "paging": false
                            }'>
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0">
                                    <?php echo e(translate('messages.sl')); ?>

                                </th>
                                <th class="table-column-pl-0 border-0"><?php echo e(translate('messages.order_id')); ?></th>
                                <th class="border-0"><?php echo e(translate('messages.order_date')); ?></th>
                                <th class="border-0"><?php echo e(translate('messages.customer_information')); ?></th>
                                <th class="border-0"><?php echo e(translate('messages.total_amount')); ?></th>
                                <th class="text-center border-0"><?php echo e(translate('messages.Payment_Method')); ?></th>
                                <th class="text-center border-0"><?php echo e(translate('messages.actions')); ?></th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <tr class="status-<?php echo e($order['order_status']); ?> class-all">
                                    <td class="text-title">
                                        <?php echo e($key+$orders->firstItem()); ?>

                                    </td>
                                    <td class="table-column-pl-0">
                                        <a href="<?php echo e(route($parcel_order?'admin.parcel.order.details':'admin.order.details',['id'=>$order['id']])); ?>" class="text-title"><?php echo e($order['id']); ?></a>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="text-title">
                                                <?php echo e(date('d M Y',strtotime($order['created_at']))); ?>

                                            </div>
                                            <div class="d-block text-uppercase text-title">
                                                <?php echo e(date(config('timeformat'),strtotime($order['created_at']))); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($order->customer): ?>
                                            <a class="text-title text-capitalize" href="<?php echo e(route('admin.customer.view',[$order['user_id']])); ?>">
                                                <strong><?php echo e($order->customer['f_name'].' '.$order->customer['l_name']); ?></strong>
                                                <div><?php echo e($order->customer['phone']); ?></div>
                                            </a>
                                        <?php elseif($order->is_guest): ?>
                                            <?php ($customer_details = json_decode($order['delivery_address'],true)); ?>
                                            <strong><?php echo e($customer_details['contact_person_name']); ?></strong>
                                            <div><?php echo e($customer_details['contact_person_number']); ?></div>
                                        <?php else: ?>
                                            <label class="badge badge-danger"><?php echo e(translate('messages.invalid_customer_data')); ?></label>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="text-right mw--85px">
                                            <div class="text-title">
                                                <?php echo e(\App\CentralLogics\Helpers::format_currency($order['order_amount'])); ?>

                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-capitalize text-title text-center">
                                        <?php echo e(optional(json_decode($order?->offline_payments?->payment_info ?? '', true))['method_name']
                                            ?? 'N/A'); ?>

                                    </td>
                                    <td>
                                        <?php if($order?->offline_payments?->status == 'pending'): ?>
                                            <div class="btn--container justify-content-center">
                                                <button  type="button" class="btn btn--primary btn-sm fs-12 px-3" data-toggle="modal" data-target="#verifyViewModal-<?php echo e($key); ?>" ><?php echo e(translate('messages.Verify_Payment')); ?></button>
                                            </div>

                                            <?php elseif($order?->offline_payments?->status == 'verified'): ?>
                                            <div class="btn--container justify-content-center">
                                                <button  type="button" class="btn btn--primary btn-sm fs-12 px-3" data-toggle="modal" data-target="#verifyViewModal-<?php echo e($key); ?>" ><?php echo e(translate('messages.verified')); ?></button>
                                            </div>
                                            <?php elseif($order?->offline_payments?->status == 'denied'): ?>
                                            <div class="btn--container justify-content-center">
                                                <button  type="button" class="btn py-2 badge-soft-danger btn-sm fs-13 px-3" data-toggle="modal" data-target="#verifyViewModal-<?php echo e($key); ?>" ><?php echo e(translate('messages.Recheck_Verification')); ?></button>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(!$order?->offline_payments): ?>
                                            <div class="btn--container justify-content-center">
                                                <button  type="button" class="btn btn--primary btn-sm fs-12 px-3" data-toggle="modal" data-target="#verifyViewModal-<?php echo e($key); ?>" ><?php echo e(translate('messages.Verify_Payment')); ?></button>
                                            </div>
                                        <?php endif; ?>

                                    </td>
                                </tr>

                                        <!-- End Card -->
                    <div class="modal fade" id="verifyViewModal-<?php echo e($key); ?>" tabindex="-1" aria-labelledby="verifyViewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header d-flex justify-content-end  border-0 pt-3 px-3">
                                    <button type="button" class="close border bg-modal-btn rounded-circle" data-dismiss="modal">
                                        <span aria-hidden="true" class="tio-clear"></span>
                                    </button>
                                </div>
                            <div class="modal-body pt-0">
                            <div class="d-flex align-items-center flex-column gap-1 mb-xxl-5 mb-4 text-center">
                                <h2 class="mb-0">
                                    <?php echo e(translate('Payment Verification')); ?>


                                    <?php if(optional($order->offline_payments)->status === 'verified'): ?>
                                        <span class="badge badge-soft-success mt-3 mb-3">
                                        <?php echo e(translate('messages.verified')); ?>

                                    </span>
                                    <?php endif; ?>
                                </h2>

                                <?php if (! (optional($order->offline_payments)->status === 'verified')): ?>
                                    <p class="text-danger mb-0 mt-0">
                                        <?php echo e(translate('Please check and verify the payment information before confirming the order.')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="card border-0">
                                <div class="bg-light2 p-xxl-20 p-3 rounded">
                                    <div class="adjust-information-payment flex-md-nowrap flex-wrap">
                                        <div class="bg-white p-3 rounded w-100">
                                            <h4 class="mb-3 fs-16"><?php echo e(translate('messages.customer_information')); ?></h4>
                                            <div class="d-flex flex-column gap-2">
                                                <?php if($order->customer): ?>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="customer-namekey"><?php echo e(translate('Name')); ?></span>:
                                                    <span class="text-dark"> <a class="text-dark text-capitalize" href="<?php echo e(route('admin.customer.view',[$order['user_id']])); ?>"> <?php echo e($order->customer['f_name'].' '.$order->customer['l_name']); ?>  </a>  </span>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="customer-namekey"><?php echo e(translate('Contact')); ?></span>:
                                                    <span class="text-dark"><?php echo e($order->customer['phone']); ?>  </span>
                                                </div>

                                                <?php elseif($order->is_guest): ?>
                                                    <?php ($customer_details = json_decode($order['delivery_address'],true)); ?>

                                                    <div class="d-flex align-items-center gap-2">
                                                        <span><?php echo e(translate('Name')); ?></span>:
                                                        <span class="text-dark"> <?php echo e($customer_details['contact_person_name']); ?></span>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-2">
                                                        <span><?php echo e(translate('Phone')); ?></span>:
                                                        <span class="text-dark">  <?php echo e($customer_details['contact_person_number']); ?></span>
                                                    </div>

                                                <?php else: ?>
                                                    <label class="badge badge-danger"><?php echo e(translate('messages.invalid_customer_data')); ?></label>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="bg-white p-3 rounded h-100 w-100">
                                            <div class="">
                                                <h4 class="mb-3 fs-16"><?php echo e(translate('messages.Payment_Information')); ?></h4>
                                                <?php if($order?->offline_payments): ?>
                                                    <div class="row g-1">
                                                        <?php $__currentLoopData = json_decode($order?->offline_payments?->payment_info ?? '[]'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($key != 'method_id'): ?>
                                                         <?php
                                                                $key = match ($key) {
                                                                    'method_name'    => 'Payment Method',
                                                                    'name'           => 'Payment By',
                                                                    'date'           => 'Date',
                                                                    'transaction_id' => 'Transaction ID',
                                                                    default          => $key,
                                                                };
                                                            ?>
                                                            <div class="col-sm-12">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <span class="namekey"> <?php echo e(translate($key)); ?></span>:
                                                                    <span class="text-dark text-break"><?php echo e($item); ?></span>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>

                                                    
                                                <?php else: ?>
                                                    <div class="row g-1">
                                                        <div class="col-sm-12">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <span class="namekey"><?php echo e(translate('Payment Method')); ?></span>:
                                                                <span class="text-dark text-break"><?php echo e(translate('messages.N/A')); ?> </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if($order?->offline_payments?->status != 'verified'): ?>
                            <div class="btn--container justify-content-end mt-xxl-5 mt-4 pt-xxl-1">
                                <?php if($order?->offline_payments?->status != 'denied'): ?>
                                    <button type="button" class="btn btn--reset offline_payment_cancelation_note" data-toggle="modal" data-target="#offline_payment_cancelation_note" data-id="<?php echo e($order['id']); ?>" class="btn btn--reset"><?php echo e(translate('Payment_didn’t_Receive')); ?></button>
                                <?php elseif($order?->offline_payments?->status == 'denied'): ?>
                                    <button type="button" data-url="<?php echo e(route('admin.order.offline_payment', [ 'id' => $order['id'], 'verify' => 'switched_to_cod', ])); ?>" data-message="<?php echo e(translate('messages.Make_the_payment_switched_to_cod_for_this_order')); ?>" class="btn btn--reset route-alert"><?php echo e(translate('Switched_to_COD')); ?></button>
                                <?php endif; ?>
                                <?php if($order?->offline_payments): ?>
                                    <button type="button" data-url="<?php echo e(route('admin.order.offline_payment', [ 'id' => $order['id'], 'verify' => 'yes', ])); ?>" data-message="<?php echo e(translate('messages.Make_the_payment_verified_for_this_order')); ?>" class="btn btn--primary route-alert"><?php echo e(translate('Yes,_Payment_Received')); ?></button>
                                <?php else: ?>
                                        <button type="button" class="btn btn--primary btn-sm form-alert"
                                                data-id="order-<?php echo e($order['id']); ?>"
                                                data-cancel-btn="<?php echo e(translate('messages.Cancel')); ?>"
                                                data-confirm-btn="<?php echo e(translate('messages.Confirm')); ?>"
                                                data-image-url="<?php echo e(asset('assets/admin/img/tughrik.png')); ?>"
                                                data-title="<?php echo e(translate('Switch to Cash on Delivery?')); ?>"
                                                data-message="<?php echo e(translate('The customer’s offline payment has failed. Before switching this order to Cash on Delivery (COD), please confirm the payment issue with the customer to avoid any misunderstandings.')); ?>">
                                            <?php echo e(translate('messages.Switch to COD')); ?>

                                        </button>
                                    <form action="<?php echo e(route('admin.order.switch_to_cod',[$order['id']])); ?>"
                                          method="post" id="order-<?php echo e($order['id']); ?>">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->
                    <?php if(count($orders) !== 0): ?>

                    <?php endif; ?>
                    <div class="page-area border-top">
                        <?php echo $orders->appends($_GET)->links(); ?>

                    </div>
                    <?php if(count($orders) === 0): ?>
                    <div class="empty--data">
                        <img src="<?php echo e(asset('/assets/admin/svg/illustrations/sorry.svg')); ?>" alt="public">
                        <h5>
                            <?php echo e(translate('no_data_found')); ?>

                        </h5>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

            <!-- Modal -->
    <!-- <div class="modal fade" id="offline_payment_cancelation_note" tabindex="-1" role="dialog"
        aria-labelledby="offline_payment_cancelation_note_l" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offline_payment_cancelation_note_l"><?php echo e(translate('messages.Add_Offline_Payment_Rejection_Note')); ?></h5>
                    <button type="button" class="close border bg-modal-btn rounded-circle" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('admin.order.offline_payment')); ?>" method="get">
                        <input type="hidden" name="id" id="myorderId">
                        <input type="text" required class="form-control" name="note" value="<?php echo e(old('note')); ?>"
                            placeholder="<?php echo e(translate('transaction_id_mismatched')); ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                    <button type="submit" class="btn btn--danger btn-outline-danger"><?php echo e(translate('messages.Confirm_Rejection')); ?> </button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
    <div class="modal fade" id="offline_payment_cancelation_note" tabindex="-1" role="dialog"
        aria-labelledby="offline_payment_cancelation_note_l" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-600" role="document">
            <div class="modal-content">
                <div class="modal-header px-2 pt-2">
                    <button type="button" class="close min-w-28 border bg-modal-btn rounded-circle" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('admin.order.offline_payment')); ?>" method="get">
                        <div class="cont mb-4 text-center pb-xxl-1">
                            <img width="60px" height="60px" src="<?php echo e(asset('/assets/admin/img/delete-confirmation.png')); ?>" alt="public" class="mb-20">
                            <h3 class="mb-xl-2 mb-1">
                                <?php echo e(translate('Are you sure the payment was not received?')); ?>

                            </h3>
                            <p class="mb-0 fs-14 max-w-420 mx-auto">
                                Please insert a <span class="text-title">Denied</span> note for this payment request to inform the customer.
                            </p>
                        </div>
                        <div class="bg-light2 rounded p-3">
                            <label class="form-label">
                                Denied Note
                                <span class="custom-tooltip" data-title="payment request to inform the customer ">
                                    <i class="tio-info text-muted"></i>
                                </span>
                            </label>
                            <input type="hidden" name="id" id="myorderId">
                            <textarea type="text" rows="1" required class="form-control" maxlength="100" name="note" value="<?php echo e(old('note')); ?>"
                            placeholder="<?php echo e(translate('transaction_id_mismatched')); ?>"></textarea>
                            <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/100</span>
                        </div>
                </div>
                <div class="modal-footer border-0 pt-2">
                    <button type="button" class="btn btn--reset h-40px min-w-120px py-2 fs-14" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                    <button type="submit" class="btn btn-primary h-40px min-w-120px py-2 fs-14"><?php echo e(translate('messages.Submit')); ?> </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- End Modal -->
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script src="<?php echo e(asset('assets/admin')); ?>/js/view-pages/offline-verification-list.js"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            let datatable = $.HSCore.components.HSDatatables.init($('#datatable'), {
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        className: 'd-none'
                    },
                    {
                        extend: 'excel',
                        className: 'd-none',
                        action: function (e, dt, node, config)
                        {
                            window.location.href = '<?php echo e(route("admin.order.export",['status'=>$status,'file_type'=>'excel','type'=>$parcel_order?'parcel':'order', request()->getQueryString()])); ?>';
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'd-none',
                        action: function (e, dt, node, config)
                        {
                            window.location.href = '<?php echo e(route("admin.order.export",['status'=>$status,'file_type'=>'csv','type'=>$parcel_order?'parcel':'order', request()->getQueryString()])); ?>';
                        }
                    },
                    // {
                    //     extend: 'pdf',
                    //     className: 'd-none'
                    // },
                    {
                        extend: 'print',
                        className: 'd-none'
                    },
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                        '<img class="w-7rem mb-3" src="<?php echo e(asset('assets/admin')); ?>/svg/illustrations/sorry.svg" alt="Image Description">' +

                        '</div>'
                }
            });
        });
    </script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\order\offline_verification_list.blade.php ENDPATH**/ ?>