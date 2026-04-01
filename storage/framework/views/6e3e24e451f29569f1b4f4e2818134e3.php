<?php if(count($combinations[0]) > 0): ?>
    <table class="table table-borderless table--vertical-middle">
        <thead class="thead-light __bg-7">
            <tr>
                <th class="text-center border-0">
                    <span class="control-label m-0"><?php echo e(translate('messages.Variant')); ?></span>
                </th>
                <th class="text-center border-0">
                    <span class="control-label"><?php echo e(translate('messages.Variant Price')); ?></span>
                </th>
                <?php if($stock): ?>
                    <th class="text-center border-0">
                        <span class="control-label text-capitalize"><?php echo e(translate('messages.stock')); ?></span>
                    </th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $combination): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(strlen($combination['name']) > 0): ?>
                    <tr>
                        <td class="text-center">
                            <label class="control-label m-0"><?php echo e($combination['name']); ?></label>
                        </td>
                        <td class="error-wrapper">
                            <input type="number" name="price_<?php echo e($combination['name']); ?>" value="<?php echo e($combination['price']); ?>" min="0"
                                step="0.01" class="form-control" required>
                        </td>
                        <?php if($stock): ?>
                            <td class="error-wrapper"><input type="number" name="stock_<?php echo e($combination['name']); ?>"
                                    value="<?php echo e($combination['stock']); ?>" min="0" class="form-control" required></td>
                        <?php endif; ?>

                    </tr>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\product\partials\_variant-combinations.blade.php ENDPATH**/ ?>