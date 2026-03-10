<div class="col-lg-12">
    <div class="price_wrapper">
        <div class="outline-wrapper">
            <div class="card shadow--card-2 border-0 bg-animate">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon mr-2"><i class="tio-dollar-outlined"></i></span>
                        <span><?php echo e(translate('Price_Information')); ?></span>
                    </h5>
                    <?php if(isset($openai_config) && data_get($openai_config, 'status') == 1): ?>
                        <button type="button"
                            class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 price_others_auto_fill"
                            id="price_others_auto_fill" data-route="<?php echo e(route('admin.product.price-others-auto-fill')); ?>"
                            data-error="<?php echo e(translate('Please provide an item name and description so the AI can generate a suitable data.')); ?>"
                            data-lang="en">
                            <div class="btn-svg-wrapper">
                                <img width="18" height="18" class=""
                                    src="<?php echo e(asset('public/assets/admin/img/svg/blink-right-small.svg')); ?>"
                                    alt="">
                            </div>
                            <span class="ai-text-animation d-none" role="status">
                                <?php echo e(translate('Just_a_second')); ?>

                            </span>
                            <span class="btn-text"><?php echo e(translate('Generate')); ?></span>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1"><?php echo e(translate('messages.Unit_Price')); ?>

                                    <?php echo e(\App\CentralLogics\Helpers::currency_symbol()); ?><span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                    </span></label>
                                <input type="number" id="unit_price" min="0" max="999999999999.999"
                                    step="0.001" value="<?php echo e($product?->price ?? (old('price') ?? 0)); ?>" name="price"
                                    class="form-control" placeholder="<?php echo e(translate('messages.Ex:_100')); ?>" required>
                            </div>
                        </div>


                        <?php if($productWiseTax): ?>
                            <div class="col-md-3">
                                <div class="form-group pickup-zone-tag mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlInput1"><?php echo e(translate('messages.Select Tax Rate')); ?>

                                    </label>
                                    <select name="tax_ids[]" id="" class="form-control multiple-select2"
                                        multiple="multiple" data-placeholder="<?php echo e(translate('--Select Tax Rate--')); ?>">
                                        <?php $__currentLoopData = $taxVats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxVat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option
                                                <?php echo e(isset($taxVatIds) && in_array($taxVat->id, $taxVatIds) ? 'selected' : ''); ?>

                                                value="<?php echo e($taxVat->id); ?>"> <?php echo e($taxVat->name); ?>

                                                (<?php echo e($taxVat->tax_rate); ?>%)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1"><?php echo e(translate('messages.discount_type')); ?>


                                </label>
                                <select name="discount_type" id="discount_type" class="form-control js-select2-custom">
                                    <option
                                        <?php echo e(isset($product) && $product->discount_type == 'percent' ? 'selected' : ''); ?>

                                        value="percent"><?php echo e(translate('messages.percent') . ' (%)'); ?></option>
                                    <option
                                        <?php echo e(isset($product) && $product->discount_type == 'amount' ? 'selected' : ''); ?>

                                        value="amount">
                                        <?php echo e(translate('messages.amount') . ' (' . \App\CentralLogics\Helpers::currency_symbol() . ')'); ?>

                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1"><?php echo e(translate('messages.discount')); ?>

                                    <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('messages.Required.')); ?>"> *
                                    </span>
                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('Currently_you_need_to_manage_discount_with_the_Restaurant.')); ?>">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="number" min="0" max="999999999999999"
                                    value="<?php echo e(isset($product) ? $product->discount : old('discount', 0)); ?>"
                                    id="discount" name="discount" class="form-control"
                                    placeholder="<?php echo e(translate('messages.Ex:_100')); ?> ">
                            </div>
                        </div>
                        <div class="col-md-3" id="maximum_cart_quantity">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="maximum_cart_quantity"><?php echo e(translate('messages.Maximum_Purchase_Quantity_Limit')); ?>

                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="<?php echo e(translate('If_this_limit_is_exceeded,_customers_can_not_buy_the_food_in_a_single_purchase.')); ?>">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="number"
                                    value="<?php echo e(isset($product) ? $product->maximum_cart_quantity : old('maximum_cart_quantity')); ?>"
                                    placeholder="<?php echo e(translate('messages.Ex:_10')); ?>" class="form-control"
                                    name="maximum_cart_quantity" min="0" id="cart_quantity">
                            </div>
                        </div>

                        <?php if(Config::get('module.current_module_type') != 'food'): ?>
                            <div class="col-sm-6 col-lg-3" id="stock_input">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="total_stock"><?php echo e(translate('messages.total_stock')); ?></label>
                                    <input type="number" class="form-control" name="current_stock" min="0"
                                        value="<?php echo e(isset($product) ? $product->stock : ''); ?>" id="quantity">
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="sku"><?php echo e(translate('messages.sku')); ?></label>
                                <input type="text" class="form-control" name="sku" value="<?php echo e($product?->sku ?? old('sku')); ?>" id="sku" placeholder="<?php echo e(translate('Ex: SKU-123')); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="ean"><?php echo e(translate('messages.ean')); ?></label>
                                <input type="text" class="form-control" name="ean" value="<?php echo e($product?->ean ?? old('ean')); ?>" id="ean" placeholder="<?php echo e(translate('Ex: 1234567890123')); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="cost_price"><?php echo e(translate('messages.cost_price')); ?></label>
                                <input type="number" step="0.01" class="form-control" name="cost_price" value="<?php echo e($product?->cost_price ?? old('cost_price', 0)); ?>" id="cost_price" placeholder="<?php echo e(translate('Ex: 80')); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="expiry_days"><?php echo e(translate('messages.expiry_days')); ?></label>
                                <input type="number" class="form-control" name="expiry_days" value="<?php echo e($product?->expiry_days ?? old('expiry_days')); ?>" id="expiry_days" placeholder="<?php echo e(translate('Ex: 365')); ?>">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="discount_expires_at"><?php echo e(translate('messages.discount_expires_at')); ?></label>
                                <input type="datetime-local" class="form-control" name="discount_expires_at" value="<?php echo e(isset($product->discount_expires_at) ? \Carbon\Carbon::parse($product->discount_expires_at)->format('Y-m-d\TH:i') : old('discount_expires_at')); ?>" id="discount_expires_at">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views/admin-views/product/partials/_price_and_stock.blade.php ENDPATH**/ ?>