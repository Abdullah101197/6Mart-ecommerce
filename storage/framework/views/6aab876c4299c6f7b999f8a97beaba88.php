

<?php $__env->startSection('title',translate('messages.language')); ?>


<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 font-bold mb-1 text-capitalize">
                <?php echo e(translate('View Translations')); ?> - <?php echo e(\App\CentralLogics\Helpers::get_language_name($lang)); ?> (<?php echo e($lang); ?>)
            </h2>
            <h6 class="text-info fs-12 d-flex gap-2 align-items-center mb-0">
                <i class="tio-back-ui fs-10"></i>
                <a style="color: #245BD1;" href="<?php echo e(route('admin.business-settings.language.index')); ?>"><?php echo e(translate('messages.Back to Language Setup')); ?></a>
            </h6>
        </div>
        <div class="fs-12 text-title px-3 py-2 rounded bg-warning d-flex align-items-center gap-2 h-100 bg-opacity-10 mb-3">
            <span class="text-warning lh-1 fs-14">
                <i class="tio-info"></i>
            </span>
            <span>
                <?php echo e(translate('messages.If you change your default language full')); ?>

                <span class="font-semibold"><?php echo e(translate('messages.System Language')); ?></span>
                <?php echo e(lcfirst(translate('messages.will changed. So, make sure before change'))); ?>

                <span class="font-semibold"><?php echo e(translate('messages.Default Language.')); ?></span>
            </span>

        </div>
        <div class="card card-body">
            <div class="d-flex align-items-center flex-wrap justify-content-end gap-3 mb-20">
                <h4 class="m-0 text-capitalize flex-grow-1"><?php echo e(translate('language_content_table')); ?></h4>
                <form class="search-form min--260">
                    <!-- Search -->
                    <div class="input-group input--group">
                        <input id="datatableSearch_" type="search" name="search" class="form-control h--40px"
                                placeholder="<?php echo e(translate('messages.Search_Language')); ?>" aria-label="<?php echo e(translate('messages.search')); ?>" value="<?php echo e(request()?->search ?? null); ?>" required>
                        <input type="hidden">
                        <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>

                    </div>
                    <!-- End Search -->
                </form>
                <?php if($lang !== 'en'): ?>
                <button class="btn btn--primary d-flex align-items-center justify-content-center gap-2" id="translate-confirm-btn">
                    <img width="14" height="14" class="svg" src="<?php echo e(asset('assets/admin/img/svg/language-exchange.svg')); ?>" alt="public">
                    <?php echo e(translate('Translate_All')); ?>

                </button>
                <?php endif; ?>
            </div>
            <input type="hidden" value="0" id="translating-count">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" >
                    <thead class="thead-light table-nowrap">
                    <tr>
                        <th><?php echo e(translate('SL#')); ?></th>
                        <th class="__width-400"><?php echo e(translate('Current_value')); ?></th>
                        <th class="__min-width"><?php echo e(translate('translated_value')); ?></th>
                        <th class="text-center"><?php echo e(translate('auto_translate')); ?></th>
                        <th class="text-center"><?php echo e(translate('update')); ?></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php ($count=0); ?>
                    <?php $__currentLoopData = $full_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php ($count++); ?>

                    <tr id="lang-<?php echo e($count); ?>">
                        <td><?php echo e($count+$full_data->firstItem() -1); ?></td>
                        <td >
                            <input type="text" name="key[]"
                            value="<?php echo e($key); ?>" hidden>
                            <div style="max-inline-size: 450px"> <?php echo e(translate($key)); ?></div>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="value[]"
                            id="value-<?php echo e($count); ?>"
                            value="<?php echo e($full_data[$key]); ?>">
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button"
                                    data-key="<?php echo e($key); ?>" data-id="<?php echo e($count); ?>"
                                    class="btn btn--primary btn-outline-primary action-btn auto-translate-btn">
                                    <img width="14" height="14" class="svg" src="<?php echo e(asset('assets/admin/img/svg/language-exchange.svg')); ?>" alt="public">
                                </button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <button type="button"
                                        data-key="<?php echo e($key); ?>"
                                        data-id="<?php echo e($count); ?>"
                                        class="btn btn--primary action-btn update-language-btn">
                                         <img width="14" height="14" class="svg" src="<?php echo e(asset('assets/admin/img/svg/disk.svg')); ?>" alt="public">
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php if(count($full_data) !== 0): ?>
                <hr>
                <?php endif; ?>
                <div class="page-area">
                    <?php echo $full_data->appends(request()->query())->links(); ?>

                </div>
                <?php if(count($full_data) === 0): ?>
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


    <div class="modal fade language-complete-modal" id="translate-confirm-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered max-w-450px">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="py-5">
                        <div class="mb-4">
                            <img src="<?php echo e(asset('/assets/admin/img/language-complete.png')); ?>" alt="">
                        </div>
                        <h4 class="mb-3"><?php echo e(translate('messages.Are you sure ?')); ?></h4>
                        <p class="mb-4 text-9EADC1 max-w-362px mx-auto">
                            <?php echo e(translate('You_want_to_auto_translate_all._It_may_take_a_while_to_complete_the_translation')); ?>

                        </p>
                        <div class="d-flex justify-content-center gap-3 pt-1">

                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                            <button type="button" class="btn btn--primary auto_translate_all" data-dismiss="modal" ><?php echo e(translate('Yes,_Translate_All')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade language-complete-modal" id="complete-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered max-w-450px">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="py-5">
                        <div class="mb-4">
                            <img src="<?php echo e(asset('/assets/admin/img/language-complete.png')); ?>" alt="">
                        </div>
                        <h4 class="mb-3"><?php echo e(translate('Your_file_has_been_successfully_translated')); ?></h4>
                        <p class="mb-4 text-9EADC1 max-w-362px mx-auto">
                            <?php echo e(translate('All_your_items_has_been_translated.')); ?>

                        </p>
                        <div class="d-flex justify-content-center gap-3 pt-1">
                            <button type="button" class="btn btn--primary location_reload" data-dismiss="modal"><?php echo e(translate('messages.Okay')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade language-warning-modal" id="warning-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex gap-3 align-items-start">
                        <img src="<?php echo e(asset('/assets/admin/img/invalid-icon.png')); ?>" alt="">
                        <div class="w-0 flex-grow-1">
                            <h3><?php echo e(translate('Warning!')); ?></h3>
                            <p>
                               <?php echo e(translate('Translating_in_progress._Are_you_sure,_want_to_close_this_tab?_If_you_close_the_tab,_then_some_translated_items_will_be_unchanged.')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(translate('Cancel')); ?></button>
                        <button type="button" class="btn btn--primary" id="close-tab" ><?php echo e(translate('Yes,_Close')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade language-complete-modal " id="translating-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="py-5 px-sm-2">
                        <div class="progress-circle-container mb-4">
                            <img width="80px" src="<?php echo e(asset('/assets/admin/img/loader-icon.gif')); ?>" alt="">
                        </div>
                        <h4 class="mb-2"><?php echo e(translate('Translating_may_take_up_to')); ?> <span id="time-data"> <?php echo e(translate('Hours')); ?></span></h4>
                        <p class="mb-4">
                            <?php echo e(translate('Please_wait_&_don’t_close/terminate_your_tab_or_browser')); ?>

                        </p>
                        <div class="max-w-215px mx-auto">
                            <div class="d-flex flex-wrap mb-1 justify-content-between font-semibold text--title">
                                <span><?php echo e(translate('In_Progress')); ?></span>
                                <span class="translating-modal-success-rate">0.4%</span>
                            </div>
                            <div class="progress mb-3 h-5px">
                                <div class="progress-bar bg-success rounded-pill translating-modal-success-bar" style="width: 0.4%"></div>
                            </div>
                        </div>
                        <p class="mb-4 text-9EADC1">
                            <span class="text-dark"><?php echo e(translate('note:')); ?></span> <?php echo e(translate('All_the_translations_may_not_be_fully_accurate.')); ?>

                        </p>
                        <div class="d-flex justify-content-center gap-3 pt-1">
                            <button type="button" class="btn btn--primary location-reload"  ><?php echo e(translate('messages.Cancel')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script_2'); ?>
<script>
    "use strict"


    $(document).on('click', '.auto-translate-btn', function () {
        let key = $(this).data('key');
        let id = $(this).data('id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "<?php echo e(route('admin.business-settings.language.auto-translate',[$lang])); ?>",
            method: 'POST',
            data: {
                key: key
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (response) {
                toastr.success('<?php echo e(translate('Key translated successfully')); ?>');
                $('#value-'+id).val(response.translated_data);
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });
    $(document).on('click', '.update-language-btn', function () {
        let key = $(this).data('key');
        let id = $(this).data('id');
        let value = $('#value-'+id).val() ;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "<?php echo e(route('admin.business-settings.language.translate-submit',[$lang])); ?>",
            method: 'POST',
            data: {
                key: key,
                value: value
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function () {
                toastr.success('<?php echo e(translate('text_updated_successfully')); ?>');
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });







    $(document).on('click', '#translate-confirm-btn', function () {
        $('#translate-confirm-modal').modal('show')

    });
    $(document).on('click', '.auto_translate_all', function () {
        auto_translate_all();

    });
    $(document).on('click', '.location_reload', function () {
        location.reload();

    });
    $(document).on('click', '.close-tab', function () {
        $('#translating-modal').removeClass('prevent-close')
        window.close();

    });

    function  auto_translate_all(){
        var total_count;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "<?php echo e(route('admin.business-settings.language.auto_translate_all',[$lang])); ?>",
            method: 'get',
            data: {
                translating_count: $('#translating-count').val(),
            },
            beforeSend: function () {
                $('#translating-modal').addClass('prevent-close')
                $('#translating-modal').modal('show')
            },
            success: function (response) {

                if(response.data === 'data_prepared'){
                    $('#translating-modal').modal('show')
                    $('#translating-count').val(response.total)
                    auto_translate_all();
                } else if(response.data === 'translating' &&  response.status === 'pending' ){
                    if($('#translating-count').val() == 0  ){
                        $('#translating-count').val(response.total)
                    }

                    $('.translating-modal-success-rate').html(response.percentage + '%');
                    $('.translating-modal-success-bar').attr('style', 'width:' + response.percentage + '%');


                        if(response.hours > 0){
                            $('#time-data').html(response.hours + ' <?php echo e(translate('hours')); ?> ' + response.minutes + ' <?php echo e(translate('min')); ?>' );
                        }
                        if(response.minutes > 0 && response.hours <= 0){
                            $('#time-data').html(response.minutes + ' <?php echo e(translate('min')); ?> ' +  response.seconds + ' <?php echo e(translate('seconds')); ?>');
                        }
                        if(response.seconds > 0 && response.minutes <= 0){
                            $('#time-data').html(response.seconds + ' <?php echo e(translate('seconds')); ?>');
                        }

                    auto_translate_all();

                    $('#translating-modal').modal('show')
                    } else if((response.data === 'translating' &&  response.status === 'done') || response.data === 'success' || response.data === 'error'  ){
                        $('#translating-modal').removeClass('prevent-close')
                        $('#translating-modal').modal('hide')
                        $('#translating-count').val(0)
                        if(response.data !== 'error'){
                            $('#complete-modal').modal('show')
                        } else{
                            toastr.error(response.message);
                        }
                    }
            },
            complete: function () {
            },
        });
    }

    const modal = document.getElementById('translating-modal');
    window.addEventListener('beforeunload', (event) => {

        if (modal.classList.contains('prevent-close')) {
            // $('#warning-modal').modal('show')
            event.preventDefault();
            event.returnValue = '';
        }
    });
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\language\translate.blade.php ENDPATH**/ ?>