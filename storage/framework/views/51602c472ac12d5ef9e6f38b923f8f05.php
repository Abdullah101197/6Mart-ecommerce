<div class="row">
    <div class="col-lg-12 text-center ">
        <h1><?php echo e(translate('delivery_man_loyalty_point_transaction_history')); ?></h1>
    </div>
    <div class="col-lg-12">



        <table>
            <thead>
                <tr>
                    <th><?php echo e(translate('delivery_man_info')); ?></th>
                    <th></th>
                    <th></th>
                    <th>
                        <?php echo e(translate('name')); ?>- <?php echo e($data['dm']->f_name . ' ' . $data['dm']->l_name); ?>

                        <br>
                        <?php echo e(translate('phone')); ?>- <?php echo e($data['dm']->phone); ?>

                        <br>
                        <?php echo e(translate('email')); ?>- <?php echo e($data['dm']->email); ?>

                        <br>
                        <?php echo e(translate('total_rating')); ?>- <?php echo e(count($data['dm']->rating)); ?>

                        <br>
                        <?php echo e(translate('average_review')); ?>-
                        <?php echo e(count($data['dm']->rating) > 0 ? number_format($data['dm']->rating[0]->average, 1, '.', ' ') : 0); ?>


                    </th>
                    <th> </th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                
                <tr>
                    <th><?php echo e(translate('SL')); ?></th>
                    <th><?php echo e(translate('messages.Transaction ID')); ?></th>
                    <th><?php echo e(translate('messages.Date')); ?></th>
                    <th><?php echo e(translate('messages.Transaction Type')); ?></th>
                    <th><?php echo e(translate('messages.Point')); ?></th>
                    <th><?php echo e(translate('messages.Reference')); ?></th>

            </thead>
            <tbody>
                <?php $__currentLoopData = $data['histories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $loyalty_point): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-center"><?php echo e($key + 1); ?></td>
                        <td>
                            <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                <?php echo e($loyalty_point->transaction_id); ?>

                            </div>
                        </td>
                        <td>
                            <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                <?php echo e(\App\CentralLogics\Helpers::date_format($loyalty_point->created_at)); ?>

                            </div>
                        </td>
                        <td>
                            <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                <?php echo e(translate($loyalty_point->transaction_type)); ?>

                                <?php echo e($loyalty_point->transaction_type == 'converted_to_wallet' ? '(' . \App\CentralLogics\Helpers::currency_symbol() . ')' : ''); ?>

                            </div>
                        </td>
                        <td>
                            <div class="text-dark text-right pr-6">
                                <?php echo e($loyalty_point->point_conversion_type == 'credit' ? '+' : '-'); ?>

                                <?php echo e($loyalty_point->point); ?> <br>
                                <?php if($loyalty_point->point_conversion_type == 'credit'): ?>
                                    <span type="button"
                                        class="btn px-3 fs-12 py-1 badge-soft-success"><?php echo e(translate('credit')); ?></span>
                                <?php else: ?>
                                    <span type="button"
                                        class="btn px-3 fs-12 py-1 badge-soft-danger"><?php echo e(translate('Debit')); ?></span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <?php echo e($loyalty_point->reference ?? translate('N/A')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\file-exports\single-deliveryman-loyalty-point.blade.php ENDPATH**/ ?>