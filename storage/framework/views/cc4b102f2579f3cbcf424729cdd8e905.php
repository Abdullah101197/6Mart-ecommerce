

<?php $__env->startSection('title', translate('Automated_Message')); ?>

<?php $__env->startPush('css_or_js'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/owl.min.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="<?php echo e(asset('assets/admin/img/business.png')); ?>" class="w--26" alt="">
                </span>
                <span>
                    <?php echo e(translate('messages.business_setup')); ?>

                </span>
            </h1>
            <?php echo $__env->make('admin-views.business-settings.partials.nav-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <!-- End Page Header -->

        <div class="card mb-20">
            <div class="card-body">
                <div class="mb-0">
                    <h3 class="mb-1">
                        <?php echo e(translate('Automated Message/Reason')); ?>

                    </h3>
                    <p class="mb-0 fs-12">
                        <?php echo e(translate('Setup the predefined reasons which can be choose by the customer when they want to report any issue for the specific order')); ?>

                    </p>
                </div>
                <div class="fs-12 text-dark px-3 py-2 bg-opacity-10 rounded bg-info mt-20">
                    <div class="d-flex align-items-center gap-2 mb-0">
                        <span class="text-info fs-16">
                            <i class="tio-light-on"></i>
                        </span>
                        <span>
                            <?php echo e(translate('All orders you can see and manage from All order list page')); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="mb-0">
                    <h3 class="mb-1">
                        <?php echo e(translate('Automated Message Setup')); ?>

                    </h3>
                    <p class="mb-0 fs-12">
                        <?php echo e(translate('You must setup the automated messageor reason to allow the customer choose from those for reporting')); ?>

                    </p>
                </div>
            </div>
            <div class="card-body">
                <div class="report-card-inner bg-light2 rounded p-xxl-20 p-3 mb-4 mw-100">
                    <form action="<?php echo e(route('admin.business-settings.automated_message.store')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <?php if($language): ?>
                            <ul class="nav nav-tabs nav--tabs d-block nav-slider owl-theme owl-carousel mb-4">
                                <li class="nav-item">
                                    <a class="nav-link lang_link active px-0" href="#"
                                        id="default-link" data-text="<?php echo e(translate('Default')); ?>"><?php echo e(translate('Default')); ?></a>
                                </li>
                                <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <a class="nav-link lang_link px-0" href="#"
                                            id="<?php echo e($lang); ?>-link" data-text="<?php echo e(\App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')'); ?>"><?php echo e(\App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')'); ?></a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                        <div class="row align-items-end">
                            <div class="col-md-12 lang_form default-form">
                                <label for="reason" class="form-label"><?php echo e(translate('Automated_Message/Reason')); ?>

                                    (<?php echo e(translate('Default')); ?>)

                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('You_must_set_predefined_reasons_for_customers_to_select_This_will_guide_them_in_choosing_a_reason_when_reporting_any_issues_with_their_order.')); ?>">
                                        <i class="tio-info text-muted"></i>
                                    </span>
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea id="reason" type="text" class="form-control h--45px" name="message[]" maxlength="150"
                                    placeholder="<?php echo e(translate('Ex:Enter_the_message')); ?>"></textarea>
                                <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/150</span>
                                <input type="hidden" name="lang[]" value="default">
                            </div>
                            <?php if($language): ?>
                                <?php $__currentLoopData = $language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-12 d-none lang_form" id="<?php echo e($lang); ?>-form">
                                        <label for="reason<?php echo e($lang); ?>"
                                            class="form-label"><?php echo e(translate('Automated_Message/Reason')); ?>

                                            (<?php echo e(strtoupper($lang)); ?>)
                                        </label>
                                        <textarea id="reason<?php echo e($lang); ?>" type="text" class="form-control h--45px" name="message[]" maxlength="150"
                                            placeholder="<?php echo e(translate('Ex:Enter_the_message')); ?>"></textarea>
                                        <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/150</span>
                                        <input type="hidden" name="lang[]" value="<?php echo e($lang); ?>">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                        <div class="mt-20 btn--container justify-content-end">
                            <button type="reset" id="reset_btn"
                                class="btn btn--reset"><?php echo e(translate('messages.reset')); ?></button>
                            <button type="submit" class="btn btn--primary"><?php echo e(translate('messages.Save')); ?></button>
                        </div>

                    </form>
                </div>
                <div class="card">
                    <div class="card-header align-items-center gap-3 flex-wrap border-0">
                        <div class="mx-1">
                            <h4 class="mb-0">
                                <?php echo e(translate('Message List')); ?>

                                <span class="badge badge-soft-dark ml-2" id="itemCount"><?php echo e($messages->total()); ?></span>
                            </h4>
                        </div>
                        <div class="search--button-wrapper justify-content-end">
                            <form class="search-form">
                                <!-- Search -->
                                <div class="input-group input--group">
                                    <input id="datatableSearch" name="search" value="<?php echo e(request()?->search ?? null); ?>"
                                        type="search" class="form-control h--40px"
                                        placeholder="<?php echo e(translate('ex_:search_here')); ?>"
                                        aria-label="<?php echo e(translate('messages.search_here')); ?>">
                                    <button type="submit" class="btn btn--secondary h--40px"><i
                                            class="tio-search"></i></button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <?php if(request()->get('search')): ?>
                                <button type="reset" class="btn btn--primary ml-2 location-reload-to-base"
                                    data-url="<?php echo e(url()->full()); ?>"><?php echo e(translate('messages.reset')); ?></button>
                            <?php endif; ?>
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
                                        <th class="border-0"><?php echo e(translate('messages.message')); ?></th>
                                        <th class="border-0"><?php echo e(translate('messages.status')); ?></th>
                                        <th class="border-0 text-center"><?php echo e(translate('messages.action')); ?></th>
                                    </tr>
                                </thead>

                                <tbody id="table-div">
                                    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fs-14 text-dark"><?php echo e($key + $messages->firstItem()); ?></td>

                                            <td>
                                                <span
                                                    class="d-block text-body fs-14 text-dark line--limit-2 min-w-176px max-w-490"
                                                    title="<?php echo e($message->message); ?>">
                                                    <?php echo e(Str::limit($message->message, 50, '...')); ?>

                                                </span>
                                            </td>
                                            <td>
                                                <label class="toggle-switch toggle-switch-sm"
                                                    for="stocksCheckbox<?php echo e($message->id); ?>">
                                                    <input type="checkbox"
                                                        data-url="<?php echo e(route('admin.business-settings.automated_message.status', [$message['id'], $message->status ? 0 : 1])); ?>"
                                                        class="toggle-switch-input redirect-url"
                                                        id="stocksCheckbox<?php echo e($message->id); ?>"
                                                        <?php echo e($message->status ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                            </td>

                                            <td>
                                                <div class="btn--container justify-content-center">
                                                    <a class="btn btn-sm text-end action-btn info--outline text--info info-hover offcanvas-trigger get_data data-info-show"
                                                        data-target="#offcanvas__customBtn3"
                                                        data-id="<?php echo e($message->id); ?>"
                                                        data-url="<?php echo e(route('admin.business-settings.automated_message.edit', [$message->id])); ?>"
                                                        href="javascript:"
                                                        title="<?php echo e(translate('messages.edit_Message')); ?>"><i
                                                            class="tio-edit"></i></a>



                                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn form-alert"
                                                        href="javascript:" data-id="refund_reason-<?php echo e($message['id']); ?>"
                                                        data-message="<?php echo e(translate('Want to delete this message ?')); ?>"
                                                        title="<?php echo e(translate('messages.delete')); ?>">
                                                        <i class="tio-delete-outlined"></i>
                                                    </a>
                                                    <form
                                                        action="<?php echo e(route('admin.business-settings.automated_message.destroy', [$message['id']])); ?>"
                                                        method="post" id="refund_reason-<?php echo e($message['id']); ?>">
                                                        <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php if(count($messages) === 0): ?>
                                <div class="empty--data">
                                    <img src="<?php echo e(asset('/assets/admin/svg/illustrations/sorry.svg')); ?>"
                                        alt="public">
                                    <h5>
                                        <?php echo e(translate('no_data_found')); ?>

                                    </h5>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer pt-0 border-0">
                            <div class="page-area px-4 pb-3">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div>
                                        <?php echo $messages->withQueryString()->links(); ?>

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

    <div id="global_guideline_offcanvas"
    class="custom-offcanvas d-flex flex-column justify-content-between global_guideline_offcanvas">
    <div>
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0"><?php echo e(translate('Automated Message Guideline')); ?></h3>
            <button type="button"
                class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary offcanvas-close fz-15px p-0"
                aria-label="Close">&times;</button>
        </div>

        <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
            <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                    type="button" data-toggle="collapse" data-target="#automated_message_guide" aria-expanded="true">
                    <div
                        class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                        <i class="tio-down-ui"></i>
                    </div>
                    <span class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Automated Message')); ?></span>
                </button>
                <a href="#automated_message_section"
                    class="text-info text-underline fs-12 text-nowrap offcanvas-close offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
            </div>
            <div class="collapse mt-3 show" id="automated_message_guide">
                <div class="card card-body">
                    <div class="">
                        <h5 class="mb-3"><?php echo e(translate('Automated Message')); ?></h5>
                        <p class="fs-12 mb-0">
                            <?php echo e(translate('Create and manage predefined messages that customers can choose when reporting an issue. You can activate or deactivate each message as needed.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
    <div id="offcanvas__customBtn3" class="custom-offcanvas d-flex flex-column justify-content-between">
        <div id="data-view" class="h-100">
        </div>
    </div>


<?php $__env->stopSection(); ?>
<?php $__env->startPush('script_2'); ?>
<script src="<?php echo e(asset('assets/admin/js/view-pages/offcanvas-edit.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/owl.min.js')); ?>"></script>
    <script>
        $('.nav-slider').owlCarousel({
            margin: 30,
            loop: false,
            autoWidth: true,
            items: 4
        })

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\settings\automated-message.blade.php ENDPATH**/ ?>