

<?php $__env->startSection('title', translate('messages.Notification Channels')); ?>
<?php $__env->startSection('notification_setup_type'); ?>
active
<?php $__env->stopSection(); ?>
<?php $__env->startSection('notification_setup'); ?>
active
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="content container-fluid">




        <div class="page-header d-flex flex-wrap align-items-center justify-content-between">
            <h1 class="page-header-title">
                <span>
                    <?php echo e(translate('messages.Notification Channels Setup')); ?>

                </span>
            </h1>
            <div class="text--primary-2 d-flex flex-wrap align-items-center" type="button" data-toggle="modal" data-target="#notiifcation-how-it-works">
                <strong class="mr-2"><?php echo e(translate('how_it_works!')); ?></strong>
                  <div class="blinkings">
                    <i class="tio-info-outined"></i>
                </div>
            </div>
        </div>



            <!-- Title -->
            <div class="mb-3 d-flex align-items-start gap-2">
                <img src="<?php echo e(asset('assets/admin/img/bell-2.png')); ?>" alt="">
                <div class="w-0 flex-grow mb-2">
                    <?php echo e(translate('From here you setup who can see what types of notification from')); ?> <?php echo e($business_name); ?>


                </div>
            </div>

            <!-- Nav Menus -->
            <ul class="nav nav-tabs border-0 nav--tabs nav--pills mb-4">
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()?->type == null || request()?->type == 'admin' ?  'active' : ''); ?> " href="<?php echo e(route('admin.business-settings.notification_setup' ,['type' =>  'admin'])); ?>"><?php echo e(translate('Admin')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()?->type == 'store' ?  'active' : ''); ?> " href="<?php echo e(route('admin.business-settings.notification_setup' ,['type' =>  'store'])); ?>"><?php echo e(translate('store')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()?->type == 'customers' ?  'active' : ''); ?>"   href="<?php echo e(route('admin.business-settings.notification_setup' ,['type' =>  'customers'])); ?>"><?php echo e(translate('Customers')); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()?->type == 'deliveryman' ?  'active' : ''); ?> "  href="<?php echo e(route('admin.business-settings.notification_setup' ,['type' =>  'deliveryman'])); ?>"><?php echo e(translate('Deliveryman')); ?></a>
                </li>
            </ul>

            <div class="fs-12 text-dark px-3 py-12px rounded bg-warning-10 mt-20 mb-20">
                <div class="d-flex gap-2 mb-1">
                    <span class="text-warning lh-1 fs-14">
                        <i class="tio-info"></i>
                    </span>
                    <span>
                        <?php echo e(translate('Setup the push notifications for the user and complete the below configuartions')); ?>

                    </span>
                </div>
                <ul class="mb-0 gap-1 d-flex flex-column">
                    <li>
                        <?php echo e(translate('Configure the setup from')); ?> <a href="javascript:void(0)" class="font-semibold text-primary text-underline"><?php echo e(translate('Firebase Configuration')); ?></a>
                    </li>
                    <li>
                        <?php echo e(translate('Setup Mail Template and')); ?> <a href="javascript:void(0)" class="font-semibold text-primary text-underline"><?php echo e(translate('Mail configuration')); ?></a>
                    </li>
                </ul>
            </div>


            <div class="card">
                <div class="card-body">
                    <div class="d-flex w-100 flex-md-nowrap flex-wrap align-items-center gap-3 justif-content-between pb-20">
                        <div>
                            <h3 class="mb-1">
                                <?php echo e(translate('Admin Notification Channels')); ?>

                            </h3>
                            <p class="mb-0 fs-12">
                                <?php echo e(translate('From here you setup who can see what types of notification from 6amMart')); ?>

                            </p>
                        </div>
                        <form class="search-form ml-auto min--260">
                            <div class="input-group input--group">
                                <input id="" type="search" name="search" class="form-control h--40px" placeholder="Search by topic" value="">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive datatable-custom table-admin-channels shadow-sm rounded">
                        <table class="font-size-sm table table-borderless table-thead-bordered table-align-middle card-table">
                            <thead class="thead-light table-nowrap">
                                <tr>
                                    <th class="sl-top w-70px"> <div class="w-70px"><?php echo e(translate('sl')); ?></div></th>
                                    <th class="title_grp"><?php echo e(translate('Topics')); ?></th>
                                    <th class="w-180 text-center"><?php echo e(translate('Push Notification')); ?></th>
                                    <th class="text-end w-340"><?php echo e(translate('Mail')); ?></th>
                                    <th class="text-end"><div class="pe-30"><?php echo e(translate('SMS')); ?></div></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td colspan="12">
                                        <div class="d-flex gap-2 p-20 py-2 table-toggle-btn cursor-pointer">
                                            <span class="rounded-circle bg-opacity-primary-10 d-center text-primary w-20px h-20px fs-16">
                                                <i class="tio-chevron-down"></i>
                                            </span>
                                            <h5 class="fz-16 text-capitalize"><?php echo e(translate('Forget password')); ?></h5>
                                        </div>
                                        <div class="table-custom-wrap">
                                            <div class="table-custom-td gap-2 d-flex align-items-center border-bottom p-20 py-2">
                                                <div class="sl-top w-70px">
                                                    <?php echo e($key +1); ?>

                                                </div>
                                                <div class="title_grp">
                                                    <h5 class="text-capitalize line--limit-1"><?php echo e(translate($item->title)); ?></h5>
                                                    <div class="white-space-initial line--limit-2 text-capitalize max-w-400px">
                                                    <?php echo e(translate('Choose_how_')); ?> <?php echo e(translate($item->type)); ?> <?php echo e(translate('_will_get_notified_about')); ?>  <?php echo e(translate($item->sub_title)); ?>.
                                                    </div>
                                                </div>
                                                <div class="text-center w--150px">
                                                    <div class="d-flex justify-content-center">
                                                        <div>
                                                            <?php if($item->push_notification_status == 'disable'): ?>
                                                            <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                                            <?php else: ?>

                                                            <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip"
                                                                <?php if($item->push_notification_status  == 'active'): ?>
                                                                    title="<?php echo e(translate('Turn_Off_push_notification_for') .' '.translate($item->title)); ?>"
                                                                <?php else: ?>
                                                                    title="<?php echo e(translate('Turn_On_push_notification_for') .' '.translate($item->title)); ?>"
                                                                <?php endif; ?> >
                                                                <input type="checkbox"
                                                                id="push_notification_<?php echo e($item->key); ?>"
                                                                data-id="push_notification_<?php echo e($item->key); ?>"
                                                                data-type="toggle" data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to enable the Push Notification For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the Push Notification For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('Push Notification Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('Push Notification Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox"  <?php echo e($item->push_notification_status  == 'active' ? 'checked' : ''); ?>>
                                                                <span class="toggle-switch-label text">
                                                                    <span class="toggle-switch-indicator"></span>
                                                                </span>
                                                            </label>
                                                            <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key ,'user_type' => $item->type ,'type' => 'push_notification'])); ?>" method="get" id="push_notification_<?php echo e($item->key); ?>_form">
                                                            </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="w-342">
                                                    <div class="d-flex justify-content-end">
                                                        <div>
                                                        <?php if($item->mail_status == 'disable'): ?>
                                                            <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                                        <?php else: ?>
                                                        <label class="toggle-switch toggle-switch-sm"
                                                                <?php if($item->mail_status  == 'active'): ?>
                                                                data-toggle="tooltip" title="<?php echo e(translate('Turn_Off_Mail_for') .' '.translate($item->title)); ?>"
                                                                <?php else: ?>
                                                                data-toggle="tooltip" title="<?php echo e(translate('Turn_On_Mail_for') .' '.translate($item->title)); ?>"
                                                                <?php endif; ?>
                                                            >
                                                            <input type="checkbox" data-type="toggle"
                                                            id="mail_<?php echo e($item->key); ?>"
                                                            data-id="mail_<?php echo e($item->key); ?>"
                                                            data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to enable the Mail For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the Mail For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('Mail Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('Mail Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox" <?php echo e($item->mail_status  == 'active' ? 'checked' : ''); ?>>
                                                            <span class="toggle-switch-label text">
                                                                <span class="toggle-switch-indicator"></span>
                                                            </span>
                                                        </label>
                                                        <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key ,'user_type' => $item->type ,'type' => 'Mail'])); ?>" method="get" id="mail_<?php echo e($item->key); ?>_form">
                                                        </form>
                                                        <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ml-auto">
                                                    <div class="d-flex justify-content-center">
                                                        <div>

                                                            <?php if($item->sms_status == 'disable'): ?>
                                                            <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                                            <?php else: ?>

                                                            <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip"

                                                            <?php if($item->sms_status  == 'active'): ?>

                                                            title="<?php echo e(translate('Turn_Off__SMS_for') .' '.translate($item->title)); ?>"
                                                            <?php else: ?>

                                                            title="<?php echo e(translate('Turn_On_SMS_for') .' '.translate($item->title)); ?>"
                                                            <?php endif; ?>


                                                            >
                                                                <input type="checkbox"
                                                                    id="SMS_<?php echo e($item->key); ?>"
                                                                data-id="SMS_<?php echo e($item->key); ?>"
                                                                data-type="toggle" data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to disable the SMS For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the SMS For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('SMS Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('SMS Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox" <?php echo e($item->sms_status  == 'active' ? 'checked' : ''); ?>>
                                                                <span class="toggle-switch-label text">
                                                                    <span class="toggle-switch-indicator"></span>
                                                                </span>
                                                            </label>
                                                            <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key,'user_type' => $item->type ,'type' => 'SMS'])); ?>" method="get" id="SMS_<?php echo e($item->key); ?>_form">
                                                            </form>
                                                                <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td><?php echo e($key +1); ?></td>
                                    <td>
                                        <h5 class="text-capitalize"><?php echo e(translate($item->title)); ?></h5>
                                        <div class="white-space-initial text-capitalize max-w-400px">
                                        <?php echo e(translate('Choose_how_')); ?> <?php echo e(translate($item->type)); ?> <?php echo e(translate('_will_get_notified_about')); ?>  <?php echo e(translate($item->sub_title)); ?>.
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <?php if($item->push_notification_status == 'disable'): ?>
                                                <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                                <?php else: ?>

                                                <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip"
                                                    <?php if($item->push_notification_status  == 'active'): ?>
                                                        title="<?php echo e(translate('Turn_Off_push_notification_for') .' '.translate($item->title)); ?>"
                                                    <?php else: ?>
                                                        title="<?php echo e(translate('Turn_On_push_notification_for') .' '.translate($item->title)); ?>"
                                                    <?php endif; ?> >
                                                    <input type="checkbox"
                                                    id="push_notification_<?php echo e($item->key); ?>"
                                                    data-id="push_notification_<?php echo e($item->key); ?>"
                                                    data-type="toggle" data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to enable the Push Notification For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the Push Notification For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('Push Notification Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('Push Notification Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox"  <?php echo e($item->push_notification_status  == 'active' ? 'checked' : ''); ?>>
                                                    <span class="toggle-switch-label text">
                                                        <span class="toggle-switch-indicator"></span>
                                                    </span>
                                                </label>
                                                <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key ,'user_type' => $item->type ,'type' => 'push_notification'])); ?>" method="get" id="push_notification_<?php echo e($item->key); ?>_form">
                                                </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div>
                                            <?php if($item->mail_status == 'disable'): ?>
                                                <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                            <?php else: ?>
                                            <label class="toggle-switch toggle-switch-sm"
                                                    <?php if($item->mail_status  == 'active'): ?>
                                                    data-toggle="tooltip" title="<?php echo e(translate('Turn_Off_Mail_for') .' '.translate($item->title)); ?>"
                                                    <?php else: ?>
                                                    data-toggle="tooltip" title="<?php echo e(translate('Turn_On_Mail_for') .' '.translate($item->title)); ?>"
                                                    <?php endif; ?>
                                                >
                                                <input type="checkbox" data-type="toggle"
                                                id="mail_<?php echo e($item->key); ?>"
                                                data-id="mail_<?php echo e($item->key); ?>"
                                                data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to enable the Mail For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the Mail For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('Mail Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('Mail Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox" <?php echo e($item->mail_status  == 'active' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key ,'user_type' => $item->type ,'type' => 'Mail'])); ?>" method="get" id="mail_<?php echo e($item->key); ?>_form">
                                            </form>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div>

                                            <?php if($item->sms_status == 'disable'): ?>
                                            <span class="badge badge-pill badge--info">  <?php echo e(translate('messages.N/A')); ?></span>
                                            <?php else: ?>

                                            <label class="toggle-switch toggle-switch-sm" data-toggle="tooltip"

                                            <?php if($item->sms_status  == 'active'): ?>

                                            title="<?php echo e(translate('Turn_Off__SMS_for') .' '.translate($item->title)); ?>"
                                            <?php else: ?>

                                            title="<?php echo e(translate('Turn_On_SMS_for') .' '.translate($item->title)); ?>"
                                            <?php endif; ?>


                                            >
                                                <input type="checkbox"
                                                    id="SMS_<?php echo e($item->key); ?>"
                                                data-id="SMS_<?php echo e($item->key); ?>"
                                                data-type="toggle" data-image-on="<?php echo e(asset('assets/admin/img/modal/mail-success.png')); ?>" data-image-off="<?php echo e(asset('assets/admin/img/modal/mail-warning.png')); ?>" data-title-on="<?php echo e(translate('Want to disable the SMS For') .' '.  translate($item->title)); ?> ?" data-title-off="<?php echo e(translate('Want to disable the SMS For') .' '.  translate($item->title)); ?> ?" data-text-on="<p><?php echo e(translate('SMS Will Be Enabled For')  .' '.  translate($item->title)); ?></p>" data-text-off="<p><?php echo e(translate('SMS Will Be disabled For')  .' '.  translate($item->title)); ?></p>" class="status toggle-switch-input dynamic-checkbox" <?php echo e($item->sms_status  == 'active' ? 'checked' : ''); ?>>
                                                <span class="toggle-switch-label text">
                                                    <span class="toggle-switch-indicator"></span>
                                                </span>
                                            </label>
                                            <form action="<?php echo e(route('admin.business-settings.notification_status_change',['key'=> $item->key,'user_type' => $item->type ,'type' => 'SMS'])); ?>" method="get" id="SMS_<?php echo e($item->key); ?>_form">
                                            </form>
                                                <?php endif; ?>

                                                </div>
                                        </div>


                                    </td>
                                </tr> -->

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="notiifcation-how-it-works">
                <div class="modal-dialog modal-dialog-centered status-warning-modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true" class="tio-clear"></span>
                            </button>
                        </div>
                        <div class="modal-body pb-5 pt-0">
                            <div class="max-349 mx-auto mb-20">
                                <div>
                                    <div class="text-center">
                                        <img width="80" src="<?php echo e(asset('assets/admin/img/modal/bell.png')); ?>" class="mb-20">
                                        <h5 class="modal-title"></h5>
                                    </div>
                                    <div class="text-center" >
                                        <h3 > <?php echo e(translate('Notification Setup')); ?></h3>
                                        <div > <p><?php echo e(translate('Enable or disable the notification channel to control notifications for a specific feature or topic.')); ?></h3></p></div>
                                    </div>
                                    <div class="text-center" >

                                        <div > <p> <strong><?php echo e(translate('For_example,')); ?></strong> <?php echo e(translate('if the ‘Order Placed‘ push notification is turned off for a customer, they will not receive a push notification in the customer app when an order is placed.')); ?></h3></p></div>
                                    </div>


                                    </div>

                                <div class="btn--container justify-content-center">
                                    <button data-dismiss="modal"   type="button"  class="btn btn--primary min-w-120"><?php echo e(translate('Okay, Got it')); ?></button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\business-settings\notification_setup_copy.blade.php ENDPATH**/ ?>