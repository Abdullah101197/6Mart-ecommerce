<div class="row">
    <div class="col-lg-12 text-center ">
        <h3><?php echo e(translate('delivery_man_referral_and_earn_history')); ?></h3>
    </div>
    <div class="col-lg-12">



        <table>
            <thead>
                <tr>
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
                    <th></th>
                    <th></th>

                    <th> </th>
                </tr>

                <tr>
                    <th><?php echo e(translate('SL')); ?></th>
                    <th><?php echo e(translate('messages.Transaction ID')); ?></th>
                    <th><?php echo e(translate('messages.Date')); ?></th>
                    <th><?php echo e(translate('messages.Amount')); ?></th>
                    <th><?php echo e(translate('messages.Reference')); ?></th>

            </thead>
            <tbody>
                <?php $__currentLoopData = $data['histories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $referralEarning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                                    <td class="text-center"><?php echo e($key + 1); ?></td>
                                    <td>
                                        <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                            <?php echo e($referralEarning->transaction_id); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-wrap line--limit-1  max-w--220px min-w-160 text-title">
                                            <?php echo e(\App\CentralLogics\Helpers::date_format($referralEarning->created_at)); ?>

                                        </div>
                                         <?php if($referralEarning->refer_type == 'referrerBonus'): ?>
                                            <div>
                                                <span class="text--title">(<?php echo e(translate('messages.Referral_Bonus')); ?>)</span>
                                            </div>
                                            <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-center text-title">
                                            <?php echo e(\App\CentralLogics\Helpers::format_currency($referralEarning->amount)); ?>

                                        </div>
                                    </td>
                                    <td>
                                        <?php echo e($referralEarning->reference ?? translate('N/A')); ?>

                                    </td>
                                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\file-exports\single-deliveryman-referral-earn.blade.php ENDPATH**/ ?>