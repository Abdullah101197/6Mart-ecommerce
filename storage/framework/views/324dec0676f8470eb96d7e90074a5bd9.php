

<?php $__env->startSection('title',translate('messages.SEO Setup')); ?>

<?php $__env->startSection('content'); ?>
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-header-title text-break">
            <span class="page-header-icon">
                <img src="<?php echo e(asset('assets/admin/img/seo-setting.png')); ?>" class="w--26" alt="">
            </span>
            <span>Manage Page SEO</span>
        </h1> 
    </div>
    <!-- End Page Header -->
 
    <div class="card">
        <div class="card-header flex-sm-nowrap flex-wrap pt-3 pb-3 gap-2">
            <div class="">
                <h4 class="fs-16 text-dark"><?php echo e(translate('messages.Meta Data Setup')); ?></h4>            
                <p class="fs-12 m-0"><?php echo e(translate('messages.Include Meta Information to improve search engine visibility and social media sharing')); ?></p>            
            </div>
            <a href="#0" class="theme-clr text-nowrap text-underline fs-14 font-weight-medium">
                Back to List
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-xxl-8 col-lg-7">
                    <div class="bg-light2 rounded p-sm-4 p-3 h-100">
                        <ul class="nav nav-tabs mb-20">
                            <li class="nav-item">
                                <a class="nav-link lang_link active" href="#" id="default-link"><?php echo e(translate('messages.default')); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link lang_link" href="#" id="">English(EN)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link lang_link" href="#" id="">Arabic(SA)</a>
                            </li>
                        </ul>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-group m-0">
                                <label for="" class>
                                    <?php echo e(translate('Meta Title (EN)')); ?> 
                                    <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('Content This Title appears in browser tabs, search results, and link previews. Use a short ,clear, and keyword-focused title(recommended: 80-100 characters)')); ?>"><i class="tio-info text-muted fs-14"></i></span>
                                </label>
                                <textarea type="text" rows="1" maxlength="100" placeholder="Let’s" class="form-control"></textarea>
                                <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/100</span>
                            </div>
                            <div class="form-group m-0">
                                <label for="" class>
                                    <?php echo e(translate('Meta Description (EN)')); ?> 
                                    <span data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('A brief summary that appears under your page title in search results. Keep it compelling and relevant (recommended: 120-160 characters)')); ?>"><i class="tio-info text-muted fs-14"></i></span>
                                </label>
                                <textarea type="text" rows="1" maxlength="200" placeholder="Manage your business Smartly" class="form-control"></textarea>
                                <span class="text-right text-counting color-A7A7A7 d-block mt-1">0/200</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-5">
                    <div class="bg-light2 d-center rounded p-4 h-100">
                        <div class="">
                            <div class="mb-30 text-center">
                                <h4 class="mb-1"><?php echo e(translate('Meta Image')); ?> </h4>
                                <p class="mb-0 fs-12 gray-dark">
                                    <?php echo e(translate('Upload a rectangular image ')); ?>

                                </p>
                            </div>
                            <div class="mx-auto text-center">
                                <div class="upload-file_custom ratio-2-1 h-100px">
                                    <input class="upload-file__input single_file_input" type="file" id="" name="" accept="">
                                    <label for="" class="upload-file__wrapper w-100 h-100 m-0">
                                        <div class="upload-file-textbox text-center">
                                            <img width="22" class="svg" src="<?php echo e(asset('assets/admin/img/document-upload.svg')); ?>" alt="img">
                                            <h6 class="mt-1 fw-medium fs-10 lh-base text-center">
                                                <span class="theme-clr"><?php echo e(translate('Add')); ?></span>
                                            </h6>
                                        </div>
                                        <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="" style="display: none;">
                                    </label>
                                    <div class="overlay">
                                        <div class="d-flex gap-1 justify-content-center align-items-center h-100">
                                            <button type="button" class="btn btn-outline-info icon-btn view_btn">
                                                <i class="tio-invisible"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-info icon-btn edit_btn">
                                                <i class="tio-edit"></i>
                                            </button>
                                            <button type="button" class="remove_btn btn icon-btn">
                                                <i class="tio-delete text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-20 fs-12 mb-0 gray-dark">
                                    JPG, JPEG, PNG size : Max 2 MB <strong class="text-dark">(2:1)</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-light2 rounded p-sm-4 p-3 h-100">
                        <div class="resturant-type-group gap-2 py-3 px-3 bg-white rounded mb-20">
                            <label class="form-check flex-grow-1 form--check">
                                <input class="form-check-input" type="radio" value="1" name="index_status" checked>
                                <span class="form-check-label">Index</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('Allow search engines to put this web page on their list or index & show it on search results')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                            <label class="form-check flex-grow-1 form--check">
                                <input class="form-check-input" type="radio" value="0" name="index_status">
                                <span class="form-check-label">No Index</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right" data-original-title="<?php echo e(translate('Disallow search engines from putting this web page on their list or index, and do not show it on search results')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                        </div>
                        <div class="bg-white rounded follow-type-group py-3 px-3">
                            <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                <input type="checkbox" value="1" name="follow" checked>
                                <span class="text-nowrap label-text">No Follow</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('Instruct search engines not to follow links from this webpage.')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                            <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                <input type="checkbox" value="1" name="follow" checked>
                                <span class="text-nowrap label-text">No Index</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('Disallow search engines from putting this web page on their list or index, and do not show it on search results')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                            <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                <input type="checkbox" value="1" name="follow" checked>
                                <span class="text-nowrap label-text">No Image Index</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate(' Prevent images from being listed or indexed by search engines')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                            <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                <input type="checkbox" value="1" name="follow" checked>
                                <span class="text-nowrap label-text">No Snippet</span>
                                <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                    data-original-title="<?php echo e(translate('Instruct search engines not to show a summary or snippet of this webpage s content in search results.')); ?>">
                                    <i class="tio-info text-muted fs-14"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-light2 rounded p-sm-4 p-3 h-100">
                        <div class="bg-white rounded py-3 px-3">
                            <div class="row g-1 align-items-center mb-3">
                                <div class="col-sm-6">
                                    <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                        <input type="checkbox" value="1" name="follow" checked>
                                        <span class="label-text">Max Snippet</span>
                                        <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('Determine the maximum length of a snippet or preview text of the webpage.')); ?>">
                                            <i class="tio-info text-muted fs-14"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <input type="text" placeholder="0" class="form-control min-h-35px h--35px">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1 align-items-center mb-3">
                                <div class="col-sm-6">
                                    <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                        <input type="checkbox" value="1" name="follow" checked>
                                        <span class="label-text">Max Video Preview</span>
                                        <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('Determine the maximum duration of a video preview that search engines will display')); ?>">
                                            <i class="tio-info text-muted fs-14"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <input type="text" placeholder="10" class="form-control min-h-35px h--35px">
                                    </div>
                                </div>
                            </div>
                            <div class="row g-1 align-items-center">
                                <div class="col-sm-6">
                                    <label class="custom_checkbox d-flex align-items-center gap-1 flex-grow-1 m-0">
                                        <input type="checkbox" value="1" name="follow" checked>
                                        <span class="label-text">Max Image Preview</span>
                                        <span class="ms-4px" data-toggle="tooltip" data-placement="right"
                                            data-original-title="<?php echo e(translate('Determine the maximum size or dimensions of an image preview that search engines will display')); ?>">
                                            <i class="tio-info text-muted fs-14"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group m-0">
                                        <select name="sizeSelect" class="min-h-35px h--35px custom-select py-1" id="">
                                            <option value="">Large</option>
                                            <option value="">Medium</option>
                                            <option value="">Small</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           <div class="btn--container justify-content-end mt-4">
                <button type="reset" id="reset_btn" class="btn btn--reset min-w-120px">Reset</button>
                <button type="submit" class="btn btn--primary min-w-120px">Save</button>
            </div>
        </div>
    </div>             

</div>
<div class="tour-guide-items offcanvas-trigger text-capitalize fs-14 text-title cursor-pointer" data-target="#global_guideline_offcanvas"><?php echo e(translate('Guideline')); ?></div>

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
                            <!-- <a href="javascript:void(0)" class="fs-12 text-nowrap theme-clr text-underline">
                                <?php echo e(translate('Let’s Setup')); ?>

                            </a> -->
                        </div>
                        <div class="collapse mt-3 show" id="collapseGeneralSetup_01">
                            <div class="card rounded border p-3 card-body">
                                <div class="mb-3">
                                    <p class="m-0 fs-12 color-656566">
                                        <strong>Meta Data Setup</strong> allows you to define how each page of your e-commerce site appears in:
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


<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\test\seo-setting\seo-content-page.blade.php ENDPATH**/ ?>