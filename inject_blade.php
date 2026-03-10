<?php
$index_new = file_get_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php');

// Define replacements
$basic_info_blade = <<<BLADE
                    @php(\$language = \App\CentralLogics\Helpers::get_business_settings('language'))
                   @php(\$product = isset(\$product) ? \$product : null)
                   <div class="js-nav-scroller hs-nav-scroller-horizontal">
                       <ul class="nav nav-tabs mb-4">
                           <li class="nav-item">
                               <a class="nav-link lang_link active" href="#"
                                   id="default-link">{{ translate('Default') }}</a>
                           </li>
                           @foreach (\$language ?? [] as \$lang)
                               <li class="nav-item">
                                   <a class="nav-link lang_link " href="#"
                                       id="{{ \$lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name(\$lang) . '(' . strtoupper(\$lang) . ')' }}</a>
                               </li>
                           @endforeach
                       </ul>
                   </div>

                   <div class="lang_form" id="default-form">
                       <div class="form-group">
                           <div class="justify-content-between d-flex">
                               <label class="input-label" for="default_name">{{ translate('messages.name') }}
                                   ({{ translate('Default') }}) <span class="form-label-secondary text-danger"
                                       data-toggle="tooltip" data-placement="right"
                                       data-original-title="{{ translate('messages.Required.') }}"> *
                                   </span>
                               </label>
                            @if (isset(\$openai_config) && data_get(\$openai_config, 'status') == 1)
                            <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 auto_fill_title"
                                id="title-default-action-btn" data-type="default"
                                data-error="{{ translate('Please provide a product name so the AI can generate a suitable title.') }}"
                                data-lang="{{ \App\CentralLogics\Helpers::system_default_language() }}"
                                data-route="{{ route('admin.product.title-auto-fill') }}">
                                <div class="btn-svg-wrapper">
                                    <img width="18" height="18" class=""
                                        src="{{ asset('public/assets/admin/img/svg/blink-right-small.svg') }}" alt="">
                                </div>
                                <span class="ai-text-animation d-none" role="status">
                                    {{ translate('Just_a_second') }}
                                </span>
                                <span class="btn-text">{{ translate('Generate') }}</span>
                            </button>
                            @endif

                           </div>
                           <div class="error-wrapper">
                               <div class="outline-wrapper">
                                   <!-- Changed class to task2 input style -->
                                   <input type="text" name="name[]" id="default_name" 
                                       value="{{ \$product?->getRawOriginal('name') ?? old('name.0') }}"
                                       placeholder="{{ translate('messages.new_food') }}" required>
                               </div>
                           </div>
                       </div>
                       <input type="hidden" name="lang[]" value="default">
                       <div class="form-group mb-0 des_wrapper mt-3">

                           <div class="justify-content-between d-flex">
                               <label class="input-label"
                                   for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                   ({{ translate('Default') }}) <span class="form-label-secondary text-danger"
                                       data-toggle="tooltip" data-placement="right"
                                       data-original-title="{{ translate('messages.Required.') }}"> *
                                   </span></label>

                                   @if (isset(\$openai_config) && data_get(\$openai_config, 'status') == 1)
                                   <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper p-0 mb-2 auto_fill_description"
                                       id="description-default-action-btn" data-type="default"
                                       data-error="{{ translate('Please provide a product description so the AI can generate a description.') }}"
                                       data-lang="{{ \App\CentralLogics\Helpers::system_default_language() }}"
                                       data-route="{{ route('admin.product.description-auto-fill') }}">
                                       <div class="btn-svg-wrapper">
                                            <img width="18" height="18" class=""
                                                src="{{ asset('public/assets/admin/img/svg/blink-right-small.svg') }}" alt="">
                                        </div>
                                        <span class="ai-text-animation d-none" role="status">
                                            {{ translate('Just_a_second') }}
                                        </span>
                                        <span class="btn-text">{{ translate('Generate') }}</span>
                                   </button>
                                   @endif
                           </div>

                           <div class="error-wrapper">
                               <div class="outline-wrapper">
                                    <textarea type="text" rows="5" name="description[]" maxlength="1200" id="description-default" class="ckeditor" required>{{ \$product?->getRawOriginal('description') ?? old('description.0') }}</textarea>
                               </div>
                           </div>
                       </div>
                   </div>

                   @foreach (\$language ?? [] as \$key => \$lang)
                       <?php

                       if (\$product && count(\$product['translations'])) {
                           \$translate = [];
                           foreach (\$product['translations'] as \$t) {
                               if (\$t->locale == \$lang && \$t->key == 'name') {
                                   \$translate[\$lang]['name'] = \$t->value;
                               }
                               if (\$t->locale == \$lang && \$t->key == 'description') {
                                   \$translate[\$lang]['description'] = \$t->value;
                               }
                           }
                       }
                       ?>

                       <div class="d-none lang_form" id="{{ \$lang }}-form">
                           <div class="form-group">
                               <div class="justify-content-between d-flex">
                                   <label class="input-label"
                                       for="{{ \$lang }}_name">{{ translate('messages.name') }}
                                       ({{ strtoupper(\$lang) }})
                                   </label>
                                @if (isset(\$openai_config) && data_get(\$openai_config, 'status') == 1)
                                <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper auto_fill_title"
                                    id="title-{{ \$lang }}-action-btn" data-lang="{{ \$lang }}"
                                    data-error="{{ translate('Please provide a product name so the AI can generate a suitable title or description.') }}"
                                    data-route="{{ route('admin.product.title-auto-fill') }}">
                                    <div class="btn-svg-wrapper">
                                        <img width="18" height="18" class=""
                                            src="{{ asset('public/assets/admin/img/svg/blink-right-small.svg') }}" alt="">
                                    </div>
                                    <span class="ai-text-animation d-none" role="status">
                                        {{ translate('Just_a_second') }}
                                    </span>
                                    <span class="btn-text">{{ translate('Generate') }}</span>
                                </button>
                                @endif
                               </div>
                               <div class="error-wrapper">
                                   <input type="text" name="name[]" id="{{ \$lang }}_name"
                                       value="{{ isset(\$translate[\$lang]['name']) ? \$translate[\$lang]['name'] : old('name.' . \$key + 1) }}"
                                        placeholder="{{ translate('messages.new_food') }}">
                               </div>
                           </div>
                           <input type="hidden" name="lang[]" value="{{ \$lang }}">
                           <div class="form-group mb-0 mt-3">
                               <div class="justify-content-between d-flex">
                                   <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                       ({{ strtoupper(\$lang) }})</label>
                                      @if (isset(\$openai_config) && data_get(\$openai_config, 'status') == 1)
                                      <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper auto_fill_description"
                                          id="description-default-action-btn"
                                          data-error="{{ translate('Please provide a product description so the AI can generate a description.') }}"
                                          data-lang="{{ \$lang }}"
                                          data-route="{{ route('admin.product.description-auto-fill') }}">
                                            <div class="btn-svg-wrapper">
                                                <img width="18" height="18" class=""
                                                    src="{{ asset('public/assets/admin/img/svg/blink-right-small.svg') }}" alt="">
                                            </div>
                                            <span class="ai-text-animation d-none" role="status">
                                                {{ translate('Just_a_second') }}
                                            </span>
                                            <span class="btn-text">{{ translate('Generate') }}</span>
                                      </button>
                                       @endif
                               </div>
                               <div class="error-wrapper">
                                   <textarea type="text" name="description[]" id="description-{{ \$lang }}" maxlength="1200"
                                       class="ckeditor min-height-154px">{{ isset(\$translate[\$lang]['description']) ? \$translate[\$lang]['description'] : old('description.' . \$key + 1) }}</textarea>
                               </div>
                           </div>
                       </div>
                   @endforeach
