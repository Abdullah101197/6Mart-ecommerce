

<?php $__env->startSection('title',translate('messages.language')); ?>



<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header pb-0 mb-20">
            <h1 class="font-bold mb-0"><?php echo e(translate('messages.Language')); ?></h1>
        </div>
        <!-- End Page Header -->

        <div class="card card-body mb-20">
            <div class="mb-20">
                <h3 class="mb-1">
                    <?php echo e(translate('messages.Add New Language')); ?>

                </h3>
                <p class="fs-12 mb-0">
                    <?php echo e(translate('messages.Setup new languages in your system, Website & apps to make order from versatile customers.')); ?>

                </p>
            </div>
            <form action="<?php echo e(route('admin.business-settings.language.add-new')); ?>" method="post"
                style="text-align: <?php echo e(Session::get('direction') === "rtl" ? 'right' : 'left'); ?>;">
                <?php echo csrf_field(); ?>
                <div class="__bg-F8F9FC-card mb-20">
                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="country-code"
                                        class="input-label"><?php echo e(translate('messages.language')); ?></label>
                                <select id="country-code" name="code" class="form-control custom-select js-select2-custom">
                                    <?php $__currentLoopData = \App\CentralLogics\Helpers::getLanguages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="direction" class="input-label"><?php echo e(translate('messages.direction')); ?></label>
                                <div class="resturant-type-group bg-white border">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" value="ltr" name="direction" checked>
                                        <span class="form-check-label">
                                                <?php echo e(translate('messages.Left to Right')); ?>

                                        </span>
                                    </label>
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" value="rtl" name="direction">
                                        <span class="form-check-label">
                                            <?php echo e(translate('messages.Right to Left')); ?>

                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn--container justify-content-end">
                    <button type="reset" class="btn btn--reset"><?php echo e(translate('messages.reset')); ?></button>
                    <button type="submit" class="btn btn--primary"><?php echo e(translate('messages.Submit')); ?></button>
                </div>
            </form>
        </div>

        <div class="card card-body">
            <div class="d-flex gap-3 flex-wrap justify-content-between align-items-center mb-20">
                <h4 class="mb-0"><?php echo e(translate('messages.Language_List')); ?></h4>
            </div>
            <div class="table-responsive datatable-custom" id="table-div">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    data-hs-datatables-options='{
                        "columnDefs": [{
                            "targets": [],
                            "width": "5%",
                            "orderable": false
                        }],
                        "order": [],
                        "info": {
                        "totalQty": "#datatableWithPaginationInfoTotalQty"
                        },

                        "entries": "#datatableEntries",

                        "isResponsive": false,
                        "isShowPaging": false,
                        "paging":false
                    }'>
                    <thead class="thead-light">
                    <tr>
                        <th><?php echo e(translate('SL')); ?></th>
                        <th><?php echo e(translate('Id')); ?></th>
                        <th><?php echo e(translate('Language')); ?></th>
                        <th><?php echo e(translate('Code')); ?></th>
                        <th class="text-center"><?php echo e(translate('Status')); ?></th>
                        <th class="text-center"><?php echo e(translate('Action')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php ($language=App\Models\BusinessSetting::where('key','system_language')->first()); ?>
                    <?php if($language): ?>
                    <?php $__currentLoopData = json_decode($language['value'],true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
                            <td>#<?php echo e($data['id']); ?></td>
                            <td>
                                <span><?php echo e(\App\CentralLogics\Helpers::get_language_name($data['code'])); ?></span>
                                <?php if($data['default']): ?>
                                <span class="text-info bg-info bg-opacity-10 rounded px-2 py-1 fs-12 font-medium">
                                    <?php echo e(translate('messages.Default')); ?>

                                </span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($data['code']); ?></td>
                            <td>
                                <?php if($data['default']): ?>
                                <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($data['id']); ?>">
                                    <input type="checkbox"  class="toggle-switch-input update-lang-status" id="stocksCheckbox<?php echo e($data['id']); ?>" <?php echo e($data['status']==1?'checked':''); ?> disabled>
                                    <span class="toggle-switch-label mx-auto">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                <?php else: ?>
                                <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox<?php echo e($data['id']); ?>">
                                    <input type="checkbox"
                                            data-url="<?php echo e(route('admin.business-settings.language.update-status')); ?>"
                                            data-id="<?php echo e($data['code']); ?>"
                                            class="toggle-switch-input status-update" id="stocksCheckbox<?php echo e($data['id']); ?>" <?php echo e($data['status']==1?'checked':''); ?>>
                                    <span class="toggle-switch-label mx-auto">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-3 justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary d-flex gap-2 align-items-center px-3 py-2 <?php echo e(( ($key == 0 ||  $key == 1 ) && env('APP_MODE') == 'demo') ? 'call-demo-lang' : ''); ?>"
                                        data-key="<?php echo e($key); ?>"
                                        data-env-mode="<?php echo e(env('APP_MODE')); ?>"
                                        href="<?php echo e(( ($key == 0 ||  $key == 1 ) && env('APP_MODE') == 'demo') ? 'javascript:' :route('admin.business-settings.language.translate',[$data['code']])); ?>">
                                        <img width="14" height="14" class="svg" src="<?php echo e(asset('assets/admin/img/svg/language-exchange.svg')); ?>" alt="public">
                                        <span class="fs-12"><?php echo e(translate('messages.View')); ?></span>

                                    </a>
                                    <?php if($data['code']=='en' && $data['default']): ?>
                                    <?php else: ?>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn--primary btn-outline-primary action-btn h-100" data-toggle="dropdown" aria-expanded="false">
                                                <i class="tio-more-vertical fs-24"></i>
                                            </button>
                                            <ul class="dropdown-menu w--180px" dir="ltr">
                                                <?php if($data['default']): ?>
                                                <?php else: ?>
                                                <a href="<?php echo e(route('admin.business-settings.language.update-default-status', ['code'=>$data['code']])); ?>" class="dropdown-item d-flex gap-2 align-items-center cursor-pointer">
                                                    <i class="tio-checkmark-circle-outlined"></i>
                                                    <?php echo e(translate('messages.Mark As Default')); ?>

                                                </a>    
                                                <?php endif; ?>
                                                <?php if($data['code']=='en'): ?>
                                                <?php else: ?>
                                                <a class="dropdown-item d-flex gap-2 align-items-center cursor-pointer call-demo-lang offcanvas-trigger"
                                                    data-key="<?php echo e($key); ?>"
                                                    data-env-mode="<?php echo e(env('APP_MODE')); ?>"
                                                    data-target="<?php echo e(( ($key == 0 ||  $key == 1 ) && env('APP_MODE') == 'demo') ? '' :'#lang-offcanvas-update-'.$data['code']); ?>">
                                                    <i class="tio-edit"></i>
                                                    <?php echo e(translate('messages.Edit')); ?>

                                                </a>
                                                <?php endif; ?>
                                                <?php if($data['code']=='en'): ?>
                                                <?php else: ?>
                                                    <a class="dropdown-item d-flex gap-2 align-items-center cursor-pointer call-demo-lang <?php echo e(( ($key == 0 ||  $key == 1 ) && env('APP_MODE') == 'demo') ? '' : 'delete'); ?>"
                                                        data-key="<?php echo e($key); ?>"
                                                        data-env-mode="<?php echo e(env('APP_MODE')); ?>"
                                                        id="<?php echo e(( ($key == 0 ||  $key == 1 ) && env('APP_MODE') == 'demo')  ? 'javascript:' :route('admin.business-settings.language.delete',[$data['code']])); ?>">
                                                        <i class="tio-delete-outlined"></i>
                                                        <?php echo e(translate('messages.Delete')); ?>

                                                    </a>

                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if($language): ?>
        <?php $__currentLoopData = json_decode($language['value'],true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key =>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="lang-offcanvas-update-<?php echo e($data['code']); ?>" class="custom-offcanvas d-flex flex-column justify-content-between">
            <form action="<?php echo e(route('admin.business-settings.language.update')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="code" value="<?php echo e($data['code']); ?>">
                <input type="hidden" name="old_code" value="<?php echo e($data['code']); ?>">

                <div>
                    <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
                        <div class="py-1">
                            <h3 class="mb-0"><?php echo e(translate('messages.Edit_Language')); ?></h3>
                        </div>
                        <button type="button" class="btn-close w-25px h-25px border bg-white rounded-circle d-center text-dark offcanvas-close fz-15px p-0" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="custom-offcanvas-body custom-offcanvas-body-100  p-20">
                        <div class="mb-9">
                            <div class="p-12 p-sm-20 bg-light rounded mb-3">
                                <div class="form-group">
                                    <label for="" class="input-label">
                                        <?php echo e(translate('Language')); ?>

                                    </label>
                                    <input readonly type="text" class="form-control" placeholder="<?php echo e(translate('Language_Name')); ?>" value="<?php echo e(\App\CentralLogics\Helpers::get_language_name($data['code'])); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="direction-update" class="input-label"><?php echo e(translate('Direction')); ?></label>
                                    <div class="resturant-type-group bg-white border">
                                        <label class="form-check form--check mr-2 mr-md-4">
                                            <input class="form-check-input" type="radio" value="ltr" name="direction"
                                            <?php echo e(isset($data['direction'])?$data['direction']=='ltr'?'checked':'':''); ?> >
                                            <span class="form-check-label">
                                                 <?php echo e(translate('LTR')); ?>

                                            </span>
                                        </label>
                                        <label class="form-check form--check mr-2 mr-md-4">
                                            <input class="form-check-input" type="radio" value="rtl" name="direction"
                                            <?php echo e(isset($data['direction'])?$data['direction']=='rtl'?'checked':'':''); ?>>
                                            <span class="form-check-label">
                                                <?php echo e(translate('RTL')); ?>

                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas-footer d-flex gap-3 justify-content-center align-items-center bg-white bottom-0 mt-auto p-3">
                        <button type="reset" class="btn btn--reset w-100"><?php echo e(translate('reset')); ?></button>
                        <button type="submit" class="btn btn--primary w-100">
                            <?php echo e(translate('update')); ?>

                        </button>
                    </div>
                </div>
            </form>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div id="offcanvasOverlay" class="offcanvas-overlay"></div>
    <?php endif; ?>
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-6">
                <button type="button" class="close position-absolute top-0 right-0 m-3" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="mb-3">
                    <img src="<?php echo e(asset('assets/admin/img/modal/delete.png')); ?>" alt="delete" class="w-12">
                </div>
                <h4 class="modal-title mb-2"><?php echo e(translate('Want to delete this Language')); ?>?</h4>
                <p class="mb-4"><?php echo e(translate('Deleting a language will remove all associated content. This action cannot be undone.')); ?></p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="" id="delete-link" class="btn btn-danger" style="background-color: #FF4040; border-color: #FF4040;"><?php echo e(translate('Yes, Delete')); ?></a>
                    <button type="button" class="btn btn-secondary" style="background-color: #E8EAED; border-color: #E8EAED; color: #000;" data-dismiss="modal"><?php echo e(translate('No, Cancel')); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        "use strict"
        $(".delete").click(function (e) {
            e.preventDefault();
            let link = $(this).attr("id");
            $('#delete-modal').modal('show');
            $('#delete-link').attr('href', link);
        });

        $(".update-lang-status").click(function (e) {
            e.preventDefault();
            toastr.warning('<?php echo e(translate('default language can not be updated! to update change the default language first!')); ?>');
        });

        $(".call-demo-lang").click(function (e) {
            e.preventDefault();
            let key = $(this).data('key');
            let mode = $(this).data('env-mode');

            if(  (key === 0 ||  key === 1 ) &&  mode === 'demo' ){
                toastr.info('<?php echo e(translate('Update option is disabled for demo!')); ?>', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });

        $(".status-update").click(function () {
            $.get({
                url: $(this).data('url'),
                data: {
                    code: $(this).data('id'),
                },

                success: function () {
                    toastr.success('<?php echo e(translate('status_updated_successfully')); ?>');
                    setTimeout(function () {
                        window.location.href =
                            '<?php echo e(route('admin.business-settings.language.index')); ?>';
                    }, 1200);
                }
            });
        });

        $(".update-default").click(function () {
            window.location.href = $(this).data('url');
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\language\index.blade.php ENDPATH**/ ?>