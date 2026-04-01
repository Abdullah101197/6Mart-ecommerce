

<?php $__env->startSection('title', translate('admin_landing_page')); ?>

<?php $__env->startSection('content'); ?>
    <?php
    use Illuminate\Support\Facades\File;

    $filePath = resource_path('views/layouts/landing/custom/index.blade.php');

    $custom_file = File::exists($filePath);
    ?>

    <div class="content">
        <form id="theme_form" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="container-fluid">
                <!-- Page Header -->
                <div class="page-header pb-0">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h1 class="page-header-title">
                            <span class="page-header-icon">
                                <img src="<?php echo e(asset('assets/admin/img/landing.png')); ?>" class="w--20" alt="">
                            </span>
                            <span>
                                <?php echo e(translate('messages.admin_landing_pages')); ?>

                            </span>
                        </h1>
                        <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#how-it-works">
                            <strong class="mr-2"><?php echo e(translate('See_how_it_works!')); ?></strong>
                            <div>
                                <i class="tio-info-outined"></i>
                            </div>
                        </div>
                    </div>
                    <?php echo $__env->make('admin-views.business-settings.landing-page-settings.top-menu-links.admin-landing-page-links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="card card-body mb-3">
                    <h3 class="mb-1"><?php echo e(translate('messages.Landing Page Setup')); ?></h3>
                    <p class="fs-12 mb-0"><?php echo e(translate('messages.Here you can manage the Landing Page setup which will be displayed')); ?></p>
                </div>

                <div class="card card-body">
                     <?php ($config = \App\CentralLogics\Helpers::get_business_settings('landing_page')); ?>
                    <?php ($landing_integration_type = \App\CentralLogics\Helpers::get_business_data('landing_integration_type')); ?>
                    <?php ($redirect_url = \App\CentralLogics\Helpers::get_business_data('landing_page_custom_url')); ?>
                    <div class="__bg-F8F9FC-card mb-20">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <h4 class="mb-1"><?php echo e(translate('messages.Choose Admin Landing Page')); ?></h4>
                                    <p class="fs-12 mb-0">
                                        <?php echo e(translate('messages.Setup which types of admin landing page you want to show.')); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="resturant-type-group border bg-white w-100 d-inline-flex gap-3">
                                    <label class="form-check form--check flex-grow-1">
                                        <input class="form-check-input landing-page"
                                            type="radio"
                                            name="choose_admin_landing"
                                            id="default_landing"
                                            value="default"
                                            <?php echo e(isset($config) && $config ? 'checked' : ''); ?>>
                                        <span class="form-check-label">
                                            <?php echo e(translate('messages.Default Landing Page')); ?>

                                        </span>
                                    </label>

                                    <label class="form-check form--check flex-grow-1">
                                        <input class="form-check-input landing-page"
                                            type="radio"
                                            name="choose_admin_landing"
                                            id="custom_landing"
                                            value="custom"
                                            <?php echo e(isset($config) && !$config ? 'checked' : ''); ?>>
                                        <span class="form-check-label">
                                            <?php echo e(translate('messages.Custom Landing Page')); ?>

                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="custom-landing-page-setup" class="__bg-F8F9FC-card mb-20" style="<?php echo e(isset($config) && $config ? 'display: none' : ''); ?>">
                        <div class="row g-3 mb-20">
                            <div class="col-lg-6">
                                <div>
                                    <h4 class="mb-1"><?php echo e(translate('messages.How to Integrate Custom Landing Page')); ?></h4>
                                    <p class="fs-12 mb-0">
                                        <?php echo e(translate('Choose the integration method for your custom landing page, then provide the required information.')); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="resturant-type-group border bg-white w-100 d-inline-flex gap-3">
                                    <label class="form-check form--check flex-grow-1">
                                        <input class="form-check-input" type="radio" value="url" name="landing_integration_via" <?php echo e($landing_integration_type == 'url'?'checked':''); ?>>
                                        <span class="form-check-label">
                                            <?php echo e(translate('messages.url')); ?>

                                        </span>
                                    </label>
                                    <label class="form-check form--check flex-grow-1">
                                        <input class="form-check-input" type="radio" value="file_upload" name="landing_integration_via" <?php echo e($landing_integration_type == 'file_upload'?'checked':''); ?>>
                                        <span class="form-check-label">
                                            <?php echo e(translate('file_upload')); ?>

                                        </span>
                                    </label>
                                    <label class="form-check form--check flex-grow-1">
                                        <input class="form-check-input" type="radio" value="none" name="landing_integration_via" <?php echo e($landing_integration_type == 'none' ?'checked':''); ?>>
                                        <span class="form-check-label">
                                            <?php echo e(translate('none')); ?>

                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <div class="__input-tab <?php echo e($landing_integration_type == 'url'?'active':''); ?>" id="url">
                                <div class="form-group mb-20">
                                    <label class="input-label text-capitalize d-flex gap-1 align-items-center">
                                        <?php echo e(translate('landing_page_url')); ?>

                                        <span class="tio-info text-light-gray fs-16" data-toggle="tooltip"
                                            data-placement="right"
                                            data-original-title="<?php echo e(translate('Provide the URL of the landing page where users will be redirected')); ?>">
                                        </span>
                                    </label>
                                    <input type="text"
                                        placeholder="<?php echo e(translate('messages.Ex: https://6ammart-web.6amtech.com/')); ?>"
                                        class="form-control h--45px" id="redirect_url" name="redirect_url" value="<?php echo e($redirect_url); ?>">
                                </div>
                                <div class="fs-12 px-3 py-2 rounded bg-info bg-opacity-10">
                                    <div class="d-flex gap-2 mb-3">
                                        <span class="text-info lh-1 fs-14">
                                            <img src="<?php echo e(asset('assets/admin/img/svg/bulb.svg')); ?>" class="svg" alt="">
                                        </span>
                                        <h4 class="font-medium mb-0">
                                            <?php echo e(translate('messages.If you want to set up your own landing page please follow tha instructions below')); ?>

                                        </h4>
                                    </div>
                                    <ul class="d-flex flex-column gap-2">
                                        <li>
                                            <?php echo e(translate('messages.You can add your customized landing page via URL or upload ZIP file of the landing page.')); ?>

                                        </li>
                                        <li>
                                            <?php echo e(translate('messages.If you want to use URL option. Just host you landing page and copy the page URL and click save information.')); ?>

                                        </li>
                                        <li>
                                            <?php echo e(translate('messages.If you want to upload your landing page source code file:')); ?>

                                            <ol type="a" class="pl-3">
                                                <li>
                                                    <?php echo e(translate('messages.Create an html file named index.blade.php and insert your landing page design code and make a zip file.')); ?>

                                                </li>
                                                <li>
                                                    <?php echo e(translate('messages.Upload the zip file in file upload section and click save information.')); ?>

                                                </li>
                                            </ol>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="__input-tab <?php echo e($landing_integration_type == 'file_upload'?'active':''); ?>" id="file_upload">
                                <div class="card card-body">
                                    <div class="mb-20">
                                        <h5 class="mb-1"><?php echo e(translate('messages.Upload PHP File')); ?></h5>
                                        <p class="fs-12 mb-0">
                                            <?php echo e(translate('messages.Here you need to upload your custome designed PHP file that will work as a Admin Landing Page.')); ?>

                                        </p>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-6">
                                            <div class="fs-12 text-dark px-3 py-2 rounded bg-info d-flex align-items-center h-100 bg-opacity-10">
                                                <div>
                                                    <div class="d-flex gap-2 mb-3">
                                                        <span class="text-info lh-1 fs-14">
                                                            <img src="<?php echo e(asset('/assets/admin/img/svg/bulb.svg')); ?>" class="svg" alt="">
                                                        </span>
                                                        <h4 class="text-title mb-0">
                                                            <?php echo e(translate('messages.Instructions')); ?>

                                                        </h4>
                                                    </div>
                                                    <ul class="d-flex flex-column gap-2">
                                                        <li>
                                                            <?php echo e(translate('messages.Create an html file named index.blade.php and insert your landing page design code and make a zip file.')); ?>

                                                        </li>
                                                        <li>
                                                            <?php echo e(translate('messages.Upload file must be zip file format in and click save information.')); ?>

                                                        </li>
                                                        <li>
                                                            <?php echo e(translate('messages.Without save the changes Landing page can’t update properly and you can’t see the updated preview.')); ?>

                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="__bg-F8F9FC-card text-center">
                                                <div class="file-upload-parent">
                                                    <div class="custom-file-upload">
                                                        <input type="hidden" name="file_exist" value="<?php echo e($custom_file ? 1 : 0); ?>">
                                                        <input type="file" accept=".zip" data-max-file-size="<?php echo e(MAX_FILE_SIZE); ?>MB" name="file_upload" data-warning-message="<?php echo e(translate('messages.please_delete_the_existing_landing_page_first')); ?>">
                                                        <div class="text-center p-3 p-sm-4">
                                                            <div class="mb-20">
                                                                <img width="48" height="48" class="svg" src="<?php echo e(asset('/assets/admin/img/svg/upload-cloud.svg')); ?>" alt="">
                                                            </div>
                                                            <p class="mb-0 fs-14 mb-1 text-title">
                                                                <?php echo e(translate('messages.Select a file or Drag & Drop here')); ?>

                                                                <span class="font-semibold"><?php echo e(translate('messages. Drag & Drop')); ?> </span>
                                                                <?php echo e(translate('messages.here')); ?>

                                                            </p>
                                                            <div class="mb-0 fs-12"><?php echo e(translate('messages.PHP file size no more than '.MAX_FILE_SIZE.'MB')); ?></div>
                                                            <div class="btn btn-outline-primary font-semibold mt-4 trigger_input_btn">
                                                                <?php echo e(translate('messages.Select File')); ?>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="file-preview-list d-flex flex-column gap-4"></div>
                                                    <div id="file-upload-config" data-icon-src="<?php echo e(asset('/assets/admin/img/file-view.png')); ?>"></div>
                                                    <?php if($custom_file): ?>
                                                    <div id="existing-file-preview" class="file-preview-list_single mt-3 bg-white rounded-10 d-flex align-items-center justify-content-between gap-3 p-3">
                                                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                            <img width="18" class="aspect-1 flex-shrink-0" src="<?php echo e(asset('/assets/admin/img/file-view.png')); ?>" alt="">
                                                            <span class="fs-12 line--limit-1 fw-medium text-title file_title"><?php echo e(translate('messages.index.blade.php')); ?></span>
                                                        </div>
                                                        <button type="button" class="btn p-0 d-flex align-items-center justify-content-center btn-danger btn-circle rounded-circle close_btn delete_btn">
                                                            <i class="tio-clear fs-14"></i>
                                                        </button>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="__input-tab <?php echo e($landing_integration_type == 'none' ?'active':''); ?>" id="none">
                                <div class="d-flex gap-2 fs-12 text-dark px-3 py-2 rounded bg-warning bg-opacity-10">
                                    <span class="text-warning lh-1 fs-14">
                                        <i class="tio-info"></i>
                                    </span>
                                    <span>
                                        <?php echo e(translate('messages.Without save the changes,')); ?>

                                        <span class="font-semibold"><?php echo e(translate('messages.Landing Page')); ?> </span>
                                        <?php echo e(translate('messages.can’t update properly and you can’t see the updated preview.')); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="__bg-F8F9FC-card d-flex flex-wrap gap-3 align-items-center justify-content-between">
                         <div class="">
                            <?php if(isset($config) && !$config && $landing_integration_type == 'none'): ?>
                            <h4 class="mb-1"><?php echo e(translate('messages.Currently You are Not Using')); ?></h4>
                            <?php else: ?>
                            <h4 class="mb-1"><?php echo e(translate('messages.Currently You are Using')); ?></h4>
                            <?php endif; ?>
                            <?php if(isset($config) && $config): ?>
                            <p class="fs-12 mb-0">
                                <span class="font-semibold"><?php echo e((\App\CentralLogics\Helpers::get_business_data('business_name') ?? "6amMart" ) .' '. translate('Default')); ?> </span>
                                <?php echo e(translate('Landing Page')); ?>

                            </p>
                            <?php else: ?>
                                <?php if($landing_integration_type == 'url'): ?>
                                    <p class="fs-12 mb-0">
                                        <span class="font-semibold"><?php echo e(translate('messages.Custom Landing Page')); ?> </span>
                                        <?php echo e(translate('with a external Landing Page')); ?>

                                        <span class="font-semibold"><?php echo e(translate('URL')); ?></span>
                                    </p>
                                <?php elseif($landing_integration_type == 'file_upload'): ?>
                                    <p class="fs-12 mb-0">
                                        <span class="font-semibold"><?php echo e(translate('messages.Custom Landing Page')); ?> </span>
                                        <?php echo e(translate('with a')); ?>

                                        <span class="font-semibold"><?php echo e(translate('PHP File.')); ?></span>
                                    </p>
                                <?php else: ?>
                                    <p class="fs-12 mb-0">
                                        <?php echo e(translate('Any')); ?>

                                        <span class="font-semibold"><?php echo e(translate('messages.Custom Landing Page')); ?> </span>
                                        <?php echo e(translate('for Admin Landing Page. If anyone search landing page URL they will see')); ?>

                                        <span class="font-semibold"><?php echo e(translate('404 page')); ?></span>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <a target="_blank" rel="noopener noreferrer" href="<?php echo e(route('home')); ?>"
                            class="btn btn--primary">
                                <?php echo e(translate('Visit_Landing_Page')); ?>

                            <i class="tio-open-in-new"></i>
                        </a>


                    </div>

                </div>

            </div>
            <div class="footer-sticky mt-2">
                <div class="container-fluid">
                    <div class="d-flex flex-wrap gap-3 justify-content-center py-3">
                        <button type="reset" id="reset_btn" class="btn btn--reset min-w-120"><?php echo e(translate('Reset')); ?></button>
                        <button type="button"  class="btn btn--primary form-submit" id="update_setting">
                            <i class="tio-save"></i>
                            <?php echo e(translate('Save_Information')); ?></button>
                    </div>
                </div>
            </div>
        </form>
         <form action="<?php echo e(route('admin.business-settings.delete-custom-landing-page')); ?>" method="post" id="index_page">
            <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
        </form> 
    </div>

    <div class="modal fade" id="read-instructions">
        <div class="modal-dialog status-warning-modal max-w-842">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true" class="tio-clear"></span>
                    </button>
                </div>
                <div class="modal-body px-4 px-md-5 pb-5 pt-0">
                    <div class="single-item-slider owl-carousel">
                        <div class="item">
                            <div class="mb-20">
                                <div class="text-center">
                                    <img src="<?php echo e(asset('/assets/admin/img/read-instructions.png')); ?>"
                                        alt="" class="mb-20">
                                    <h5 class="modal-title">
                                        <?php echo e(translate('If_you_want_to_set_up_your_own_landing_page_please_follow_tha_instructions_below')); ?>

                                    </h5>
                                </div>
                                <ol type="1">
                                    <li>
                                        <?php echo e(translate('You_can_add_your_customised_landing_page_via_URL_or_upload_ZIP_file_of_the_landing_page.')); ?>

                                    </li>
                                    <li>
                                        <?php echo e(translate('If_you_want_to_use_URL_option._Just_host_you_landing_page_and_copy_the_page_URL_and_click_save_information.')); ?>

                                    </li>
                                    <li>
                                        <?php echo e(translate('If_you_want_to_Upload_your_landing_page_source_code_file.')); ?>


                                        <div class="ms-2 mt-1">
                                            <?php echo e(translate('a._Create_an_html_file_named')); ?> <b
                                                class="bg--4 text--primary-2">index.blade.php</b>
                                            <?php echo e(translate('_and_insert_your_landing_page_design_code_and_make_a_zip_file.')); ?>


                                        </div>
                                        <div class="ms-2 mt-1">
                                            <?php echo e(translate('b._upload_the_zip_file_in_file_upload_section_and_click_save_information.')); ?>

                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="slide-counter"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script src="<?php echo e(asset('assets/admin/js/view-pages/business-settings-landing-page.js')); ?>"></script>

    <script>

        "use strict";

        $(document).ready(function() {
            $('.landing-page').on('change', function(event) {
                if ($(this).val() == 'custom') {
                    $('#custom-landing-page-setup').slideDown();
                } else {
                    $('#custom-landing-page-setup').slideUp();
                }
            });
            $(document).on('change', 'input[name="file_upload"]', function (e) {
                if ($('#existing-file-preview').length > 0) {
                    e.preventDefault();
                    // Reset file input
                    this.value = '';
                    // Remove any new preview that might have been generated by common.js
                    $('.file-preview-list').empty();

                    toastr.error(
                        "<?php echo e(translate('messages.please_delete_the_existing_landing_page_first')); ?>",
                        {
                            CloseButton: true,
                            ProgressBar: true
                        }
                    );
                }
            });

             $('.form-submit').on('click', function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let formData = new FormData(document.getElementById('theme_form'));
                $.ajax({
                    type: 'POST',
                    url: "<?php echo e(route('admin.business-settings.update-landing-setup')); ?>",
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        let xhr = new window.XMLHttpRequest();
                        if ($('#inputFile').val()) {
                            $('#progress-bar').show();
                        }

                        xhr.upload.addEventListener("progress", function(e) {
                            if (e.lengthComputable) {
                                let percentage = Math.round((e.loaded * 100) / e.total);
                                $("#uploadProgress").val(percentage);
                                $("#progress-label").text(percentage + "%");
                            }
                        }, false);

                        return xhr;
                    },
                    beforeSend: function() {
                        $('#update_setting').attr('disabled');
                    },
                    success: function(response) {
                        if (response.status === 'error') {
                            $('#progress-bar').hide();
                            toastr.error(response.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        } else if (response.status === 'success') {
                            toastr.success(response.message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function (xhr) {
                        $('#progress-bar').hide();

                        if (xhr.status === 413) {
                            const $fileInput = $('input[name="file_upload"]');
                            const $maxFileSize = $('input[name="file_upload"]').data('max-file-size');
                            $fileInput.val('');
                            toastr.error('Uploaded file is too large. Please upload a smaller file. Server Max file size is ' + $maxFileSize + 'MB');
                        } else if (xhr.responseJSON?.message) {
                            toastr.error(xhr.responseJSON.message);
                        } else {
                            toastr.error('Something went wrong. Please try again.');
                        }
                    },
                    complete: function() {
                        $('#update_setting').removeAttr('disabled');
                    },
                });

            });

        });

        $('.delete_btn').on('click', function () {
            $('#index_page').submit();
        });

        // const MAX_FILE_SIZE = <?php echo e(MAX_FILE_SIZE ?? 2); ?>;


        $('#reset_btn').click(function() {
            setTimeout(function(){
                if ($('input[name="choose_admin_landing"]:checked').val() == 'custom') {
                    $('#custom-landing-page-setup').slideDown();
                } else {
                    $('#custom-landing-page-setup').slideUp();
                }
            }, 10);

            $('.file-upload-parent').html(`
                <div class="custom-file-upload">
                    <input type="hidden" name="file_exist" value="<?php echo e($custom_file ? 1 : 0); ?>">
                    <input type="file" accept=".zip" data-max-file-size="<?php echo e(MAX_FILE_SIZE); ?>MB" name="file_upload" data-warning-message="<?php echo e(translate('messages.please_delete_the_existing_landing_page_first')); ?>">
                    <div class="text-center p-3 p-sm-4">
                        <div class="mb-20">
                            <img width="48" height="48" class="svg" src="<?php echo e(asset('/assets/admin/img/svg/upload-cloud.svg')); ?>" alt="">
                        </div>
                        <p class="mb-0 fs-14 mb-1 text-title">
                            <?php echo e(translate('messages.Select a file or Drag & Drop here')); ?>

                            <span class="font-semibold"><?php echo e(translate('messages. Drag & Drop')); ?> </span>
                            <?php echo e(translate('messages.here')); ?>

                        </p>
                        <div class="mb-0 fs-12">
                            <?php echo e(translate('messages.PHP file size no more than :size MB', ['size' => MAX_FILE_SIZE])); ?>

                        </div>
                        <div class="btn btn-outline-primary font-semibold mt-4 trigger_input_btn">
                            <?php echo e(translate('messages.Select File')); ?>

                        </div>
                    </div>
                </div>
                <div class="file-preview-list d-flex flex-column gap-4"></div>
                <div id="file-upload-config" data-icon-src="<?php echo e(asset('/assets/admin/img/file-view.png')); ?>"></div>
                <?php if($custom_file): ?>
                <div id="existing-file-preview" class="file-preview-list_single mt-3 bg-white rounded-10 d-flex align-items-center justify-content-between gap-3 p-3">
                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                        <img width="18" class="aspect-1 flex-shrink-0" src="<?php echo e(asset('/assets/admin/img/file-view.png')); ?>" alt="">
                        <span class="fs-12 line--limit-1 fw-medium text-title file_title"><?php echo e(translate('messages.index.blade.php')); ?></span>
                    </div>
                    <button type="button" class="btn p-0 d-flex align-items-center justify-content-center btn-danger btn-circle rounded-circle close_btn delete_btn">
                        <i class="tio-clear fs-14"></i>
                    </button>
                </div>
                <?php endif; ?>
            `);

            // Re-initialize file uploads and trigger inputs
            initializeFileUploads();
            setupTriggerInputs();

            $(`.__input-tab`).removeClass('active')
            $(`#<?php echo e($landing_integration_type); ?>`).addClass('active')
        })


    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\landing-page-settings\admin-setup.blade.php ENDPATH**/ ?>