BLADE;

// We need to inject this into <div class="card-header"><span class="card-icon">📝</span><span class="card-title">Basic Information</span></div>
// Then inside its card-body.
$pattern = '/<div class="card-header"><span class="card-icon">📝<\/span><span class="card-title">Basic Information<\/span><\/div>.*?<div class="card-body">.*?<div class="form-row">/s';
$replacement = <<<REP
<div class="card-header"><span class="card-icon">📝</span><span class="card-title">Basic Information</span></div>
            <div class="card-body">
$basic_info_blade
              <div class="form-row mt-4">
REP;

$new_html = preg_replace($pattern, $replacement, $index_new);

if ($new_html !== null) {
    file_put_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php', $new_html);
    echo "Injected Basic Info successfully.\n";
} else {
    echo "Regex failed.\n";
}

// Next is Pricing
// We should replace the "Pricing" card-body with the logic from `_price_and_stock.blade.php`.
$pattern_price = '/<div class="card-header"><span class="card-icon">💰<\/span><span class="card-title">Pricing<\/span><\/div>.*?<div class="card-body">.*?<\/div>\s*<\/div>\s*<!--/s';
// Pricing in _price_and_stock is:
$price_blade = <<<BLADE
<div class="card-header"><span class="card-icon">💰</span><span class="card-title">Pricing & Stock</span></div>
<div class="card-body">
    <div class="form-row-3">
        <div class="form-group" style="margin-bottom:0">
            <label class="input-label">{{ translate('messages.price') }}</label>
            <div class="iw"><span class="ipfx">{{\App\CentralLogics\Helpers::currency_symbol()}}</span>
                <input type="number" name="price" id="regPrice" class="form-control"
                    placeholder="{{ translate('messages.Ex:') }} 100" step="0.01" min="0" required
                    value="{{ old('price') }}">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="input-label">{{ translate('messages.discount') }}</label>
            <div class="d-flex align-items-center">
                <input type="number" min="0" max="100000" id="discount" name="discount" class="form-control mr-2"
                    placeholder="{{ translate('messages.Ex:') }} 10" value="{{ old('discount') }}">
            </div>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="input-label">{{ translate('messages.discount_type') }}</label>
            <select name="discount_type" id="discount_type" class="form-control js-select2-custom">
                <option value="percent">{{ translate('messages.percent') }} (%)</option>
                <option value="amount">{{ translate('messages.amount') }} ({{\App\CentralLogics\Helpers::currency_symbol()}})</option>
            </select>
        </div>
    </div>
    <div class="divider"></div>
    <div class="form-row">
        <div class="form-group" style="margin-bottom:0">
            <label class="input-label">{{ translate('messages.maximum_cart_quantity') }}</label>
            <input type="number" name="maximum_cart_quantity" class="form-control"
                placeholder="{{ translate('messages.Ex:_10') }}" min="0">
        </div>
        <div class="form-group" style="margin-bottom:0" id="stock_input"
            {{ \App\CentralLogics\Helpers::get_mail_status('stock_limit') == 1 ? '' : 'style=display:none;' }}>
            <label class="input-label">{{ translate('messages.total_stock') }}</label>
            <input type="number" name="current_stock" id="quantity" class="form-control"
                placeholder="{{ translate('messages.Ex:_10') }}" {{ \App\CentralLogics\Helpers::get_mail_status('stock_limit') == 1 ? 'required' : '' }}>
        </div>
    </div>
