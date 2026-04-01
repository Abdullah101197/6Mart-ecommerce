

<?php $__env->startSection('title', translate('messages.priority_settings')); ?>

<?php $__env->startPush('css_or_js'); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<?php use App\CentralLogics\Helpers;?>
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title mr-3">
                <span class="page-header-icon">
                    <img src="<?php echo e(asset('assets/admin/img/business.png')); ?>" class="w--26" alt="">
                </span>
                <span>
                    <?php echo e(translate('business_setup')); ?>

                </span>
            </h1>

            <?php echo $__env->make('admin-views.business-settings.partials.nav-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </div>

        <!-- Main Content -->
        <form method="post" action="<?php echo e(route('admin.business-settings.update-priority')); ?>">
            <div class="fs-12 px-3 py-2 bg-opacity-10 rounded bg-info mb-20">
                <div class="d-flex align-items-center gap-2 mb-0">
                    <span class="text-info fs-16">
                        <i class="tio-light-on"></i>
                    </span>
                    <span class="color-656565">
                        <?php echo e(translate('After change any setup in this page must click the ')); ?> <strong><?php echo e(translate('Save Information')); ?></strong> <?php echo e(translate('button, otherwise changes are not work.')); ?>

                    </span>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    
                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Select System')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Select the systems you want to temporarily deactivate for maintenance')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($category_list_default_status =  Helpers::get_business_settings('category_list_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">
        
                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by priority')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">
        
                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="category_list_default_status" value="1"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($category_list_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">
        
                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">
        
                                                <div class="fs-14 text-dark">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="category_list_default_status" value="0"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($category_list_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($category_list_sort_by_general = \App\Models\PriorityList::where('name', 'category_list_sort_by_general')->where('type', 'general')->first()?->value ?? ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_list_sort_by_general" value="latest"
                                                        <?php echo e($category_list_sort_by_general == 'latest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by latest created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_list_sort_by_general" value="oldest"
                                                        <?php echo e($category_list_sort_by_general == 'oldest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by first created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_list_sort_by_general" value="order_count"
                                                        <?php echo e($category_list_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_list_sort_by_general" value="a_to_z"
                                                        <?php echo e($category_list_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_list_sort_by_general" value="z_to_a"
                                                        <?php echo e($category_list_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <?php echo csrf_field(); ?>
                <div class="card-body">
                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Best Stores Nearby')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Best Stores Nearby is the list of customer choices in which customer ordered items most and also highly rated with good reviews')); ?>

                                </p>
                            </div>
                        </div>

                        <?php ($popular_store_default_status =  Helpers::get_business_settings('popular_store_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_distance_which_is_the_most_nearby_user_and_total_orders.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="popular_store_default_status" value="1"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($popular_store_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="popular_store_default_status" value="0"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($popular_store_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($popular_store_sort_by_rating = \App\Models\PriorityList::where('name', 'popular_store_sort_by_rating')->where('type', 'rating')->first()); ?>
                                            <?php ($popular_store_sort_by_rating = $popular_store_sort_by_rating ? $popular_store_sort_by_rating->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_rating" value="four_plus"
                                                        <?php echo e($popular_store_sort_by_rating == 'four_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 4+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_rating" value="three_half_plus"
                                                        <?php echo e($popular_store_sort_by_rating == 'three_half_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 3.5+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_rating" value="three_plus"
                                                        <?php echo e($popular_store_sort_by_rating == 'three_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 3+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_rating" value="two_plus"
                                                        <?php echo e($popular_store_sort_by_rating == 'two_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 2+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_rating" value="none"
                                                        <?php echo e($popular_store_sort_by_rating == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($popular_store_sort_by_general = \App\Models\PriorityList::where('name', 'popular_store_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($popular_store_sort_by_general = $popular_store_sort_by_general ? $popular_store_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="popular_store_sort_by_general" value="nearest_first"
                                                        <?php echo e($popular_store_sort_by_general == 'nearest_first' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Distance from customer location')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_general" value="order_count"
                                                        <?php echo e($popular_store_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_general" value="review_count"
                                                        <?php echo e($popular_store_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_general" value="rating"
                                                        <?php echo e($popular_store_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($popular_store_sort_by_unavailable = \App\Models\PriorityList::where('name', 'popular_store_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($popular_store_sort_by_unavailable = $popular_store_sort_by_unavailable ? $popular_store_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_unavailable" value="last"
                                                        <?php echo e($popular_store_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show currently closed stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_unavailable" value="remove"
                                                        <?php echo e($popular_store_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove currently closed stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_unavailable" value="none"
                                                        <?php echo e($popular_store_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($popular_store_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'popular_store_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($popular_store_sort_by_temp_closed = $popular_store_sort_by_temp_closed ? $popular_store_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_temp_closed" value="last"
                                                        <?php echo e($popular_store_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show temporarily off stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_temp_closed" value="remove"
                                                        <?php echo e($popular_store_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove temporarily off stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_store_sort_by_temp_closed" value="none"
                                                        <?php echo e($popular_store_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Recommended Store')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Recommended Stores is the list of Admin choices which is highly recommended by admin')); ?>

                                </p>
                            </div>
                        </div>

                        <?php ($recommended_store_default_status =  Helpers::get_business_settings('recommended_store_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_oldest_recommended_stores.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="recommended_store_default_status"
                                                    value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($recommended_store_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="recommended_store_default_status"
                                                    value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($recommended_store_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($recommended_store_sort_by_general = \App\Models\PriorityList::where('name', 'recommended_store_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($recommended_store_sort_by_general = $recommended_store_sort_by_general ? $recommended_store_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="recommended_store_sort_by_general" value="order_count"
                                                        <?php echo e($recommended_store_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="recommended_store_sort_by_general" value="review_count"
                                                        <?php echo e($recommended_store_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="recommended_store_sort_by_general" value="rating"
                                                        <?php echo e($recommended_store_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($recommended_store_sort_by_rating = \App\Models\PriorityList::where('name', 'recommended_store_sort_by_rating')->where('type', 'rating')->first()); ?>
                                            <?php ($recommended_store_sort_by_rating = $recommended_store_sort_by_rating ? $recommended_store_sort_by_rating->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_rating" value="four_plus"
                                                        <?php echo e($recommended_store_sort_by_rating == 'four_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 4+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_rating" value="three_half_plus"
                                                        <?php echo e($recommended_store_sort_by_rating == 'three_half_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 3.5+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_rating" value="three_plus"
                                                        <?php echo e($recommended_store_sort_by_rating == 'three_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 3+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_rating" value="two_plus"
                                                        <?php echo e($recommended_store_sort_by_rating == 'two_plus' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show 2+ rated sellers')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_rating" value="none"
                                                        <?php echo e($recommended_store_sort_by_rating == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($recommended_store_sort_by_unavailable = \App\Models\PriorityList::where('name', 'recommended_store_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($recommended_store_sort_by_unavailable = $recommended_store_sort_by_unavailable ? $recommended_store_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_unavailable" value="last"
                                                        <?php echo e($recommended_store_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show currently closed stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_unavailable" value="remove"
                                                        <?php echo e($recommended_store_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove currently closed stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_unavailable" value="none"
                                                        <?php echo e($recommended_store_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($recommended_store_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'recommended_store_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($recommended_store_sort_by_temp_closed = $recommended_store_sort_by_temp_closed ? $recommended_store_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_temp_closed" value="last"
                                                        <?php echo e($recommended_store_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show temporarily off stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_temp_closed" value="remove"
                                                        <?php echo e($recommended_store_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove temporarily off stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="recommended_store_sort_by_temp_closed" value="none"
                                                        <?php echo e($recommended_store_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Special Offers')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The special offers includes the list of discounted items offered for the customers')); ?>

                                </p>
                            </div>
                        </div>

                        <?php ($special_offer_default_status = Helpers::get_business_settings('special_offer_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by highest discount amount.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="special_offer_default_status" value="1"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($special_offer_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="special_offer_default_status" value="0"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($special_offer_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($special_offer_sort_by_general = \App\Models\PriorityList::where('name', 'special_offer_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($special_offer_sort_by_general = $special_offer_sort_by_general ? $special_offer_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_general" value="order_count"
                                                        <?php echo e($special_offer_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_general" value="review_count"
                                                        <?php echo e($special_offer_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_general" value="rating"
                                                        <?php echo e($special_offer_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_general" value="a_to_z"
                                                        <?php echo e($special_offer_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_general" value="z_to_a"
                                                        <?php echo e($special_offer_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($special_offer_sort_by_unavailable = \App\Models\PriorityList::where('name', 'special_offer_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($special_offer_sort_by_unavailable = $special_offer_sort_by_unavailable ? $special_offer_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_unavailable" value="last"
                                                        <?php echo e($special_offer_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_unavailable" value="remove"
                                                        <?php echo e($special_offer_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="special_offer_sort_by_unavailable" value="none"
                                                        <?php echo e($special_offer_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Most Popular Item')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Popular item Nearby means the item items list  which are mostly ordered by the customers and have good reviews & ratings')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($popular_item_default_status = Helpers::get_business_settings('popular_item_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_higher_ordered_items.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status.')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="popular_item_default_status"
                                                    value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($popular_item_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Set customized condition to show this list')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="popular_item_default_status"
                                                    value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($popular_item_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($popular_item_sort_by_general = \App\Models\PriorityList::where('name', 'popular_item_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($popular_item_sort_by_general = $popular_item_sort_by_general ? $popular_item_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="popular_item_sort_by_general" value="latest_created"
                                                        <?php echo e($popular_item_sort_by_general == 'latest_created' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by latest created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="popular_item_sort_by_general" value="first_created"
                                                        <?php echo e($popular_item_sort_by_general == 'first_created' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by first created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_general" value="order_count"
                                                        <?php echo e($popular_item_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_general" value="review_count"
                                                        <?php echo e($popular_item_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_general" value="rating"
                                                        <?php echo e($popular_item_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_general" value="a_to_z"
                                                        <?php echo e($popular_item_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_general" value="z_to_a"
                                                        <?php echo e($popular_item_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($popular_item_sort_by_unavailable = \App\Models\PriorityList::where('name', 'popular_item_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($popular_item_sort_by_unavailable = $popular_item_sort_by_unavailable ? $popular_item_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_unavailable" value="last"
                                                        <?php echo e($popular_item_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_unavailable" value="remove"
                                                        <?php echo e($popular_item_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_unavailable" value="none"
                                                        <?php echo e($popular_item_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($popular_item_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'popular_item_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($popular_item_sort_by_temp_closed = $popular_item_sort_by_temp_closed ? $popular_item_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_temp_closed" value="last"
                                                        <?php echo e($popular_item_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_temp_closed" value="remove"
                                                        <?php echo e($popular_item_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="popular_item_sort_by_temp_closed" value="none"
                                                        <?php echo e($popular_item_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Best Reviewed Item')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Best Reviewed items are the top most ordered item list of customer choice which are highly rated & reviewed ')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($best_reviewed_item_default_status =  Helpers::get_business_settings('best_reviewed_item_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by top ratings')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="best_reviewed_item_default_status"
                                                       value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($best_reviewed_item_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="best_reviewed_item_default_status"
                                                       value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($best_reviewed_item_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($best_reviewed_item_sort_by_general = \App\Models\PriorityList::where('name', 'best_reviewed_item_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($best_reviewed_item_sort_by_general = $best_reviewed_item_sort_by_general ? $best_reviewed_item_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_general" value="order_count"
                                                        <?php echo e($best_reviewed_item_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by orders count')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_general" value="review_count"
                                                        <?php echo e($best_reviewed_item_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by reviews count')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_general" value="rating"
                                                        <?php echo e($best_reviewed_item_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by ratings')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                            <?php ($best_reviewed_item_sort_by_unavailable = \App\Models\PriorityList::where('name', 'best_reviewed_item_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($best_reviewed_item_sort_by_unavailable = $best_reviewed_item_sort_by_unavailable ? $best_reviewed_item_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_unavailable" value="last"
                                                        <?php echo e($best_reviewed_item_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show stockout products in the last')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_unavailable" value="remove"
                                                        <?php echo e($best_reviewed_item_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove stockout products from the list')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_unavailable" value="none"
                                                        <?php echo e($best_reviewed_item_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                            <?php ($best_reviewed_item_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'best_reviewed_item_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($best_reviewed_item_sort_by_temp_closed = $best_reviewed_item_sort_by_temp_closed ? $best_reviewed_item_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_temp_closed" value="last"
                                                        <?php echo e($best_reviewed_item_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_temp_closed" value="remove"
                                                        <?php echo e($best_reviewed_item_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="best_reviewed_item_sort_by_temp_closed" value="none"
                                                        <?php echo e($best_reviewed_item_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                   

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Just for You')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Just for You is the Item campaign includes the list of discounted items offered for the customers.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($item_campaign_default_status = Helpers::get_business_settings('item_campaign_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by latest')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="item_campaign_default_status" value="1"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($item_campaign_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px>
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="item_campaign_default_status" value="0"
                                                    class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($item_campaign_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($item_campaign_sort_by_general = \App\Models\PriorityList::where('name', 'item_campaign_sort_by_general')->where('type', 'general')->first()?->value ?? ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="item_campaign_sort_by_general" value="order_count"
                                                        <?php echo e($item_campaign_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="item_campaign_sort_by_general" value="end_first"
                                                        <?php echo e($item_campaign_sort_by_general == 'end_first' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by End Date of campaign')); ?>

                                                    </span>
                                                </label>

                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="item_campaign_sort_by_general" value="a_to_z"
                                                        <?php echo e($item_campaign_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="item_campaign_sort_by_general" value="z_to_a"
                                                        <?php echo e($item_campaign_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>





                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('New_0n')); ?> <?php echo e(Helpers::get_business_settings('business_name')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The New store list arranges stores based on the latest join that are closest to the customers location.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($latest_stores_default_status = Helpers::get_business_settings('latest_stores_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by latest')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="latest_stores_default_status"
                                                        value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($latest_stores_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                            <h5 class="fs-14 font-semibold mb-1">
                                                <?php echo e(translate('Use custom sorting list')); ?></h5>
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Set customized condition to show this list')); ?>

                                                </div>
                                            </label>
                                        </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="latest_stores_default_status"
                                                        value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($latest_stores_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($latest_stores_sort_by_general = \App\Models\PriorityList::where('name', 'latest_stores_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($latest_stores_sort_by_general = $latest_stores_sort_by_general ? $latest_stores_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_general" value="latest_created"
                                                        <?php echo e($latest_stores_sort_by_general == 'latest_created' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by latest created')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_general" value="review_count"
                                                        <?php echo e($latest_stores_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by reviews count')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_general" value="rating"
                                                        <?php echo e($latest_stores_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by ratings')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="latest_stores_sort_by_general" value="a_to_z"
                                                        <?php echo e($latest_stores_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="latest_stores_sort_by_general" value="z_to_a"
                                                        <?php echo e($latest_stores_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($latest_stores_sort_by_unavailable = \App\Models\PriorityList::where('name', 'latest_stores_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($latest_stores_sort_by_unavailable = $latest_stores_sort_by_unavailable ? $latest_stores_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_unavailable" value="last"
                                                        <?php echo e($latest_stores_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show currently closed stores in the last')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_unavailable" value="remove"
                                                        <?php echo e($latest_stores_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove currently closed stores from the list')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_unavailable" value="none"
                                                        <?php echo e($latest_stores_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                            <?php ($latest_stores_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'latest_stores_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($latest_stores_sort_by_temp_closed = $latest_stores_sort_by_temp_closed ? $latest_stores_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_temp_closed" value="last"
                                                        <?php echo e($latest_stores_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show temporarily off stores in the last')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_temp_closed" value="remove"
                                                        <?php echo e($latest_stores_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove temporarily off stores from the list')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="latest_stores_sort_by_temp_closed" value="none"
                                                        <?php echo e($latest_stores_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('All Stores')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The all store list arranges all stores based on the latest join that are closest to the customers location.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($all_stores_default_status = Helpers::get_business_settings('all_stores_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_active_stores.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="all_stores_default_status"
                                                    value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($all_stores_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="all_stores_default_status"
                                                    value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($all_stores_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">

                                            <?php ($all_stores_sort_by_general = \App\Models\PriorityList::where('name', 'all_stores_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($all_stores_sort_by_general = $all_stores_sort_by_general ? $all_stores_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="latest_created"
                                                        <?php echo e($all_stores_sort_by_general == 'latest_created' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by latest created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="first_created"
                                                        <?php echo e($all_stores_sort_by_general == 'first_created' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by first created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="order_count"
                                                        <?php echo e($all_stores_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="review_count"
                                                        <?php echo e($all_stores_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="rating"
                                                        <?php echo e($all_stores_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="a_to_z"
                                                        <?php echo e($all_stores_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_general" value="z_to_a"
                                                        <?php echo e($all_stores_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($all_stores_sort_by_unavailable = \App\Models\PriorityList::where('name', 'all_stores_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($all_stores_sort_by_unavailable = $all_stores_sort_by_unavailable ? $all_stores_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_unavailable" value="last"
                                                        <?php echo e($all_stores_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show currently closed stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_unavailable" value="remove"
                                                        <?php echo e($all_stores_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove currently closed stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_unavailable" value="none"
                                                        <?php echo e($all_stores_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($all_stores_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'all_stores_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($all_stores_sort_by_temp_closed = $all_stores_sort_by_temp_closed ? $all_stores_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_temp_closed" value="last"
                                                        <?php echo e($all_stores_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show temporarily off stores in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_temp_closed" value="remove"
                                                        <?php echo e($all_stores_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove temporarily off stores from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="all_stores_sort_by_temp_closed" value="none"
                                                        <?php echo e($all_stores_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Category / Subcategory wise product list')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Category / Subcategory Wise Items means the latest items list under a specific category')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($category_sub_category_item_default_status = Helpers::get_business_settings('category_sub_category_item_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_latest_created_items.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="category_sub_category_item_default_status"
                                                    value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($category_sub_category_item_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="category_sub_category_item_default_status"
                                                    value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($category_sub_category_item_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">

                                            <?php ($category_sub_category_item_sort_by_general = \App\Models\PriorityList::where('name', 'category_sub_category_item_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($category_sub_category_item_sort_by_general = $category_sub_category_item_sort_by_general ? $category_sub_category_item_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">

                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_general" value="order_count"
                                                        <?php echo e($category_sub_category_item_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_general" value="review_count"
                                                        <?php echo e($category_sub_category_item_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_general" value="rating"
                                                        <?php echo e($category_sub_category_item_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_general" value="a_to_z"
                                                        <?php echo e($category_sub_category_item_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_general" value="z_to_a"
                                                        <?php echo e($category_sub_category_item_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($category_sub_category_item_sort_by_unavailable = \App\Models\PriorityList::where('name', 'category_sub_category_item_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($category_sub_category_item_sort_by_unavailable = $category_sub_category_item_sort_by_unavailable ? $category_sub_category_item_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_unavailable" value="last"
                                                        <?php echo e($category_sub_category_item_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_unavailable" value="remove"
                                                        <?php echo e($category_sub_category_item_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_unavailable" value="none"
                                                        <?php echo e($category_sub_category_item_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($category_sub_category_item_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'category_sub_category_item_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($category_sub_category_item_sort_by_temp_closed = $category_sub_category_item_sort_by_temp_closed ? $category_sub_category_item_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_temp_closed" value="last"
                                                        <?php echo e($category_sub_category_item_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_temp_closed" value="remove"
                                                        <?php echo e($category_sub_category_item_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="category_sub_category_item_sort_by_temp_closed" value="none"
                                                        <?php echo e($category_sub_category_item_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('product search list')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('product search list (Search Bar) means the item list from top search bar')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($product_search_default_status = Helpers::get_business_settings('product_search_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_active_items.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="product_search_default_status"
                                                    value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($product_search_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="product_search_default_status"
                                                    value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($product_search_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">

                                            <?php ($product_search_sort_by_general = \App\Models\PriorityList::where('name', 'product_search_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($product_search_sort_by_general = $product_search_sort_by_general ? $product_search_sort_by_general->value : ''); ?>
                                            <input hidden class="form-check-input" type="radio"
                                            name="product_search_sort_by_general" value="order_count" checked>

                                            <?php ($product_search_sort_by_unavailable = \App\Models\PriorityList::where('name', 'product_search_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($product_search_sort_by_unavailable = $product_search_sort_by_unavailable ? $product_search_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_unavailable" value="last"
                                                        <?php echo e($product_search_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_unavailable" value="remove"
                                                        <?php echo e($product_search_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_unavailable" value="none"
                                                        <?php echo e($product_search_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($product_search_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'product_search_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($product_search_sort_by_temp_closed = $product_search_sort_by_temp_closed ? $product_search_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_temp_closed" value="last"
                                                        <?php echo e($product_search_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_temp_closed" value="remove"
                                                        <?php echo e($product_search_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="product_search_sort_by_temp_closed" value="none"
                                                        <?php echo e($product_search_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Basic Medicine Nearby')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Basic Medicine Nearby is item list of the stores based on the latest join that are closest to the customers location.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($basic_medicine_default_status = Helpers::get_business_settings('basic_medicine_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_total_orders.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="basic_medicine_default_status"
                                                       value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($basic_medicine_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="basic_medicine_default_status"
                                                       value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($basic_medicine_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">

                                            <?php ($basic_medicine_sort_by_general = \App\Models\PriorityList::where('name', 'basic_medicine_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($basic_medicine_sort_by_general = $basic_medicine_sort_by_general ? $basic_medicine_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">

                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_general" value="order_count"
                                                        <?php echo e($basic_medicine_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_general" value="review_count"
                                                        <?php echo e($basic_medicine_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_general" value="rating"
                                                        <?php echo e($basic_medicine_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_general" value="a_to_z"
                                                        <?php echo e($basic_medicine_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_general" value="z_to_a"
                                                        <?php echo e($basic_medicine_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($basic_medicine_sort_by_unavailable = \App\Models\PriorityList::where('name', 'basic_medicine_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($basic_medicine_sort_by_unavailable = $basic_medicine_sort_by_unavailable ? $basic_medicine_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_unavailable" value="last"
                                                        <?php echo e($basic_medicine_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_unavailable" value="remove"
                                                        <?php echo e($basic_medicine_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_unavailable" value="none"
                                                        <?php echo e($basic_medicine_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($basic_medicine_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'basic_medicine_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($basic_medicine_sort_by_temp_closed = $basic_medicine_sort_by_temp_closed ? $basic_medicine_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_temp_closed" value="last"
                                                        <?php echo e($basic_medicine_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_temp_closed" value="remove"
                                                        <?php echo e($basic_medicine_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="basic_medicine_sort_by_temp_closed" value="none"
                                                        <?php echo e($basic_medicine_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Common Condition')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('Common Condition is the list of items which are mostly commonly used by the users.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($common_condition_default_status = Helpers::get_business_settings('common_condition_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by active conditions')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="common_condition_default_status" value="1"
                                                       class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($common_condition_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="common_condition_default_status" value="0"
                                                       class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($common_condition_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($common_condition_sort_by_general = \App\Models\PriorityList::where('name', 'common_condition_sort_by_general')->where('type', 'general')->first()?->value ?? ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="common_condition_sort_by_general" value="latest"
                                                        <?php echo e($common_condition_sort_by_general == 'latest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by latest created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="common_condition_sort_by_general" value="oldest"
                                                        <?php echo e($common_condition_sort_by_general == 'oldest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by first created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="common_condition_sort_by_general" value="order_count"
                                                        <?php echo e($common_condition_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="common_condition_sort_by_general" value="a_to_z"
                                                        <?php echo e($common_condition_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="common_condition_sort_by_general" value="z_to_a"
                                                        <?php echo e($common_condition_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Brand')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The list of well known brands.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($brand_default_status = Helpers::get_business_settings('brand_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Currently sorting this section by active brands')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Staus')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="brand_default_status" value="1"
                                                       class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($brand_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="brand_default_status" value="0"
                                                       class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($brand_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($brand_sort_by_general = \App\Models\PriorityList::where('name', 'brand_sort_by_general')->where('type', 'general')->first()?->value ?? ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_sort_by_general" value="latest"
                                                        <?php echo e($brand_sort_by_general == 'latest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by latest created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_sort_by_general" value="oldest"
                                                        <?php echo e($brand_sort_by_general == 'oldest' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by first created')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_sort_by_general" value="order_count"
                                                        <?php echo e($brand_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_sort_by_general" value="a_to_z"
                                                        <?php echo e($brand_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_sort_by_general" value="z_to_a"
                                                        <?php echo e($brand_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Brand wise product list')); ?></h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The Brand wise product list groups similar items together arranged with the latest brand first.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($brand_item_default_status = Helpers::get_business_settings('brand_item_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use default sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This_section_is_currently_sorted_by_latest_created_items.')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="brand_item_default_status"
                                                       value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($brand_item_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1"><?php echo e(translate('Use custom sorting list')); ?>

                                        </h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?></div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?></div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="brand_item_default_status"
                                                       value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($brand_item_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">

                                            <?php ($brand_item_sort_by_general = \App\Models\PriorityList::where('name', 'brand_item_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($brand_item_sort_by_general = $brand_item_sort_by_general ? $brand_item_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">

                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_general" value="order_count"
                                                        <?php echo e($brand_item_sort_by_general == 'order_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by orders')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_general" value="review_count"
                                                        <?php echo e($brand_item_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by reviews count')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_general" value="rating"
                                                        <?php echo e($brand_item_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by ratings')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_general" value="a_to_z"
                                                        <?php echo e($brand_item_sort_by_general == 'a_to_z' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (A to Z)')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_general" value="z_to_a"
                                                        <?php echo e($brand_item_sort_by_general == 'z_to_a' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Sort by Alphabetical (Z to A)')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($brand_item_sort_by_unavailable = \App\Models\PriorityList::where('name', 'brand_item_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($brand_item_sort_by_unavailable = $brand_item_sort_by_unavailable ? $brand_item_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_unavailable" value="last"
                                                        <?php echo e($brand_item_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show stockout products in the last')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_unavailable" value="remove"
                                                        <?php echo e($brand_item_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove stockout products from the list')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_unavailable" value="none"
                                                        <?php echo e($brand_item_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($brand_item_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'brand_item_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($brand_item_sort_by_temp_closed = $brand_item_sort_by_temp_closed ? $brand_item_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_temp_closed" value="last"
                                                        <?php echo e($brand_item_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Show product in the last if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_temp_closed" value="remove"
                                                        <?php echo e($brand_item_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Remove product from the list if store is temporarily off')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                           name="brand_item_sort_by_temp_closed" value="none"
                                                        <?php echo e($brand_item_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('None')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    

                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-4">
                            <div class="max-w-353px">
                                <h4 class="mb-2 mt-4"><?php echo e(translate('Top_offer_near_me')); ?> </h4>
                                <p class="m-0 fs-12">
                                    <?php echo e(translate('The store list arranges stores based on the dicount and closest to the customers location.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php ($top_offer_near_me_stores_default_status = Helpers::get_business_settings('top_offer_near_me_stores_default_status')  ?? 1); ?>
                        <div class="col-lg-6 col-xl-8">
                            <div class=" rounded d-flex flex-column gap-20px">
                                <!-- Default Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use default sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('This section sorted based on the dicount and closest to the customers location')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="top_offer_near_me_stores_default_status"
                                                        value="1" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($top_offer_near_me_stores_default_status == '1' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Custom Collapsible Card -->
                                <div class="sorting-card bg-light rounded p-3">
                                    <div class="mb-10px">
                                        <h5 class="fs-14 font-semibold mb-1">
                                            <?php echo e(translate('Use custom sorting list')); ?></h5>
                                        <label class="form-label d-flex align-items-center m-0">

                                            <div class="fs-13">
                                                <?php echo e(translate('Set customized condition to show this list')); ?>

                                            </div>
                                        </label>
                                    </div>
                                    <div class="d-flex bg-white border rounded pt-2 px-3 justify-content-between align-items-centerr">
                                        <div class="w-0 flex-grow">
                                            <label class="form-label d-flex align-items-center m-0">

                                                <div class="fs-13">
                                                    <?php echo e(translate('Status')); ?>

                                                </div>
                                            </label>
                                        </div>
                                        <div>
                                            <label
                                                class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                                                <input type="radio" name="top_offer_near_me_stores_default_status"
                                                        value="0" class="toggle-switch-input collapse-div-toggler"
                                                    <?php echo e($top_offer_near_me_stores_default_status == '0' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="inner-collapse-div">
                                        <div class="pt-4">
                                            <?php ($top_offer_near_me_stores_sort_by_general = \App\Models\PriorityList::where('name', 'top_offer_near_me_stores_sort_by_general')->where('type', 'general')->first()); ?>
                                            <?php ($top_offer_near_me_stores_sort_by_general = $top_offer_near_me_stores_sort_by_general ? $top_offer_near_me_stores_sort_by_general->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_general" value="review_count"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_general == 'review_count' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by reviews count')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_general" value="rating"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_general == 'rating' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Sort by ratings')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="top_offer_near_me_stores_sort_by_general" value="asc_discount"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_general == 'asc_discount' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Based on the Discount amount - Ascending')); ?>

                                                    </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                        name="top_offer_near_me_stores_sort_by_general" value="desc_discount"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_general == 'desc_discount' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                        <?php echo e(translate('Based on the Discount amount - Descending')); ?>

                                                    </span>
                                                </label>
                                            </div>
                                            <?php ($top_offer_near_me_stores_sort_by_unavailable = \App\Models\PriorityList::where('name', 'top_offer_near_me_stores_sort_by_unavailable')->where('type', 'unavailable')->first()); ?>
                                            <?php ($top_offer_near_me_stores_sort_by_unavailable = $top_offer_near_me_stores_sort_by_unavailable ? $top_offer_near_me_stores_sort_by_unavailable->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_unavailable" value="last"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_unavailable == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show currently closed stores in the last')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_unavailable" value="remove"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_unavailable == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove currently closed stores from the list')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_unavailable" value="none"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_unavailable == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                            <?php ($top_offer_near_me_stores_sort_by_temp_closed = \App\Models\PriorityList::where('name', 'top_offer_near_me_stores_sort_by_temp_closed')->where('type', 'temp_closed')->first()); ?>
                                            <?php ($top_offer_near_me_stores_sort_by_temp_closed = $top_offer_near_me_stores_sort_by_temp_closed ? $top_offer_near_me_stores_sort_by_temp_closed->value : ''); ?>
                                            <div class=" rounded p-3 d-flex flex-column gap-3 fs-14 mb-15 bg-white ">
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_temp_closed" value="last"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_temp_closed == 'last' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Show temporarily off stores in the last')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_temp_closed" value="remove"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_temp_closed == 'remove' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('Remove temporarily off stores from the list')); ?>

                                                        </span>
                                                </label>
                                                <label class="form-check form--check">
                                                    <input class="form-check-input" type="radio"
                                                            name="top_offer_near_me_stores_sort_by_temp_closed" value="none"
                                                        <?php echo e($top_offer_near_me_stores_sort_by_temp_closed == 'none' ? 'checked' : ''); ?>>
                                                    <span class="form-check-label">
                                                            <?php echo e(translate('None')); ?>

                                                        </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $__env->make('admin-views.partials._floating-submit-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        </form>
    </div>
    <div id="global_guideline_offcanvas" style="overflow-y: auto;"
    class="custom-offcanvas d-flex flex-column justify-content-between global_guideline_offcanvas">
    <div>
        <div class="custom-offcanvas-header bg--secondary d-flex justify-content-between align-items-center px-3 py-3">
            <h3 class="mb-0"><?php echo e(translate('Priority Settings Guideline')); ?></h3>
            <button type="button"
                class="btn-close w-25px h-25px border rounded-circle d-center bg--secondary offcanvas-close fz-15px p-0"
                aria-label="Close">&times;</button>
        </div>

        <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
            <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                    type="button" data-toggle="collapse" data-target="#priority_setup_guide" aria-expanded="true">
                    <div
                        class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                        <i class="tio-down-ui"></i>
                    </div>
                    <span class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Priority Setup')); ?></span>
                </button>
                <a href="#priority_setup_form"
                    class="text-info text-underline fs-12 text-nowrap offcanvas-close offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
            </div>
            <div class="collapse mt-3 show" id="priority_setup_guide">
                <div class="card card-body">
                    <div class="">
                        <h5 class="mb-3"><?php echo e(translate('Priority Setup')); ?></h5>
                        <p class="fs-12 mb-0">
                            <?php echo e(translate('The Priority Setup feature allows the admin or vendor to control the display order of items, categories, and subcategories across the system. By setting priority rules, the platform can highlight specific items or organise listings in a way that improves visibility and user experience.')); ?>

                        </p>
                        <br>
                        <h5 class="mb-2"><?php echo e(translate('Priority Setup Benefit')); ?>:</h5>
                        <ul class="fs-12">
                            <li><?php echo e(translate('Promotes popular or high-performing items')); ?></li>
                            <li><?php echo e(translate('Improves menu navigation and discoverability')); ?></li>
                            <li><?php echo e(translate('Creates a consistent and organised listing order')); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-3 px-3 bg-light rounded mb-3 mb-sm-20">
            <div class="d-flex gap-2 align-items-center justify-content-between overflow-hidden">
                <button class="btn-collapse d-flex gap-2 align-items-center bg-transparent border-0 p-0 collapsed"
                    type="button" data-toggle="collapse" data-target="#sorting_options_guide" aria-expanded="true">
                    <div
                        class="btn-collapse-icon w-35px h-35px bg-white d-flex align-items-center justify-content-center border icon-btn rounded-circle fs-12 lh-1">
                        <i class="tio-down-ui"></i>
                    </div>
                    <span class="font-semibold text-left fs-14 text-title"><?php echo e(translate('Sorting Options')); ?></span>
                </button>
                <a href="#category_list_section"
                    class="text-info text-underline fs-12 text-nowrap offcanvas-close offcanvas-close-btn"><?php echo e(translate('messages.Let’s Setup')); ?></a>
            </div>
            <div class="collapse mt-3" id="sorting_options_guide">
                <div class="card card-body">
                    <div class="">
                        <h5 class="mb-3"><?php echo e(translate('Sorting Options')); ?></h5>
                        <ul class="fs-12">
                            <li class="mb-2">
                                <strong><?php echo e(translate('Use Default Sorting')); ?>:</strong>
                                <?php echo e(translate('Items/Categories are displayed based on the system\'s predefined order.')); ?>

                            </li>
                            <li class="mb-2">
                                <strong><?php echo e(translate('Use Custom Sorting')); ?>:</strong>
                                <?php echo e(translate('Items are arranged based on selected criteria, including:')); ?>

                                <ul class="mt-2">
                                    <li><strong><?php echo e(translate('Sort by latest created')); ?>:</strong>
                                        <?php echo e(translate('Shows the most recently added items or vendors first')); ?></li>
                                    <li><strong><?php echo e(translate('Sort by First created')); ?>:</strong>
                                        <?php echo e(translate('Displays the oldest items or vendor first')); ?></li>
                                    <li><strong><?php echo e(translate('Sort by Orders')); ?>:</strong>
                                        <?php echo e(translate('Displays items or vendors based on the total number of orders, highest first')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Sort by total vendors')); ?>:</strong>
                                        <?php echo e(translate('Sorts categories or locations based on the number of vendors associated')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Show the nearest item first')); ?>:</strong>
                                        <?php echo e(translate('Displays items from vendors closest to the customer first')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Show unavailable items in the last')); ?>:</strong>
                                        <?php echo e(translate('Place items and vendors that are currently unavailable at the end of the list.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Remove unavailable items from the list')); ?>:</strong>
                                        <?php echo e(translate('Completely hides items or vendors that are unavailable')); ?></li>
                                    <li><strong><?php echo e(translate('Show the currently closed vendors last')); ?>:</strong>
                                        <?php echo e(translate('Keeps closed vendors at the end of the list while still showing them.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Remove currently closed vendors from the list')); ?>:</strong>
                                        <?php echo e(translate('Hides all closed vendors entirely.')); ?></li>
                                    <li><strong><?php echo e(translate('Show temporarily off vendors in the last')); ?>:</strong>
                                        <?php echo e(translate('Temporarily inactive vendors appear at the end of the list')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Remove temporarily off vendors from the list')); ?>:</strong>
                                        <?php echo e(translate('Temporarily inactive vendors are hidden completely.')); ?></li>
                                    <li><strong><?php echo e(translate('Sort new vendors by distance')); ?>:</strong>
                                        <?php echo e(translate('Shows newly added vendors closest to the customer first')); ?></li>
                                    <li><strong><?php echo e(translate('Sort new vendors by delivery time')); ?>:</strong>
                                        <?php echo e(translate('Order new vendors based on estimated delivery time, fastest first.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Show end date near Items First')); ?>:</strong>
                                        <?php echo e(translate('Displays items that are about to expire or whose availability end date is near at the top.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Alphabetical order (A–Z)')); ?>:</strong>
                                        <?php echo e(translate('Sorts items or vendors in ascending alphabetical order.')); ?></li>
                                    <li><strong><?php echo e(translate('Alphabetical order (Z–A)')); ?>:</strong>
                                        <?php echo e(translate('Sorts items or vendors in descending alphabetical order.')); ?></li>
                                    <li><strong><?php echo e(translate('Nearest item first')); ?>:</strong>
                                        <?php echo e(translate('Displays items based on proximity to the customer.')); ?></li>
                                    <li><strong><?php echo e(translate('Sort by reviews count')); ?>:</strong>
                                        <?php echo e(translate('Order items/vendors by the number of reviews received, highest first.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Sort by ratings')); ?>:</strong>
                                        <?php echo e(translate('Displays items/vendors with the highest average rating first.')); ?>

                                    </li>
                                    <li><strong><?php echo e(translate('Show 4+ rated items')); ?>:</strong>
                                        <?php echo e(translate('Displays only items with ratings of 4 or higher.')); ?></li>
                                    <li><strong><?php echo e(translate('Show 3.5+ rated items')); ?>:</strong>
                                        <?php echo e(translate('Displays items with ratings of 3.5 or higher.')); ?></li>
                                    <li><strong><?php echo e(translate('Show 3+ rated items')); ?>:</strong>
                                        <?php echo e(translate('Displays items with ratings of 3 or higher.')); ?></li>
                                </ul>
                            </li>
                        </ul>
                        <p class="fs-12 mb-0">
                            <?php echo e(translate('When Custom Sorting is selected, the system applies the chosen sorting rule dynamically to display items, categories, and subcategories accordingly.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
    <script>
        $(".collapse-div-toggler").on('change', function() {
            $(this).closest('.sorting-card').find('.inner-collapse-div').slideToggle();
            $(this).closest('.sorting-card').siblings().find('.inner-collapse-div').slideUp();
            $(this).closest('.sorting-card').siblings().find('.toggle-switch-input').prop('checked', false);
        });

        $(window).on('load', function() {
            $('.collapse-div-toggler').each(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('.sorting-card').find('.inner-collapse-div').show();
                }
            });
        })

        $('#reset_btn').click(function() {
            $('.collapse-div-toggler').each(function() {
                if ($(this).prop('checked') == true) {
                    $(this).closest('.sorting-card').find('.inner-collapse-div').show();
                } else {
                    $(this).closest('.sorting-card').siblings().find('.inner-collapse-div').slideUp();
                    $(this).closest('.sorting-card').siblings().find('.toggle-switch-input').prop('checked',
                        false);
                }
            });
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\settings\priority-index.blade.php ENDPATH**/ ?>