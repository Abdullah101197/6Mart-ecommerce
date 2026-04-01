

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
    <div class="bg-opacity-primary-10 rounded py-2 px-3 d-flex flex-wrap gap-1 align-items-center mb-20">
        <div class="gap-1 d-flex align-items-center">
            <i class="tio-light-on theme-clr-dark fs-16"></i>
            <p class="m-0 fs-12"><?php echo e(translate('Manage meta information to improve page performance in search results')); ?></p>
        </div>
    </div>
    <!-- End Page Header -->
 
    <div class="card">
        <div class="card-header flex-wrap pt-3 pb-3 border-0 gap-2">
            <div class="search--button-wrapper mr-1">
                <h4 class="card-title fs-16 text-dark"><?php echo e(translate('SEO Setup List')); ?> <span class="badge badge-soft-dark ml-2 rounded-circle fs-12" id="itemCount"><?php echo e(count($pages)); ?></span></h4>
                <form class="search-form min--260" onsubmit="event.preventDefault()">
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control h--40px" placeholder="<?php echo e(translate('Search Page Name')); ?>" aria-label="Search" tabindex="1">

                        <button type="button" class="btn btn--secondary bg-modal-btn"><i class="tio-search text-muted"></i></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive space-around-16 datatable-custom">
                <table class="table table-borderless table-thead-borderless table-align-middle table-nowrap card-table m-0">
                    <thead class="thead-light">
                        <tr>
                            <th class="border-0 min-w--120"><?php echo e(translate('SL')); ?></th>
                            <th class="border-0"><?php echo e(translate('Pages')); ?></th>
                            <th class="border-0 text-right"><?php echo e(translate('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td>
                                <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                    <?php echo e(translate($page == 'contact_us_page' ? 'Help & Support Page' : $page)); ?>

                                </div>
                            </td>
                            <td>
                                <div class="text-right">
                                    <a href="<?php echo e(route('admin.business-settings.seo-settings.pageMetaData', ['page_name' => $page])); ?>" class="btn <?php echo e(isset($pageMetaData[$page][0]) ? 'btn-outline-theme-dark' : 'btn-outline-success'); ?>">
                                        <?php if(isset($pageMetaData[$page][0])): ?>
                                            <i class="tio-edit"></i> <?php echo e(translate('Edit Content')); ?>

                                        <?php else: ?>
                                            <i class="tio-add"></i> <?php echo e(translate('Add Content')); ?>

                                        <?php endif; ?>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3">
                                <div class="empty--data">
                                    <img src="<?php echo e(asset('assets/admin/img/modal/pending-order-off.png')); ?>" alt="public">
                                    <h5>
                                        <?php echo e(translate('no_data_found')); ?>

                                    </h5>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr class="empty-data-row" style="display: none;">
                            <td colspan="3">
                                <div class="empty--data">
                                    <img src="<?php echo e(asset('assets/admin/img/modal/pending-order-off.png')); ?>" alt="public">
                                    <h5>
                                        <?php echo e(translate('no_data_found')); ?>

                                    </h5>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- End Table -->
        </div>
    </div>             
</div>

<!-- global guideline view Offcanvas here -->
<div id="global_guideline_offcanvas" class="custom-offcanvas d-flex flex-column justify-content-between">
    <form action="<?php echo e(route('taxvat.store')); ?>" method="post">
        <div>
            <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
                <div class="py-1">
                    <h3 class="mb-0 line--limit-1"><?php echo e(translate('messages.Meta Data Setup')); ?></h3>
                </div>
                <button type="button" class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary text-dark offcanvas-close fz-15px p-0"aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="custom-offcanvas-body custom-offcanvas-body-100  p-20">
                <div class="">
                    <div class="py-3 px-3 bg-light rounded mb-3">
                        <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                            <button class="btn-collapse line--limit-1 d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapse show" type="button"
                                    data-toggle="collapse" data-target="#collapseGeneralSetup_01" aria-expanded="true">
                                <div class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                                    <i class="tio-down-ui top-01 color-656566"></i>
                                </div>
                                <span class="font-semibold text-left fs-14 text-title line--limit-1"><?php echo e(translate('What is Metadata Setup for pages?')); ?></span>
                            </button>
                        </div>
                        <div class="collapse mt-3 show" id="collapseGeneralSetup_01">
                            <div class="card rounded border p-3 card-body">
                                <div class="mb-3">
                                    <p class="m-0 fs-12 color-656566">
                                        <strong><?php echo e(translate('Meta Data Setup')); ?></strong> <?php echo e(translate('allows you to define how each page of your e-commerce site appears in:')); ?>

                                    </p>
                                </div>
                                <div class="mb-3">
                                    <ul class="mb-0 list-group pl-3 d-flex flex-column gap-1px">
                                        <li class="fs-12 color-656566"><strong><?php echo e(translate('Search engines')); ?></strong> <?php echo e(translate(' (Google, Bing, etc.)')); ?></li>
                                        <li class="fs-12 color-656566"><strong><?php echo e(translate('Social media shares')); ?></strong> <?php echo e(translate(' (Facebook, WhatsApp, Twitter, LinkedIn)')); ?></li>
                                    </ul>
                                </div>
                                <p class="m-0 fs-12 color-656566">
                                    <strong><?php echo e(translate('Important Note:')); ?></strong> <?php echo e(translate('Metadata does not change page content, but it strongly affects visibility, traffic, and click-through rate.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-12 p-sm-20 bg-light rounded mb-3">
                        <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                            <button class="btn-collapse line--limit-1 d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseGeneralSetup_032" aria-expanded="true">
                                <div class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1 collapsed">
                                    <i class="tio-down-ui top-01 color-656566"></i>
                                </div>
                                <span class="font-semibold text-left fs-14 text-title line--limit-1"><?php echo e(translate('Why Set Up Metadata for Pages?')); ?></span>
                            </button>
                        </div>
                        <div class="collapse mt-3" id="collapseGeneralSetup_032">
                            <div class="card rounded border p-3 card-body"> 
                                <div class="mb-3">
                                    <p class="m-0 font-weight-medium color-656566 fs-12"><?php echo e(translate('Different e-commerce pages serve other purposes, so they need different SEO behaviour. Overall, This Setup Is Important for')); ?></p>
                                </div>                               
                                <div class="mb-3">
                                    <h6 class="mb-2 fs-12 color-656566"><?php echo e(translate('Calculate Tax Included in Product Price')); ?></h6>
                                    <ul class="mb-0 list-group pl-3 d-flex flex-column gap-1px">
                                        <li class="fs-12 color-656566"><?php echo e(translate('Improves Google ranking')); ?></li>
                                        <li class="fs-12 color-656566"><?php echo e(translate('Increases organic traffic')); ?></li>
                                        <li class="fs-12 color-656566"><?php echo e(translate('Controls which pages appear in search')); ?></li>
                                        <li class="fs-12 color-656566"><?php echo e(translate('Improves social media sharing previews')); ?></li>
                                        <li class="fs-12 color-656566"><?php echo e(translate('Prevents private pages from being indexed')); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-12 p-sm-20 bg-light rounded mb-3">
                        <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                            <button class="btn-collapse line--limit-1 d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                                    data-toggle="collapse" data-target="#collapseGeneralSetup_033" aria-expanded="true">
                                <div class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1 collapsed">
                                    <i class="tio-down-ui top-01 color-656566"></i>
                                </div>
                                <span class="font-semibold text-left fs-14 text-title line--limit-1"><?php echo e(translate('How to Set up Metadata for Pages?')); ?></span>
                            </button>
                        </div>
                        <div class="collapse mt-3" id="collapseGeneralSetup_033">
                            <div class="card rounded border p-3 card-body"> 
                                <div class="mb-3">
                                    <h6 class="mb-2 fs-12 color-656566"><?php echo e(translate('Before activation')); ?></h6>
                                    <ul class="mb-0 list-group pl-3 d-flex flex-column gap-1px">
                                        <li class="fs-12 color-656566"><?php echo e(translate('Add Page A Specific, Meaningful Text')); ?></li>                                            
                                        <li class="fs-12 color-656566"><?php echo e(translate('Avoid copying the same text across pages, and Use keywords naturally.')); ?></li>                                            
                                    </ul>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2 fs-12 color-656566"><?php echo e(translate('Upload Meta Image')); ?></h6>
                                    <ul class="mb-0 list-group pl-3 d-flex flex-column gap-1px">
                                        <li class="fs-12 color-656566"><?php echo e(translate('Used for social sharing previews')); ?></li>                                            
                                        <li class="fs-12 color-656566"><?php echo e(translate('Maintain the Recommended Ratio & Size')); ?></li>                                            
                                    </ul>
                                </div>
                                <div class="mb-3">
                                    <h6 class="mb-2 fs-12 color-656566"><?php echo e(translate('Select the necessary options as per instructions')); ?></h6>
                                    <ul class="mb-0 list-group pl-3 d-flex flex-column gap-1px">
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('Index:')); ?></strong> <?php echo e(translate('Allow search engines to show this page')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('No Index:')); ?></strong> <?php echo e(translate('Hide page from search results')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('No Follow:')); ?></strong> <?php echo e(translate('Prevents search engines from following links on this page')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('No Image Index:')); ?></strong> <?php echo e(translate('Prevents images from appearing in Google Image search. Use for private/system pages')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('Max Snippet:')); ?></strong> <?php echo e(translate('Controls text shown in Google results')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('Max Video Preview:')); ?></strong> <?php echo e(translate('Video preview length')); ?></li>                                            
                                        <li class="fs-12 color-656566"><strong class="text-dark"><?php echo e(translate('Max Image Preview:')); ?></strong> <?php echo e(translate('Small / Larges')); ?></li>                                            
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>
<!-- global guideline view Offcanvas end -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        $('#datatableSearch_').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            var count = 0;
            $('tbody tr').each(function () {
                var row = $(this);
                if (row.hasClass('empty-data-row')) return;

                var text = row.find('td:eq(1)').text().toLowerCase();
                if (text.indexOf(value) > -1) {
                    row.show();
                    count++;
                } else {
                    row.hide();
                }
            });
            $('#itemCount').text(count);

            if (count === 0) {
                $('.empty-data-row').show();
            } else {
                $('.empty-data-row').hide();
            }
        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\seo-settings\page-meta-data.blade.php ENDPATH**/ ?>