</div>
</div>
<!--
BLADE;

$new_html = preg_replace($pattern_price, $price_blade, $new_html);

file_put_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php', $new_html);
echo "Injected Pricing successfully.\n";

// Categories injection
// Replaces the "Categories & Classification" card
$pattern_cat = '/<div class="card-header"><span class="card-icon">🗂️<\/span><span class="card-title">Categories &amp; Classification<\/span>.*?<div class="card-body">.*?<\/div>\s*<\/div>\s*<\/div>\s*<!-- RIGHT -->/s';

$cat_blade = <<<BLADE
<div class="card-header"><span class="card-icon">🗂️</span><span class="card-title">Categories & Classification</span></div>
<div class="card-body">
    <div class="form-row">
        <div class="form-group">
            <label class="input-label">{{ translate('messages.store') }} <span class="req">*</span></label>
            <select name="store_id" id="store_id" class="form-control"
                data-placeholder="{{ translate('messages.select_store') }}" required>
            </select>
        </div>
        <div class="form-group">
            <label class="input-label">{{ translate('messages.category') }} <span class="req">*</span></label>
            <select name="category_id" id="category_id" class="form-control" required
                onchange="getRequest('{{ url('/') }}/admin/item/get-categories?parent_id='+this.value,'sub-categories')">
                <option value="">{{ translate('messages.select_category') }}</option>
                @foreach (\$categories as \$category)
                    <option value="{{ \$category['id'] }}">{{ \$category['name'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="input-label">{{ translate('messages.sub_category') }} <span class="opt"
                    id="sub_category_req">(optional)</span>
            </label>
            <select name="sub_category_id" id="sub-categories" class="form-control">
                <option value="">{{ translate('messages.select_sub_category') }}</option>
            </select>
        </div>
    </div>
    
    @if (Config::get('module.current_module_type') == 'grocery' || Config::get('module.current_module_type') == 'ecommerce' || Config::get('module.current_module_type') == 'pharmacy')
        <div class="form-row">
            <div class="form-group">
                <label class="input-label">{{ translate('messages.item_type') }}</label>
                <select name="veg" id="veg" class="form-control">
                    <option value="0">{{ translate('messages.non_veg') }}</option>
                    <option value="1">{{ translate('messages.veg') }}</option>
                </select>
            </div>
            @if (Config::get('module.current_module_type') == 'pharmacy')
            <div class="form-group">
                <label class="input-label">{{ translate('messages.halal') }}</label>
                <select name="is_halal" class="form-control">
                    <option value="0">{{ translate('messages.non_halal') }}</option>
                    <option value="1">{{ translate('messages.halal') }}</option>
                </select>
            </div>
            @endif
        </div>
    @endif
</div>
</div>
</div>
<!-- RIGHT -->
BLADE;

$new_html = preg_replace($pattern_cat, $cat_blade, $new_html);

// Tags injection (already inside category logic normally, but let's append it where Tags were)
// We need the Store, Categories, Item Type (Veg/NonVeg), Halal
// Then we also need "Search Tags".
file_put_contents(__DIR__ . '/resources/views/admin-views/product/index_new.blade.php', $new_html);
echo "Injected Categories successfully.\n";
