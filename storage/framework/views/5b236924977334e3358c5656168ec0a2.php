

<?php $__env->startSection('title',translate('messages.SEO Setup')); ?>

<?php $__env->startSection('content'); ?>
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title text-break">
            <span class="page-header-icon">
                <img src="<?php echo e(asset('assets/admin/img/seo-setting.png')); ?>" class="w--26" alt="">
            </span>
            <span><?php echo e(translate('Manage Page SEO')); ?></span>
        </h1> 
    </div>
    <!-- End Page Header -->

    <form action="<?php echo e(route('admin.business-settings.seo-settings.pageMetaDataUpdate')); ?>" method="POST"
                                      enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="page_name" value="<?php echo e(request()->page_name); ?>">
                                    <?php echo $__env->make('admin-views.business-settings.landing-page-settings.partial._meta_data',['submit'=>true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>
    
    

</div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\seo-settings\page-meta-data-edit.blade.php ENDPATH**/ ?>