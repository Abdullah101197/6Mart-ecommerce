<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="" id="row-<?php echo e($item->id); ?>" data-id="<?php echo e($item->id); ?>" data-cost="<?php echo e($item->cost_price); ?>">
        <td><input type="checkbox" class="row-check pc-check" value="<?php echo e($item->id); ?>"
                onchange="toggleRow(<?php echo e($item->id); ?>, this)"></td>
        <td>
            <div class="prod-cell">
                <div class="prod-img">
                    <img src="<?php echo e($item['image_full_url'] ?? asset('assets/admin/img/160x160/img2.jpg')); ?>"
                        onerror="this.src='<?php echo e(asset('assets/admin/img/160x160/img2.jpg')); ?>'" alt="">
                </div>
                <div>
                    <div class="prod-name"><?php echo e($item->name); ?></div>
                </div>
            </div>
        </td>
        <td>
            <div class="inline-val" onclick="editField(<?php echo e($item->id); ?>, 'sku', '<?php echo e($item->sku); ?>', 'SKU')">
                <span class="prod-sku"><?php echo e($item->sku ? $item->sku : translate('messages.no_sku')); ?></span>
                <span class="inline-edit-icon">✏️</span>
            </div>
            <div class="inline-val" style="margin-top:2px; font-size:11px; color:var(--muted)"
                onclick="editField(<?php echo e($item->id); ?>, 'ean', '<?php echo e($item->ean); ?>', 'EAN')">
                <span><?php echo e(translate('EAN')); ?>: <?php echo e($item->ean ? $item->ean : '—'); ?></span>
                <span class="inline-edit-icon" style="font-size:9px">✏️</span>
            </div>
        </td>
        <td>
            <div class="cat-crumb">
                <?php if($item->category && isset($item->category->parent)): ?>
                    <?php echo e($item->category->parent->name); ?>

                    <br><span><?php echo e($item->category->name); ?></span>
                <?php elseif($item->category): ?>
                    <span><?php echo e($item->category->name); ?></span>
                <?php else: ?>
                    <?php echo e(translate('Uncategorized')); ?>

                <?php endif; ?>
            </div>
        </td>
        <td>
            <div class="inline-val" onclick="editField(<?php echo e($item->id); ?>, 'price', <?php echo e($item->price); ?>, 'Price (QAR)')">
                <?php echo e(\App\CentralLogics\Helpers::format_currency($item->price)); ?>

                <span class="inline-edit-icon">✏️</span>
            </div>
            <div class="cat-crumb" style="margin-top:2px"><?php echo e(translate('Cost')); ?>:
                <?php echo e(\App\CentralLogics\Helpers::format_currency($item->cost_price)); ?>

            </div>
        </td>
        <td>
            <?php
                $margin = $item->price > 0 ? round((($item->price - $item->cost_price) / $item->price) * 100) : 0;
                $marginClass = $margin >= 35 ? 'mp-good' : ($margin >= 20 ? 'mp-ok' : 'mp-bad');
            ?>
            <div class="margin-pill <?php echo e($marginClass); ?>"
                onclick="editField(<?php echo e($item->id); ?>, 'cost_price', <?php echo e($item->cost_price); ?>, 'Cost Price (QAR)')">
                <?php echo e($margin); ?>%
            </div>
        </td>
        <td class="stock-cell">
            <div class="inline-val" onclick="editField(<?php echo e($item->id); ?>, 'stock', <?php echo e($item->stock); ?>, 'Stock Quantity')">
                <span class="stock-num"><?php echo e($item->stock); ?></span>
                <span class="inline-edit-icon">✏️</span>
            </div>
            <?php
                $stockPercent = min(100, max(0, ($item->stock / 500) * 100)); // Adjusted for demo visual
                $stockFill = $item->stock == 0 ? 'sf-low' : ($item->stock < 20 ? 'sf-med' : 'sf-high');
            ?>
            <div class="stock-bar-bg">
                <div class="stock-bar-fill <?php echo e($stockFill); ?>" style="width: <?php echo e($stockPercent); ?>%"></div>
            </div>
        </td>
        <td>
            <?php
                $shelfLife = $item->expiry_days ?? 0;
                $shelfClass = 'expiry-na';
                $shelfLabel = '—';
                if ($shelfLife > 0) {
                    $shelfLabel = $shelfLife . ' ' . translate('Days');
                    $shelfClass = $shelfLife <= 14 ? 'expiry-danger' : ($shelfLife <= 30 ? 'expiry-warn' : 'expiry-ok');
                }
            ?>
            <div class="inline-val <?php echo e($shelfClass); ?>"
                onclick="editField(<?php echo e($item->id); ?>, 'expiry_days', <?php echo e($shelfLife); ?>, 'Min. Shelf Life (Days)')">
                <?php if($shelfLife > 0 && $shelfLife <= 14): ?> ⚠️ <?php elseif($shelfLife > 0 && $shelfLife <= 30): ?> ⏰ <?php endif; ?>
                <?php echo e($shelfLabel); ?>

                <span class="inline-edit-icon">✏️</span>
            </div>
        </td>
        <td><span class="<?php echo e($item->tax > 0 ? 'vat-active' : 'vat-zero'); ?>"><?php echo e((float) $item->tax); ?>%</span></td>
        <td>
            <?php if($item->status == 1 && $item->stock > 0): ?>
                <span class="badge b-active"><span class="b-dot"></span> <?php echo e(translate('Active')); ?></span>
            <?php elseif($item->stock == 0): ?>
                <span class="badge b-oos"><span class="b-dot"></span> <?php echo e(translate('Out_of_Stock')); ?></span>
            <?php else: ?>
                <span class="badge b-draft"><span class="b-dot"></span> <?php echo e(translate('Draft')); ?></span>
            <?php endif; ?>
        </td>
        <td>
            <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                <a title="<?php echo e(translate('messages.use_this_product_info')); ?>"
                    href="<?php echo e(route('admin.item.edit', ['id' => $item['id'], 'product_gellary' => true])); ?>" class="ra-btn"
                    style="background:var(--primary);color:#fff;border-color:var(--primary);font-weight:600;white-space:nowrap">
                    ✅ <?php echo e(translate('messages.use_this_product_info')); ?>

                </a>
                <a title="<?php echo e(translate('messages.edit_product')); ?>" href="<?php echo e(route('admin.item.edit', [$item['id']])); ?>"
                    class="ra-btn">✏️ Edit</a>
                <a title="<?php echo e(translate('messages.view_product')); ?>" href="<?php echo e(route('admin.item.view', [$item['id']])); ?>"
                    class="ra-btn text-info" style="color:var(--slate)!important">👁️</a>
                <a title="<?php echo e(translate('messages.delete_product')); ?>" href="javascript:"
                    onclick="form_alert('product-<?php echo e($item['id']); ?>','<?php echo e(translate('Want_to_delete_this_item')); ?>')"
                    class="ra-btn del">🗑️</a>
                <form action="<?php echo e(route('admin.item.delete', [$item['id']])); ?>" method="post" id="product-<?php echo e($item['id']); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('delete'); ?>
                </form>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="10" class="text-center p-5">
            <div class="empty-state">
                <img src="<?php echo e(asset('assets/admin/img/900x400/img1.jpg')); ?>"
                    style="width:120px; opacity:0.5; margin-bottom:15px;">
                <p style="color:var(--muted)"><?php echo e(translate('messages.no_data_found')); ?></p>
                <a href="<?php echo e(route('admin.item.add-new', ['module_id' => Config::get('module.current_module_id')])); ?>"
                    class="btn btn-sm btn-primary">
                    <i class="tio-add"></i> <?php echo e(translate('messages.add_new_product')); ?>

                </a>
            </div>
        </td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\product\partials\_gallery.blade.php ENDPATH**/ ?>