<div class="col-lg-12">
    <div class="price_wrapper">
        <div class="outline-wrapper">
            <div class="card shadow--card-2 border-0 bg-animate">
                <div class="card-header">
                    <h5 class="card-title">
                        <span class="card-header-icon mr-2"><i class="tio-dollar-outlined"></i></span>
                        <span>{{ translate('Price_Information') }}</span>
                    </h5>
                    @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                        <button type="button"
                            class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 price_others_auto_fill"
                            id="price_others_auto_fill" data-route="{{ route('admin.product.price-others-auto-fill') }}"
                            data-error="{{ translate('Please provide an item name and description so the AI can generate a suitable data.') }}"
                            data-lang="en">
                            <div class="btn-svg-wrapper">
                                <img width="18" height="18" class=""
                                    src="{{ asset('assets/admin/img/svg/blink-right-small.svg') }}"
                                    alt="">
                            </div>
                            <span class="ai-text-animation d-none" role="status">
                                {{ translate('Just_a_second') }}
                            </span>
                            <span class="btn-text">{{ translate('Generate') }}</span>
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.Unit_Price') }}
                                    {{ \App\CentralLogics\Helpers::currency_symbol() }}<span
                                        class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                    </span></label>
                                <input type="number" id="unit_price" min="0" max="999999999999.999"
                                    step="0.001" value="{{ $product?->price ?? (old('price') ?? 0) }}" name="price"
                                    class="form-control" placeholder="{{ translate('messages.Ex:_100') }}" required>
                            </div>
                        </div>


                        @if ($productWiseTax)
                            <div class="col-md-3">
                                <div class="form-group pickup-zone-tag mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ translate('messages.Select Tax Rate') }}
                                    </label>
                                    <select name="tax_ids[]" id="" class="form-control multiple-select2"
                                        multiple="multiple" data-placeholder="{{ translate('--Select Tax Rate--') }}">
                                        @foreach ($taxVats as $taxVat)
                                            <option
                                                {{ isset($taxVatIds) && in_array($taxVat->id, $taxVatIds) ? 'selected' : '' }}
                                                value="{{ $taxVat->id }}"> {{ $taxVat->name }}
                                                ({{ $taxVat->tax_rate }}%)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.discount_type') }}

                                </label>
                                <select name="discount_type" id="discount_type" class="form-control js-select2-custom">
                                    <option
                                        {{ isset($product) && $product->discount_type == 'percent' ? 'selected' : '' }}
                                        value="percent">{{ translate('messages.percent') . ' (%)' }}</option>
                                    <option
                                        {{ isset($product) && $product->discount_type == 'amount' ? 'selected' : '' }}
                                        value="amount">
                                        {{ translate('messages.amount') . ' (' . \App\CentralLogics\Helpers::currency_symbol() . ')' }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="exampleFormControlInput1">{{ translate('messages.discount') }}
                                    <span class="form-label-secondary text-danger" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('messages.Required.') }}"> *
                                    </span>
                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Currently_you_need_to_manage_discount_with_the_Restaurant.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="number" min="0" max="999999999999999"
                                    value="{{ isset($product) ? $product->discount : old('discount', 0) }}"
                                    id="discount" name="discount" class="form-control"
                                    placeholder="{{ translate('messages.Ex:_100') }} ">
                            </div>
                        </div>
                        <div class="col-md-3" id="maximum_cart_quantity">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label"
                                    for="maximum_cart_quantity">{{ translate('messages.Maximum_Purchase_Quantity_Limit') }}
                                    <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('If_this_limit_is_exceeded,_customers_can_not_buy_the_food_in_a_single_purchase.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </label>
                                <input type="number"
                                    value="{{ isset($product) ? $product->maximum_cart_quantity : old('maximum_cart_quantity') }}"
                                    placeholder="{{ translate('messages.Ex:_10') }}" class="form-control"
                                    name="maximum_cart_quantity" min="0" id="cart_quantity">
                            </div>
                        </div>

                        @if (Config::get('module.current_module_type') != 'food')
                            <div class="col-sm-6 col-lg-3" id="stock_input">
                                <div class="form-group mb-0 error-wrapper">
                                    <label class="input-label"
                                        for="total_stock">{{ translate('messages.total_stock') }}</label>
                                    <input type="number" class="form-control" name="current_stock" min="0"
                                        value="{{ isset($product) ? $product->stock : '' }}" id="quantity">
                                </div>
                            </div>
                        @endif


                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="sku">{{ translate('messages.sku') }}</label>
                                <input type="text" class="form-control" name="sku" value="{{ $product?->sku ?? old('sku') }}" id="sku" placeholder="{{ translate('Ex: SKU-123') }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="ean">{{ translate('messages.ean') }}</label>
                                <input type="text" class="form-control" name="ean" value="{{ $product?->ean ?? old('ean') }}" id="ean" placeholder="{{ translate('Ex: 1234567890123') }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="cost_price">{{ translate('messages.cost_price') }}</label>
                                <input type="number" step="0.01" class="form-control" name="cost_price" value="{{ $product?->cost_price ?? old('cost_price', 0) }}" id="cost_price" placeholder="{{ translate('Ex: 80') }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="expiry_days">{{ translate('messages.expiry_days') }}</label>
                                <input type="number" class="form-control" name="expiry_days" value="{{ $product?->expiry_days ?? old('expiry_days') }}" id="expiry_days" placeholder="{{ translate('Ex: 365') }}">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="form-group mb-0 error-wrapper">
                                <label class="input-label" for="discount_expires_at">{{ translate('messages.discount_expires_at') }}</label>
                                <input type="datetime-local" class="form-control" name="discount_expires_at" value="{{ isset($product->discount_expires_at) ? \Carbon\Carbon::parse($product->discount_expires_at)->format('Y-m-d\TH:i') : old('discount_expires_at') }}" id="discount_expires_at">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
