<div class="tabs-slide-wrap position-relative">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 mt-3 __gap-12px">
        <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
            <!-- Nav -->
            <ul class="nav nav-tabs tabs-inner border-0 nav--tabs nav--pills">
                <li class="nav-item">
                    <a class="nav-link  <?php echo e(Request::is('admin/business-settings/business-setup') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup')); ?>"   aria-disabled="true"><?php echo e(translate('messages.business_info')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link  <?php echo e(Request::is('admin/business-settings/business-setup/payment') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'payment'])); ?>"   aria-disabled="true"><?php echo e(translate('Payment')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/store') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'store'])); ?>"  aria-disabled="true"><?php echo e(translate('Vendor')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/order') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'order'])); ?>"  aria-disabled="true"><?php echo e(translate('Order')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/refund-settings') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'refund-settings'])); ?>"  aria-disabled="true"><?php echo e(translate('Refund')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/deliveryman') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'deliveryman'])); ?>"  aria-disabled="true"><?php echo e(translate('Deliveryman')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/customer') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'customer'])); ?>"  aria-disabled="true"><?php echo e(translate('Customer')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/priority') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'priority'])); ?>"  aria-disabled="true"><?php echo e(translate('Priority Setup')); ?></a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/disbursement') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'disbursement'])); ?>"  aria-disabled="true"><?php echo e(translate('Disbursement')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::is('admin/business-settings/business-setup/automated-message') ?'active':''); ?>" href="<?php echo e(route('admin.business-settings.business-setup',  ['tab' => 'automated-message'])); ?>"  aria-disabled="true"><?php echo e(translate('Automated Message')); ?></a>
                </li>
            </ul>
            <!-- End Nav -->
             <div class="arrow-area">
                <div class="button-prev top-18 align-items-center">
                    <button type="button"
                        class="btn btn-click-prev mr-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                        <i class="tio-chevron-left fs-24"></i>
                    </button>
                </div>
                <div class="button-next top-18 align-items-center">
                    <button type="button"
                        class="btn btn-click-next ml-auto border-0 btn-primary rounded-circle fs-12 p-2 d-center">
                        <i class="tio-chevron-right fs-24"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- <?php if(!(Request::is('admin/business-settings/language') || Request::is('admin/business-settings/business-setup/refund-settings') || Request::is('admin/business-settings/business-setup/automated-message'))): ?>
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
        <?php endif; ?> -->
    </div>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\partials\nav-menu.blade.php ENDPATH**/ ?>