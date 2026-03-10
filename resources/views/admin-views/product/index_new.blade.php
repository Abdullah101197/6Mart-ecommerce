@extends('layouts.admin.app')

@section('title', translate('messages.add_new_item'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('public/assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/admin/css/new-product.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid new-product-form">
        <!-- Page Header -->
        <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center mb-4">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/items.png') }}" class="w--22" alt="">
                </span>
                <span>{{ translate('messages.add_new_item') }}</span>
            </h1>
        </div>
        <!-- End Page Header -->

        <form id="item_form" enctype="multipart/form-data" class="custom-validation" data-ajax="true">
            <input type="hidden" id="request_type" value="admin">
            <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">
            
            <div class="tab-nav">
    <button class="tab-btn active" onclick="switchTab(this,'tab-general')">📝 General</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-attributes')">🔧 Attributes</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-nutrition')">🧪 Nutrition &amp; Allergens</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-variants')">🎨 Variants</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-seo')">🔍 SEO &amp; Meta</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-media')">🖼️ Media</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-amazon')">🛒 Amazon Platform</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-b2b')">🏭 B2B &amp; Wholesale</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-logistics')">✈️ Logistics &amp; Shipping</button>
    <button class="tab-btn" onclick="switchTab(this,'tab-reviews')">⭐ Reviews &amp; Analytics</button>
  </div>

  <div class="content">

    <!-- ══════════════════ TAB 1: GENERAL ══════════════════ -->
    <div class="tab-panel active" id="tab-general">
      <div class="grid">
        <div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📝</span><span class="card-title">Basic Information</span></div>
            <div class="card-body">
                    @php($language = \App\CentralLogics\Helpers::get_business_settings('language'))
                   @php($product = isset($product) ? $product : null)
                   <div class="js-nav-scroller hs-nav-scroller-horizontal">
                       <ul class="nav nav-tabs mb-4">
                           <li class="nav-item">
                               <a class="nav-link lang_link active" href="#"
                                   id="default-link">{{ translate('Default') }}</a>
                           </li>
                           @foreach ($language ?? [] as $lang)
                               <li class="nav-item">
                                   <a class="nav-link lang_link " href="#"
                                       id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
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
                            @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
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
                                       value="{{ $product?->getRawOriginal('name') ?? old('name.0') }}"
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

                                   @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
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
                                    <textarea type="text" rows="5" name="description[]" maxlength="1200" id="description-default" class="ckeditor" required>{{ $product?->getRawOriginal('description') ?? old('description.0') }}</textarea>
                               </div>
                           </div>
                       </div>
                   </div>

                   @foreach ($language ?? [] as $key => $lang)
                       <?php

                       if ($product && count($product['translations'])) {
                           $translate = [];
                           foreach ($product['translations'] as $t) {
                               if ($t->locale == $lang && $t->key == 'name') {
                                   $translate[$lang]['name'] = $t->value;
                               }
                               if ($t->locale == $lang && $t->key == 'description') {
                                   $translate[$lang]['description'] = $t->value;
                               }
                           }
                       }
                       ?>

                       <div class="d-none lang_form" id="{{ $lang }}-form">
                           <div class="form-group">
                               <div class="justify-content-between d-flex">
                                   <label class="input-label"
                                       for="{{ $lang }}_name">{{ translate('messages.name') }}
                                       ({{ strtoupper($lang) }})
                                   </label>
                                @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                                <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper auto_fill_title"
                                    id="title-{{ $lang }}-action-btn" data-lang="{{ $lang }}"
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
                                   <input type="text" name="name[]" id="{{ $lang }}_name"
                                       value="{{ isset($translate[$lang]['name']) ? $translate[$lang]['name'] : old('name.' . $key + 1) }}"
                                        placeholder="{{ translate('messages.new_food') }}">
                               </div>
                           </div>
                           <input type="hidden" name="lang[]" value="{{ $lang }}">
                           <div class="form-group mb-0 mt-3">
                               <div class="justify-content-between d-flex">
                                   <label class="input-label"
                                       for="exampleFormControlInput1">{{ translate('messages.short_description') }}
                                       ({{ strtoupper($lang) }})</label>
                                      @if (isset($openai_config) && data_get($openai_config, 'status') == 1)
                                      <button type="button" class="btn bg-white text-primary opacity-1 generate_btn_wrapper auto_fill_description"
                                          id="description-default-action-btn"
                                          data-error="{{ translate('Please provide a product description so the AI can generate a description.') }}"
                                          data-lang="{{ $lang }}"
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
                                   <textarea type="text" name="description[]" id="description-{{ $lang }}" maxlength="1200"
                                       class="ckeditor min-height-154px">{{ isset($translate[$lang]['description']) ? $translate[$lang]['description'] : old('description.' . $key + 1) }}</textarea>
                               </div>
                           </div>
                       </div>
                   @endforeach
              <div class="form-row mt-4">
                <div class="form-group" style="margin-bottom:0">
                  <label>Brand <span class="req">*</span></label>
                  <select name="meta_data[brand]" id="brand" onchange="updateQuality()">
                    <option value="">Select brand…</option>
                    <option>BEL: Kiri</option><option>Président</option><option>Philadelphia</option>
                    <option>Lurpak</option><option>Arla</option><option>Almarai</option><option>Other</option>
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Pack / Weight <span class="req">*</span></label>
                  <input type="text" name="meta_data[weight]" id="weight" placeholder="e.g. 864g, 2×24 portions" oninput="updateQuality()">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
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
<!-- ═══ ABOUT THIS ITEM (Amazon + Instacart) ═══ -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📋</span><span class="card-title">About This Item</span><span class="card-subtitle">Amazon &amp; Instacart format</span></div>
            <div class="card-body">
              <div class="info-box">📌 These bullet points power the <b>"About this item"</b> panel on Amazon and the feature list on Instacart product pages. Write clear, benefit-led statements that answer buyer questions instantly.</div>
              <div class="sec-head">Key Selling Points (up to 6)</div>
              <div id="aboutItemRows">
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. Made from 100% natural pasteurised cow's milk — no artificial preservatives or colours"><div class="hint">Lead with the strongest keyword. Focus on the benefit.</div></div></div>
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. 48 individually wrapped cream cheese portions — perfect for lunchboxes, snacks &amp; entertaining"></div></div>
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. Halal certified — suitable for the whole family, including children"></div></div>
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. Keep refrigerated 2–8 °C; once opened consume within 3 days"></div></div>
                <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. Rich &amp; creamy texture — ideal for spreading on bread, toast, crackers or using in recipes"></div></div>
                <div style="display:flex;gap:8px;align-items:flex-start"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" name="meta_data[about_item][]" placeholder="e.g. Country of Origin: Poland · Brand: BEL Group · Net Weight: 864g (2 × 432g)"></div></div>
              </div>
              <button class="btn-add" style="margin-top:10px" onclick="(function(){var r=document.createElement('div');r.style.cssText='display:flex;gap:8px;align-items:flex-start;margin-top:10px';r.innerHTML='<span style=\\'margin-top:10px;font-size:14px;flex-shrink:0\\'>✅</span><div style=\\'flex:1\\'><input type=\\'text\\' placeholder=\\'Add another selling point…\\' style=\\'width:100%\\'></div>';document.getElementById('aboutItemRows').appendChild(r)})()">+ Add Selling Point</button>
              <div class="divider"></div>
              <div class="form-group"><label>Product Overview (long-form) <span class="opt">Shown in "Product Description" section on Amazon A+</span></label><textarea name="meta_data[product_overview]" rows="4" placeholder="Write a 2–4 sentence paragraph expanding on what makes this product special — flavour profile, story, occasions, origin…"></textarea></div>
              <div class="form-group" style="margin-bottom:0"><label>Usage Directions / How to Use</label><textarea name="meta_data[usage_directions]" rows="2" placeholder="e.g. Spread directly from the individual portion on bread, toast or crackers. Ideal chilled. Can also be used in dips, sauces, and cheesecakes."></textarea></div>
            </div>
          </div>

          <!-- ═══ INSTACART LISTING ═══ -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🛒</span><span class="card-title">Instacart Listing Details</span><span class="card-subtitle">Storefront optimisation</span></div>
            <div class="card-body">
              <div class="info-box">🛒 Instacart pulls product data directly from your catalogue. Complete these fields to ensure your item appears in the right department, with accurate promo labels and filters.</div>
              <div class="form-row">
                <div class="form-group">
                  <label>Instacart Department</label>
                  <select name="meta_data[instacart_department]">
                    <option value="">— Select —</option>
                    <option selected>Dairy &amp; Eggs</option><option>Deli &amp; Charcuterie</option>
                    <option>Bakery &amp; Bread</option><option>Produce</option>
                    <option>Meat &amp; Seafood</option><option>Frozen Foods</option>
                    <option>Pantry &amp; Dry Goods</option><option>Beverages</option>
                    <option>Snacks &amp; Candy</option><option>Health &amp; Beauty</option>
                    <option>Baby &amp; Toddler</option><option>Household &amp; Cleaning</option>
                    <option>Pet Care</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Instacart Aisle / Shelf</label>
                  <input type="text" name="meta_data[instacart_aisle]" placeholder="e.g. Cream Cheese &amp; Soft Cheese">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Instacart Promo Label</label>
                  <select name="meta_data[instacart_promo_label]">
                    <option value="">None</option>
                    <option>🏷️ Sale</option><option>🆕 New Arrival</option>
                    <option>⭐ Staff Pick</option><option>🔥 Popular</option>
                    <option>💚 Organic</option><option>🌱 Plant Based</option>
                    <option>🎉 Limited Time Offer</option><option>💰 Best Value</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Instacart Unit Pricing Display</label>
                  <select name="meta_data[instacart_unit_pricing]">
                    <option>Per item</option><option>Per 100g</option><option>Per 100ml</option>
                    <option>Per kg</option><option>Per litre</option><option>Per oz</option>
                  </select>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Instacart Product Tags</label>
                <div class="tag-wrap" id="icTagWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-green">Halal <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-green">Portion packs <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-blue">Gluten-free <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-purple">Family size <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" name="meta_data[instacart_tags]" id="icTagInput" placeholder="Add filter tag, press Enter…" onkeydown="addTag(event,'icTagWrap','icTagInput','t-green')">
                </div>
                <div class="hint">Tags map to Instacart dietary filters (e.g. Organic, Vegan, Kosher, Gluten-Free)</div>
              </div>
            </div>
          </div>

          <div class="card">
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
                @foreach ($categories as $category)
                    <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
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
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">🚦</span><span class="card-title">Status</span></div>
            <div class="card-body">
              <div class="pill-row">
                <div class="pill sel" onclick="selStatus(this,false,false)"><span class="sdot"></span>Active</div>
                <div class="pill" onclick="selStatus(this,false,true)"><span class="sdot"></span>Out of Stock</div>
                <div class="pill" onclick="selStatus(this,true,false)"><span class="sdot"></span>Draft</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🌍</span><span class="card-title">Origin &amp; Seller</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Country of Origin <span class="req">*</span></label>
                <select>
                  <option value="">Select…</option>
                  <option value="PL" selected>🇵🇱 Poland</option><option value="FR">🇫🇷 France</option>
                  <option value="DE">🇩🇪 Germany</option><option value="NL">🇳🇱 Netherlands</option>
                  <option value="QA">🇶🇦 Qatar</option><option value="SA">🇸🇦 Saudi Arabia</option>
                  <option value="AE">🇦🇪 UAE</option><option value="US">🇺🇸 USA</option><option value="GB">🇬🇧 UK</option>
                </select>
              </div>
              <div class="form-group"><label>Manufacturer / Producer</label><input type="text" placeholder="e.g. Groupe Bel S.A."></div>
              <div class="form-group" style="margin-bottom:0"><label>Seller</label><select><option>Carrefour (Direct)</option><option>Third-Party Seller A</option><option>Third-Party Seller B</option></select></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🚚</span><span class="card-title">Delivery Options</span></div>
            <div class="card-body">
              <div class="dlv-grid">
                <div class="dlv-card sel" onclick="this.classList.toggle('sel')"><div class="dlv-icon">📅</div><div class="dlv-name">Scheduled</div><div class="dlv-time">Next day</div></div>
                <div class="dlv-card sel" onclick="this.classList.toggle('sel')"><div class="dlv-icon">⚡</div><div class="dlv-name">Express</div><div class="dlv-time">60–120 mins</div></div>
                <div class="dlv-card" onclick="this.classList.toggle('sel')"><div class="dlv-icon">🏎️</div><div class="dlv-name">Rapid</div><div class="dlv-time">35 mins</div></div>
                <div class="dlv-card" onclick="this.classList.toggle('sel')"><div class="dlv-icon">🏬</div><div class="dlv-name">Click &amp; Collect</div><div class="dlv-time">In-store</div></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📊</span><span class="card-title">Inventory</span></div>
            <div class="card-body">
              <div class="form-group"><label>SKU / Barcode</label><input type="text" placeholder="e.g. 646105" class="mono"></div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>Stock Qty</label><input type="number" placeholder="0" min="0"></div>
                <div class="form-group" style="margin-bottom:0"><label>Low Stock Alert</label><input type="number" placeholder="10" min="0"></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">👁️</span><span class="card-title">Visibility &amp; Features</span></div>
            <div class="card-body" style="padding:6px 20px 12px">
              <div class="trow"><div><div class="tlbl">Featured Product</div><div class="tdsc">Show in homepage carousel</div></div><label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label></div>
              <div class="trow"><div><div class="tlbl">Special Offer Badge</div><div class="tdsc">Display sale badge on listing</div></div><label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label></div>
              <div class="trow"><div><div class="tlbl">MyCLUB Points Eligible</div><div class="tdsc">Earn loyalty points</div></div><label class="tog"><input type="checkbox"><span class="tog-track"></span></label></div>
              <div class="trow"><div><div class="tlbl">Search Indexed</div><div class="tdsc">Appear in search results</div></div><label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">⭐</span><span class="card-title">Listing Quality Score</span></div>
            <div class="card-body">
              <div class="q-lbl"><span>Completeness</span><span id="qualityPct" style="font-weight:700;color:var(--red)">0%</span></div>
              <div class="q-bar"><div class="q-fill" id="qualityFill" style="width:0%"></div></div>
              <div id="qualityTips" style="margin-top:10px;font-size:11.5px;color:var(--muted);line-height:1.8">Complete required fields to improve quality.</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 2: ATTRIBUTES ══════════════════ -->
    <div class="tab-panel" id="tab-attributes">
      <div class="grid">
        <div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📐</span><span class="card-title">Physical Dimensions &amp; Weight</span></div>
            <div class="card-body">
              <div class="sec-head">Product Dimensions</div>
              <div class="form-row-3">
                <div class="form-group"><label>Length</label><div class="iw sfx"><input type="number" placeholder="0.0" step="0.1"><span class="isfx">cm</span></div></div>
                <div class="form-group"><label>Width</label><div class="iw sfx"><input type="number" placeholder="0.0" step="0.1"><span class="isfx">cm</span></div></div>
                <div class="form-group"><label>Height</label><div class="iw sfx"><input type="number" placeholder="0.0" step="0.1"><span class="isfx">cm</span></div></div>
              </div>
              <div class="sec-head">Weight</div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>Net Weight</label><div class="iw sfx"><input type="number" placeholder="0.00" step="0.01"><span class="isfx">g</span></div></div>
                <div class="form-group" style="margin-bottom:0"><label>Gross Weight (with packaging)</label><div class="iw sfx"><input type="number" placeholder="0.00" step="0.01"><span class="isfx">g</span></div></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📋</span><span class="card-title">Product Identifiers</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group"><label>EAN / GTIN Barcode</label><input type="text" name="meta_data[ean_barcode]" placeholder="e.g. 3073781039180" class="mono"></div>
                <div class="form-group"><label>Internal SKU</label><input type="text" name="meta_data[internal_sku]" placeholder="e.g. CQ-646105" class="mono"></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label>Manufacturer Part No. (MPN)</label><input type="text" name="meta_data[mpn]" placeholder="e.g. KIRI-48P-864" class="mono"></div>
                <div class="form-group"><label>Model / Item Number</label><input type="text" name="meta_data[model_number]" placeholder="e.g. KQ-2024-V1"></div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>HS Code (Customs)</label><input type="text" name="meta_data[hs_code]" placeholder="e.g. 0406.10" class="mono"></div>
                <div class="form-group" style="margin-bottom:0"><label>Country of Manufacture</label><select name="meta_data[country_of_manufacture]"><option>Select…</option><option selected>Poland</option><option>France</option><option>Germany</option><option>Qatar</option></select></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📦</span><span class="card-title">Packaging Specifications</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group"><label>Packaging Type</label><select name="meta_data[packaging_type]"><option value="">Select…</option><option>Cardboard Box</option><option>Plastic Tray</option><option>Vacuum Pack</option><option>Glass Jar</option><option>Tin Can</option><option>Pouch / Sachet</option><option>Bottle</option><option>Blister Pack</option><option>Resealable Bag</option></select></div>
                <div class="form-group"><label>Units per Pack</label><input type="number" name="meta_data[units_per_pack]" placeholder="e.g. 24"></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label>Packs per Carton</label><input type="number" name="meta_data[packs_per_carton]" placeholder="e.g. 12"></div>
                <div class="form-group"><label>Recyclable Packaging</label><select name="meta_data[recyclable_packaging]"><option>Select…</option><option>Yes — Fully Recyclable</option><option>Partially Recyclable</option><option>No</option></select></div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>Package Material</label><input type="text" name="meta_data[package_material]" placeholder="e.g. Coated paperboard, LDPE film"></div>
                <div class="form-group" style="margin-bottom:0"><label>Package Colour</label><input type="text" name="meta_data[package_colour]" placeholder="e.g. White &amp; Blue"></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🌡️</span><span class="card-title">Storage &amp; Shelf Life</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group"><label>Storage Type</label><select name="meta_data[storage_type]"><option>Select…</option><option>Ambient (Room Temp)</option><option selected>Refrigerated (2–8°C)</option><option>Frozen (−18°C or below)</option><option>Cool &amp; Dry</option><option>Keep in Dark Place</option></select></div>
                <div class="form-group"><label>Temperature Range</label><div class="iw sfx"><input type="text" name="meta_data[temperature_range]" placeholder="2 – 8"><span class="isfx">°C</span></div></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label>Shelf Life from Production</label><div class="iw sfx"><input type="number" name="meta_data[shelf_life]" placeholder="e.g. 180"><span class="isfx">days</span></div></div>
                <div class="form-group"><label>Min. Days on Delivery</label><div class="iw sfx"><input type="number" name="meta_data[min_days_delivery]" placeholder="e.g. 30"><span class="isfx">days</span></div></div>
              </div>
              <div class="form-group" style="margin-bottom:0"><label>Storage Instructions</label><textarea name="meta_data[storage_instructions]" rows="2" placeholder="e.g. Once opened, keep refrigerated and consume within 3 days."></textarea></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">✏️</span><span class="card-title">Custom Attributes</span><span class="card-subtitle">Key–Value pairs</span></div>
            <div class="card-body" style="padding-bottom:12px">
              <div class="info-box">💡 Add product-specific attributes shown in the specifications table on the product page.</div>
              <div id="customAttrs">
                <div class="form-row" style="margin-bottom:10px"><input type="text" placeholder="Attribute name (e.g. Fat Content)"><input type="text" placeholder="Value (e.g. 24g per 100g)"></div>
                <div class="form-row" style="margin-bottom:10px"><input type="text" placeholder="Attribute name (e.g. Texture)"><input type="text" placeholder="Value (e.g. Creamy & Smooth)"></div>
                <div class="form-row" style="margin-bottom:10px"><input type="text" placeholder="Attribute name (e.g. Portions per Pack)"><input type="text" placeholder="Value (e.g. 48 portions)"></div>
              </div>
              <button class="btn-add" onclick="addCustomAttr()">+ Add Attribute Row</button>
            </div>
          </div>
        </div>

        <!-- RIGHT ATTRIBUTES -->
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">🏷️</span><span class="card-title">Product Type</span></div>
            <div class="card-body">
              <div class="form-group"><label>Product Type</label><select name="meta_data[product_type]"><option>Simple Product</option><option>Variable Product</option><option>Bundle / Multipack</option><option>Digital Product</option></select></div>
              <div class="form-group"><label>Condition</label><select name="meta_data[condition]"><option>New</option><option>Refurbished</option><option>Used</option></select></div>
              <div class="form-group" style="margin-bottom:0"><label>Age Restriction</label><select name="meta_data[age_restriction]"><option>None</option><option>18+</option><option>21+</option></select></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📅</span><span class="card-title">Dates &amp; Lifecycle</span></div>
            <div class="card-body">
              <div class="form-group"><label>Product Launch Date</label><input type="date" name="meta_data[launch_date]"></div>
              <div class="form-group"><label>End-of-Life Date</label><input type="date" name="meta_data[end_of_life_date]"></div>
              <div class="form-group" style="margin-bottom:0"><label>Warranty Period</label><select name="meta_data[warranty_period]"><option value="">N/A</option><option>1 Month</option><option>3 Months</option><option>6 Months</option><option>1 Year</option><option>2 Years</option><option>3 Years</option></select></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">⚖️</span><span class="card-title">Compliance</span></div>
            <div class="card-body">
              <div class="form-group"><label>QFSSA Approval No.</label><input type="text" name="meta_data[qfssa_approval]" placeholder="e.g. QFSSA-2024-XXXX" class="mono"></div>
              <div class="form-group"><label>Import Permit No.</label><input type="text" name="meta_data[import_permit]" placeholder="e.g. MOC-IMP-XXXX" class="mono"></div>
              <div class="form-group" style="margin-bottom:0"><label>CE / Safety Marking</label><input type="text" name="meta_data[safety_marking]" placeholder="e.g. CE, FCC, ROHS"></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🏅</span><span class="card-title">Certifications</span></div>
            <div class="card-body">
              <div class="chk-grid" id="certChecks">
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____halal]" value="1" checked> 🟢 Halal</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____organic]" value="1"> 🌱 Organic</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____free_range]" value="1"> 🐄 Free Range</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____non_gmo]" value="1"> 🌿 Non-GMO</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____iso_22000]" value="1"> 🔬 ISO 22000</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____haccp]" value="1"> 📋 HACCP</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____msc_certified]" value="1"> 🐟 MSC Certified</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][____energy_star]" value="1"> ⚡ Energy Star</label>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">↩️</span><span class="card-title">Return Policy</span></div>
            <div class="card-body">
              <div class="form-group"><label>Returnable</label><select name="meta_data[returnable]"><option>Yes — Within 7 days</option><option>Yes — Within 14 days</option><option>Yes — Within 30 days</option><option>No — Non-returnable (perishable)</option></select></div>
              <div class="form-group" style="margin-bottom:0"><label>Return Conditions</label><textarea name="meta_data[return_conditions]" rows="2" placeholder="e.g. Original packaging, unopened, within expiry date."></textarea></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 3: NUTRITION & ALLERGENS ══════════════════ -->
    <div class="tab-panel" id="tab-nutrition">
      <div class="grid">
        <div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🧾</span><span class="card-title">Ingredients</span></div>
            <div class="card-body">
              <div class="form-group"><label>Ingredients List (English) <span class="req">*</span></label><textarea name="meta_data[ingredients_english]" rows="4" placeholder="e.g. Pasteurised cow's milk, cream, salt, lactic acid bacteria, acidity regulator (E330)…"></textarea></div>
              <div class="form-group" style="margin-bottom:0"><label>Ingredients List (Arabic)</label><textarea name="meta_data[ingredients_arabic]" rows="3" placeholder="قائمة المكونات بالعربي…" style="direction:rtl;text-align:right"></textarea></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🧪</span><span class="card-title">Nutrition Facts (per 100g / per serving)</span></div>
            <div class="card-body" style="padding:0">
              <table class="ntbl">
                <thead><tr><th>Nutrient</th><th>Per 100g</th><th>Per Serving</th><th>Unit</th></tr></thead>
                                <tbody>
                  <tr><td>Energy</td><td><input type="text" name="meta_data[nutrition][energy][per_100g]" placeholder="263"></td><td><input type="text" name="meta_data[nutrition][energy][per_serving]" placeholder="47"></td><td>kcal</td></tr>
                  <tr><td>Energy (kJ)</td><td><input type="text" name="meta_data[nutrition][energy_kj][per_100g]" placeholder="1099"></td><td><input type="text" name="meta_data[nutrition][energy_kj][per_serving]" placeholder="198"></td><td>kJ</td></tr>
                  <tr><td>Total Fat</td><td><input type="text" name="meta_data[nutrition][fat][per_100g]" placeholder="24"></td><td><input type="text" name="meta_data[nutrition][fat][per_serving]"  placeholder="4.3"></td><td>g</td></tr>
                  <tr><td class="ind">Saturated Fat</td><td><input type="text" name="meta_data[nutrition][sat_fat][per_100g]" placeholder="15"></td><td><input type="text" name="meta_data[nutrition][sat_fat][per_serving]" placeholder="2.7"></td><td>g</td></tr>
                  <tr><td class="ind">Trans Fat</td><td><input type="text" name="meta_data[nutrition][trans_fat][per_100g]" placeholder="0"></td><td><input type="text" name="meta_data[nutrition][trans_fat][per_serving]" placeholder="0"></td><td>g</td></tr>
                  <tr><td class="ind">Monounsaturated Fat</td><td><input type="text" name="meta_data[nutrition][mono_fat][per_100g]" placeholder="6.5"></td><td><input type="text" name="meta_data[nutrition][mono_fat][per_serving]" placeholder="1.2"></td><td>g</td></tr>
                  <tr><td class="ind">Polyunsaturated Fat</td><td><input type="text" name="meta_data[nutrition][poly_fat][per_100g]" placeholder="0.8"></td><td><input type="text" name="meta_data[nutrition][poly_fat][per_serving]" placeholder="0.1"></td><td>g</td></tr>
                  <tr><td>Total Carbohydrates</td><td><input type="text" name="meta_data[nutrition][carbs][per_100g]" placeholder="4.5"></td><td><input type="text" name="meta_data[nutrition][carbs][per_serving]" placeholder="0.8"></td><td>g</td></tr>
                  <tr><td class="ind">Total Sugars</td><td><input type="text" name="meta_data[nutrition][sugars][per_100g]" placeholder="2.2"></td><td><input type="text" name="meta_data[nutrition][sugars][per_serving]" placeholder="0.4"></td><td>g</td></tr>
                  <tr><td class="ind">Added Sugars</td><td><input type="text" name="meta_data[nutrition][added_sugars][per_100g]" placeholder="0"></td><td><input type="text" name="meta_data[nutrition][added_sugars][per_serving]" placeholder="0"></td><td>g</td></tr>
                  <tr><td class="ind">Dietary Fibre</td><td><input type="text" name="meta_data[nutrition][fibre][per_100g]" placeholder="0"></td><td><input type="text"  name="meta_data[nutrition][fibre][per_serving]" placeholder="0"></td><td>g</td></tr>
                  <tr><td>Protein</td><td><input type="text" name="meta_data[nutrition][protein][per_100g]" placeholder="7.8"></td><td><input type="text" name="meta_data[nutrition][protein][per_serving]" placeholder="1.4"></td><td>g</td></tr>
                  <tr><td>Salt</td><td><input type="text" name="meta_data[nutrition][salt][per_100g]" placeholder="1.2"></td><td><input type="text" name="meta_data[nutrition][salt][per_serving]" placeholder="0.2"></td><td>g</td></tr>
                  <tr><td class="ind">Sodium</td><td><input type="text" name="meta_data[nutrition][sodium][per_100g]" placeholder="0.47"></td><td><input type="text" name="meta_data[nutrition][sodium][per_serving]" placeholder="0.08"></td><td>g</td></tr>
                  <tr><td>Calcium</td><td><input type="text" name="meta_data[nutrition][calcium][per_100g]" placeholder="100"></td><td><input type="text" name="meta_data[nutrition][calcium][per_serving]" placeholder="18"></td><td>mg</td></tr>
                  <tr><td>Vitamin A</td><td><input type="text" name="meta_data[nutrition][vit_a][per_100g]" placeholder="180"></td><td><input type="text" name="meta_data[nutrition][vit_a][per_serving]" placeholder="32"></td><td>µg</td></tr>
                  <tr><td>Vitamin D</td><td><input type="text" name="meta_data[nutrition][vit_d][per_100g]" placeholder="0"></td><td><input type="text" name="meta_data[nutrition][vit_d][per_serving]" placeholder="0"></td><td>µg</td></tr>
                  <tr><td>Cholesterol</td><td><input type="text" name="meta_data[nutrition][cholesterol][per_100g]" placeholder="70"></td><td><input type="text" name="meta_data[nutrition][cholesterol][per_serving]" placeholder="13"></td><td>mg</td></tr>
                  <tr><td>Iron</td><td><input type="text" name="meta_data[nutrition][iron][per_100g]" placeholder="0.1"></td><td><input type="text" name="meta_data[nutrition][iron][per_serving]" placeholder="0"></td><td>mg</td></tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🍽️</span><span class="card-title">Serving Information</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>Serving Size</label><div class="iw sfx"><input type="number" name="meta_data[serving_size]" placeholder="18" step="0.5"><span class="isfx">g</span></div></div>
                <div class="form-group" style="margin-bottom:0"><label>Servings per Container</label><input type="number" name="meta_data[servings_per_container]" placeholder="48"></div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT NUTRITION -->
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">⚠️</span><span class="card-title">Allergens (EU 14 + GCC)</span></div>
            <div class="card-body">
              <div class="sec-head">Contains</div>
              <div class="chk-grid">
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____milk]" value="1" checked> 🥛 Milk</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____gluten_wheat]" value="1"> 🌾 Gluten/Wheat</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____eggs]" value="1"> 🥚 Eggs</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____fish]" value="1"> 🐟 Fish</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____crustaceans]" value="1"> 🦐 Crustaceans</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____peanuts]" value="1"> 🥜 Peanuts</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____tree_nuts]" value="1"> 🌰 Tree Nuts</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____soybeans]" value="1"> 🫘 Soybeans</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____sesame]" value="1"> 🌱 Sesame</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____mustard]" value="1"> 🌻 Mustard</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____celery]" value="1"> 🥬 Celery</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____sulphites]" value="1"> 🍇 Sulphites</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____molluscs]" value="1"> 🦑 Molluscs</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____lupin]" value="1"> 🫘 Lupin</label>
              </div>
              <div class="sec-head" style="margin-top:14px">May Contain (Cross-contamination)</div>
              <div class="tag-wrap" id="mcWrap" onclick="this.querySelector('input').focus()">
                <input type="text" id="mcInput" placeholder="e.g. Tree Nuts, Soy… press Enter" onkeydown="addTag(event,'mcWrap','mcInput','t-orange')">
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🥗</span><span class="card-title">Dietary Information</span></div>
            <div class="card-body">
              <div class="chk-grid">
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][____halal]" value="1" checked> ✅ Halal</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_______kosher]" value="1"> ✡️ Kosher</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____vegetarian]" value="1"> 🌱 Vegetarian</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____vegan]" value="1"> 🌿 Vegan</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_________gluten_free]" value="1"> 🚫🌾 Gluten-Free</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_________dairy_free]" value="1"> 🚫🥛 Dairy-Free</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____low_sugar]" value="1"> 🍬 Low Sugar</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____low_salt]" value="1"> 🧂 Low Salt</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____high_protein]" value="1"> 🔥 High Protein</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____no_preservatives]" value="1"> 🫀 No Preservatives</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____whole_grain]" value="1"> 🌾 Whole Grain</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____sugar_free]" value="1"> 🍭 Sugar-Free</label>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🔬</span><span class="card-title">Additives &amp; E-Numbers</span></div>
            <div class="card-body">
              <div class="tag-wrap" id="eNumWrap" onclick="this.querySelector('input').focus()">
                <span class="tag t-blue">E330 <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                <input type="text" id="eNumInput" placeholder="Add E-number, press Enter…" onkeydown="addTag(event,'eNumWrap','eNumInput','t-blue')">
              </div>
              <div class="hint" style="margin-top:6px">E.g. E330 (Citric Acid), E471 (Emulsifier)</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 4: VARIANTS ══════════════════ -->
    <div class="tab-panel" id="tab-variants">
      <div class="grid">
        <div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🎨</span><span class="card-title">Colour / Flavour Variants</span></div>
            <div class="card-body">
              <div class="form-group"><label>Variant Type</label><select><option>Flavour</option><option>Colour</option><option>Scent</option><option>Style</option></select></div>
              <div class="form-group">
                <label>Colour Palette</label>
                <div class="swatches">
                  <div class="swatch sel" style="background:#e2001a" title="Red" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#2563eb" title="Blue" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#16a34a" title="Green" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#f59e0b" title="Amber" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#7c3aed" title="Purple" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#0d1b2a" title="Black" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#f9fafb;border:1.5px solid #d1d5db" title="White" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#d97706" title="Orange" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#ec4899" title="Pink" onclick="this.classList.toggle('sel')"></div>
                  <div class="swatch" style="background:#9ca3af" title="Grey" onclick="this.classList.toggle('sel')"></div>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Custom Flavour / Colour Names</label>
                <div class="tag-wrap" id="flavWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-green">Original <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-green">Light <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" id="flavInput" placeholder="Add variant, press Enter…" onkeydown="addTag(event,'flavWrap','flavInput','t-green')">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📏</span><span class="card-title">Size / Weight Variants</span></div>
            <div class="card-body">
              <div class="form-group" style="margin-bottom:0">
                <label>Available Sizes / Weights</label>
                <div class="tag-wrap" id="sizeWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-purple">48 portions / 864g <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-purple">24 portions / 432g <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" id="sizeInput" placeholder="Add size/weight, press Enter…" onkeydown="addTag(event,'sizeWrap','sizeInput','t-purple')">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📊</span><span class="card-title">Variant SKU Matrix</span><span class="card-subtitle">Per variant pricing &amp; stock</span></div>
            <div class="card-body" style="padding:0">
              <table class="vtbl">
                <thead><tr><th>Variant</th><th>SKU</th><th>Price (QAR)</th><th>Stock</th><th>Status</th><th></th></tr></thead>
                <tbody id="variantBody">
                  <tr>
                    <td><input type="text" value="Original / 864g"></td>
                    <td><input type="text" value="646105-OR-864" class="mono"></td>
                    <td><input type="number" value="35.00" step="0.01"></td>
                    <td><input type="number" value="120"></td>
                    <td><select style="font-size:11.5px;padding:5px 28px 5px 8px"><option>Active</option><option>Out of Stock</option><option>Disabled</option></select></td>
                    <td><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                  <tr>
                    <td><input type="text" value="Light / 432g"></td>
                    <td><input type="text" value="646105-LT-432" class="mono"></td>
                    <td><input type="number" value="22.00" step="0.01"></td>
                    <td><input type="number" value="85"></td>
                    <td><select style="font-size:11.5px;padding:5px 28px 5px 8px"><option>Active</option><option>Out of Stock</option><option>Disabled</option></select></td>
                    <td><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                </tbody>
              </table>
              <div style="padding:12px 16px"><button class="btn-add" onclick="addVariantRow()">+ Add Variant Row</button></div>
            </div>
          </div>

        </div>

        <!-- RIGHT VARIANTS -->
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">🔗</span><span class="card-title">Related Products</span></div>
            <div class="card-body">
              <div class="form-group"><label>Upsell Products <span class="opt">(shown on product page)</span></label><input type="text" placeholder="Search by name or SKU…"></div>
              <div class="form-group" style="margin-bottom:0"><label>Cross-Sell Products <span class="opt">(shown in cart)</span></label><input type="text" placeholder="Search by name or SKU…"></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🎁</span><span class="card-title">Bundle / Multipack</span></div>
            <div class="card-body">
              <div class="trow" style="padding-top:0">
                <div><div class="tlbl">Is Bundle / Multipack</div><div class="tdsc">Contains multiple units</div></div>
                <label class="tog"><input type="checkbox" id="bundleToggle" onchange="document.getElementById('bundleF').style.display=this.checked?'block':'none'"><span class="tog-track"></span></label>
              </div>
              <div id="bundleF" style="display:none;margin-top:14px">
                <div class="form-group"><label>Bundle Components</label><textarea rows="2" placeholder="e.g. 2× Kiri Cheese 24 portions pack"></textarea></div>
                <div class="form-group" style="margin-bottom:0"><label>Bundle Discount %</label><div class="iw sfx"><input type="number" placeholder="10" min="0" max="100"><span class="isfx">%</span></div></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🔢</span><span class="card-title">Purchase Limits</span></div>
            <div class="card-body">
              <div class="form-group"><label>Min. Order Quantity</label><input type="number" placeholder="1" min="1"></div>
              <div class="form-group" style="margin-bottom:0"><label>Max. Order Quantity per Customer</label><input type="number" placeholder="10" min="1"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 5: SEO & META ══════════════════ -->
    <div class="tab-panel" id="tab-seo">
      <div class="grid">
        <div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🔍</span><span class="card-title">Google Search Preview</span></div>
            <div class="card-body">
              <div class="seo-preview">
                <div class="seo-url" id="seoUrl">https://carrefourqatar.com/p/your-product-slug</div>
                <div class="seo-title" id="seoTitle">Product Name — Carrefour Qatar</div>
                <div class="seo-desc" id="seoDesc">Add a meta description to preview it here…</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">✍️</span><span class="card-title">SEO Details</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>SEO Title <span class="req">*</span></label>
                <input type="text" id="seoTitleInp" placeholder="e.g. Kiri Cream Cheese 864g — Buy Online | Carrefour Qatar" oninput="updateSEO()">
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="hint">Recommended: 50–60 characters</span>
                  <span id="seoTitleCC" class="cc cc-ok">0 chars</span>
                </div>
              </div>
              <div class="form-group">
                <label>Meta Description <span class="req">*</span></label>
                <textarea id="seoDescInp" rows="3" placeholder="Buy Kiri Spreadable Cream Cheese 48 portions 864g online. Fast delivery in Qatar. Special offer." oninput="updateSEO()"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="hint">Recommended: 150–160 characters</span>
                  <span id="seoDescCC" class="cc cc-ok">0 chars</span>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>URL Slug</label>
                <div class="iw"><span class="ipfx" style="font-size:11px">/p/</span><input type="text" id="seoSlug" placeholder="kiri-spreadable-cream-cheese-864g" class="mono" oninput="updateSEO()"></div>
                <div class="hint">Auto-generated from product name. Lowercase, hyphens only.</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🗃️</span><span class="card-title">Structured Data / Schema</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group"><label>GTIN (for Google Shopping)</label><input type="text" name="meta_data[ean_barcode]" placeholder="e.g. 3073781039180" class="mono"></div>
                <div class="form-group"><label>Google Product Category</label><input type="text" placeholder="e.g. Food &amp; Drink > Dairy > Cheese"></div>
              </div>
              <div class="form-group" style="margin-bottom:0"><label>Schema Type</label><select><option>Product</option><option>FoodProduct</option><option>IndividualProduct</option></select></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">📣</span><span class="card-title">Social Meta (OG Tags)</span></div>
            <div class="card-body">
              <div class="form-group"><label>OG Title</label><input type="text" placeholder="e.g. Kiri Cheese 864g — Carrefour Qatar"></div>
              <div class="form-group"><label>OG Description</label><textarea rows="2" placeholder="Description for social media sharing…"></textarea></div>
              <div class="form-group" style="margin-bottom:0"><label>OG Image URL</label><input type="url" placeholder="https://cdn.example.com/kiri-og.jpg"></div>
            </div>
          </div>
        </div>

        <!-- RIGHT SEO -->
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">🏷️</span><span class="card-title">SEO Keywords</span></div>
            <div class="card-body">
              <div class="form-group"><label>Focus Keyword</label><input type="text" placeholder="e.g. kiri cheese qatar"></div>
              <div class="form-group" style="margin-bottom:0">
                <label>Additional Keywords</label>
                <div class="tag-wrap" id="seoKWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-blue">kiri cheese <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-blue">cream cheese qatar <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" id="seoKInput" placeholder="Add keyword, press Enter…" onkeydown="addTag(event,'seoKWrap','seoKInput','t-blue')">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">🤖</span><span class="card-title">Robots &amp; Indexing</span></div>
            <div class="card-body" style="padding:6px 20px 12px">
              <div class="trow" style="padding-top:0">
                <div><div class="tlbl">Index this page</div><div class="tdsc">Allow search engines to index</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Follow Links</div><div class="tdsc">Allow crawlers to follow</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Canonical URL</div><div class="tdsc">Set as canonical version</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 6: MEDIA ══════════════════ -->
    <div class="tab-panel" id="tab-media">
<div class="grid">
<div class="col-lg-12">
    <div class="card h-100">
        <div class="card-header"><span class="card-icon">📸</span><span class="card-title">Media & Assets</span></div>
        <div class="card-body d-flex flex-wrap align-items-center">
            <div class="w-100 d-flex gap-3 flex-wrap flex-lg-nowrap">
                <div class="flex-grow-1 mx-auto overflow-x-auto scrollbar-primary">
                    <label class="text-dark d-block mb-4 mb-xl-5">
                        {{ translate('messages.item_image') }}
                        <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                    </label>
                    <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2" id="coba"></div>
                </div>

                <div class="flex-grow-1 mx-auto pb-2 flex-shrink-0">
                    <label class="text-dark d-block mb-4 mb-xl-5">
                        {{ translate('messages.item_thumbnail') }}
                        @if (Config::get('module.current_module_type') == 'food')
                            <small class="">( {{ translate('messages.ratio') }} 1:1 )</small>
                        @else
                            <small class="text-danger">* ( {{ translate('messages.ratio') }} 1:1 )</small>
                        @endif
                    </label>
                    <label class="d-inline-block m-0 position-relative error-wrapper">
                        <img class="img--176 border" id="viewer"
                            src="{{ asset('public/assets/admin/img/upload-img.png') }}" style="width:176px;height:176px;object-fit:cover" alt="thumbnail" />
                        <div class="icon-file-group">
                            <div class="icon-file"><input type="file" name="image" id="customFileEg1"
                                    class="custom-file-input d-none"
                                    accept=".webp, .jpg, .png, .webp, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<!-- RIGHT MEDIA -->
        <div>
          <div class="card">
            <div class="card-header"><span class="card-icon">📐</span><span class="card-title">Image Guidelines</span></div>
            <div class="card-body">
              <div style="font-size:12.5px;color:var(--slate);line-height:2">
                <div>📌 <b>Main image:</b> White background, centred</div>
                <div>📐 <b>Minimum size:</b> 800×800 px</div>
                <div>🎯 <b>Recommended:</b> 1500×1500 px</div>
                <div>📁 <b>Formats:</b> JPG, PNG, WEBP</div>
                <div>📦 <b>Max file size:</b> 5 MB per image</div>
                <div>🖼️ <b>Additional:</b> Multiple angles, label, usage shots</div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-icon">♿</span><span class="card-title">Image Alt Tags</span><span class="card-subtitle">For accessibility &amp; SEO</span></div>
            <div class="card-body">
              <div id="altTagsContainer"><div class="hint" style="font-size:12px">Upload images first to add alt tags.</div></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB: AMAZON PLATFORM ══════════════════ -->
    <div class="tab-panel" id="tab-amazon">
      <div class="grid">
        <div>

          <!-- AMAZON IDENTIFIERS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔢</span><span class="card-title">Amazon Identifiers</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label>ASIN <span class="opt">(Amazon Standard ID)</span></label>
                  <input type="text" placeholder="e.g. B09XXXXXYZ" class="mono" maxlength="10">
                  <div class="hint">10-char alphanumeric, auto-assigned by Amazon</div>
                </div>
                <div class="form-group">
                  <label>FNSKU <span class="opt">(FBA only)</span></label>
                  <input type="text" placeholder="e.g. X0013XXXXXYZ" class="mono">
                  <div class="hint">Fulfillment Network SKU for FBA label printing</div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>UPC / EAN</label>
                  <input type="text" name="meta_data[ean_barcode]" placeholder="e.g. 3073781039180" class="mono">
                </div>
                <div class="form-group">
                  <label>ISBN <span class="opt">(Books only)</span></label>
                  <input type="text" placeholder="e.g. 978-0-06-112008-4" class="mono">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Browse Node ID (Primary)</label>
                  <input type="text" placeholder="e.g. 16310101" class="mono">
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Browse Node ID (Secondary)</label>
                  <input type="text" placeholder="e.g. 6530595011" class="mono">
                </div>
              </div>
            </div>
          </div>

          <!-- AMAZON TITLE & BULLET POINTS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📝</span><span class="card-title">Amazon Listing Content</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Amazon Product Title <span class="req">*</span> <span class="opt">(200 chars max)</span></label>
                <input type="text" id="amzTitle" placeholder="Brand + Product Type + Key Feature + Size/Count — e.g. Kiri Spreadable Cream Cheese Squares 48 Portions 864g — 2 Packs" oninput="countAmzTitle()">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px">
                  <span class="hint">Include: Brand · Product Type · Key Feature · Size · Pack Count</span>
                  <span id="amzTitleCC" class="cc cc-ok">0/200</span>
                </div>
              </div>

              <div class="sec-head">Key Product Features (Bullet Points)</div>
              <div class="info-box">💡 Amazon allows up to 5 bullet points. Each is indexed for search. Focus on benefits, not just features. Max 500 chars each.</div>

              <div class="form-group" id="bulletGroup1">
                <label>Bullet Point 1 <span class="req">*</span></label>
                <textarea id="bp1" rows="2" placeholder="🔵 INDIVIDUAL PORTIONS: 48 individually wrapped cheese portions (2 packs × 24) — perfect for lunches, snacks &amp; entertaining…" oninput="countBullet(this,'bp1cc')"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px"><span class="hint">Lead with ALLCAPS label, then benefit</span><span id="bp1cc" class="cc cc-ok">0/500</span></div>
              </div>
              <div class="form-group">
                <label>Bullet Point 2</label>
                <textarea id="bp2" rows="2" placeholder="🔵 RICH &amp; CREAMY TASTE: Made from 100% natural pasteurised cow's milk with no artificial preservatives — smooth spreadable texture loved by kids and adults…" oninput="countBullet(this,'bp2cc')"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px"><span class="hint">Feature + benefit</span><span id="bp2cc" class="cc cc-ok">0/500</span></div>
              </div>
              <div class="form-group">
                <label>Bullet Point 3</label>
                <textarea id="bp3" rows="2" placeholder="🔵 HALAL CERTIFIED: Suitable for all the family. Halal certified and free from artificial colours and flavours. Ideal for Qatar &amp; GCC markets…" oninput="countBullet(this,'bp3cc')"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px"><span class="hint">Dietary / certification highlight</span><span id="bp3cc" class="cc cc-ok">0/500</span></div>
              </div>
              <div class="form-group">
                <label>Bullet Point 4</label>
                <textarea id="bp4" rows="2" placeholder="🔵 CONVENIENT STORAGE: Keep refrigerated between 2–8°C. Resealable outer carton keeps portions fresh. Shelf life 6 months from production…" oninput="countBullet(this,'bp4cc')"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px"><span class="hint">Storage / convenience benefit</span><span id="bp4cc" class="cc cc-ok">0/500</span></div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Bullet Point 5</label>
                <textarea id="bp5" rows="2" placeholder="🔵 GREAT VALUE TWIN PACK: 864g total (2 × 432g) — more cheese, better value per gram vs. single packs. Fast delivery across Qatar via Carrefour…" oninput="countBullet(this,'bp5cc')"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px"><span class="hint">Value / call to action</span><span id="bp5cc" class="cc cc-ok">0/500</span></div>
              </div>
            </div>
          </div>

          <!-- BACKEND SEARCH TERMS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔍</span><span class="card-title">Backend Search Terms <span style="font-size:11px;font-weight:400;color:var(--muted)">(Hidden Keywords)</span></span></div>
            <div class="card-body">
              <div class="info-box">🔒 These keywords are invisible to shoppers but indexed by Amazon's A10 algorithm. Add synonyms, misspellings, alternate spellings &amp; foreign language terms. Do NOT repeat words from title/bullets.</div>
              <div class="form-group">
                <label>Search Terms Field 1 <span class="opt">(500 chars)</span></label>
                <textarea id="bk1" rows="3" placeholder="cream cheese portions halal kiri bel frischkäse جبنة كيري spreading cheese soft cheese triangle individual wrapped snack lunch box portion packs fromage frais …" oninput="countBackend(this,'bk1cc',500)"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px">
                  <span class="hint">Separate by spaces (no commas needed)</span>
                  <span id="bk1cc" class="cc cc-ok">0/500 bytes</span>
                </div>
              </div>
              <div class="form-group">
                <label>Search Terms Field 2 <span class="opt">(500 chars)</span></label>
                <textarea id="bk2" rows="2" placeholder="kiri 48 portions 864g fresh cheese refrigerated 2 packs twin pack family size grocery cheese spread qtar doha GCC arabic breakfast snack …" oninput="countBackend(this,'bk2cc',500)"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:3px">
                  <span class="hint">Include regional terms, misspellings, language variants</span>
                  <span id="bk2cc" class="cc cc-ok">0/500 bytes</span>
                </div>
              </div>

              <div class="sec-head">Subject Matter Keywords (by Amazon field)</div>
              <div class="form-row">
                <div class="form-group">
                  <label>Subject Keywords</label>
                  <div class="tag-wrap" id="subjectWrap" onclick="this.querySelector('input').focus()">
                    <span class="tag t-blue">cream cheese <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                    <span class="tag t-blue">dairy spread <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                    <input type="text" id="subjectInput" placeholder="Add subject, Enter…" onkeydown="addTag(event,'subjectWrap','subjectInput','t-blue')">
                  </div>
                </div>
                <div class="form-group">
                  <label>Special Features</label>
                  <div class="tag-wrap" id="featWrap" onclick="this.querySelector('input').focus()">
                    <span class="tag t-green">Halal <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                    <span class="tag t-green">Portion-packed <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                    <span class="tag t-green">No preservatives <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                    <input type="text" id="featInput" placeholder="Add feature, Enter…" onkeydown="addTag(event,'featWrap','featInput','t-green')">
                  </div>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Intended Use</label>
                <div class="tag-wrap" id="useWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-purple">Cooking <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-purple">Breakfast <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-purple">Snacking <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" id="useInput" placeholder="Add use case, Enter…" onkeydown="addTag(event,'useWrap','useInput','t-purple')">
                </div>
              </div>
            </div>
          </div>

          <!-- TARGET AUDIENCE -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🎯</span><span class="card-title">Target Audience &amp; Demographics</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label>Primary Target Audience</label>
                  <select>
                    <option>All Ages</option>
                    <option>Infants (0–12 months)</option>
                    <option>Toddlers (1–3 years)</option>
                    <option>Children (4–12 years)</option>
                    <option>Teens (13–17 years)</option>
                    <option>Adults (18–64 years)</option>
                    <option>Seniors (65+ years)</option>
                    <option>Families</option>
                    <option>Professionals</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Gender</label>
                  <select>
                    <option>All — Unisex</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Boys</option>
                    <option>Girls</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Age Range (Min)</label>
                  <input type="number" placeholder="e.g. 1" min="0">
                </div>
                <div class="form-group">
                  <label>Age Range (Max)</label>
                  <input type="number" placeholder="e.g. 99" min="0">
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Target Audience Tags</label>
                <div class="tag-wrap" id="audWrap" onclick="this.querySelector('input').focus()">
                  <span class="tag t-orange">Families <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-orange">Kids <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <span class="tag t-orange">Health-conscious <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                  <input type="text" id="audInput" placeholder="Add audience, Enter…" onkeydown="addTag(event,'audWrap','audInput','t-orange')">
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT AMAZON PANEL -->
        <div>

          <!-- FULFILLMENT METHOD -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📦</span><span class="card-title">Fulfillment Method</span></div>
            <div class="card-body">
              <div class="oem-grid">
                <div class="oem-card sel" onclick="selectFulfillment(this)">
                  <div class="oem-title">🏭 FBA</div>
                  <div class="oem-desc">Fulfilled by Amazon — stored &amp; shipped by Amazon's warehouse</div>
                </div>
                <div class="oem-card" onclick="selectFulfillment(this)">
                  <div class="oem-title">🏬 FBM</div>
                  <div class="oem-desc">Fulfilled by Merchant — you handle storage &amp; shipping</div>
                </div>
                <div class="oem-card" onclick="selectFulfillment(this)">
                  <div class="oem-title">⭐ SFP</div>
                  <div class="oem-desc">Seller Fulfilled Prime — your warehouse, Prime badge</div>
                </div>
                <div class="oem-card" onclick="selectFulfillment(this)">
                  <div class="oem-title">🌐 MCF</div>
                  <div class="oem-desc">Multi-Channel Fulfillment — Amazon fulfills off-Amazon orders</div>
                </div>
              </div>
              <div class="divider"></div>
              <div class="form-group">
                <label>Prep Instructions (FBA)</label>
                <select>
                  <option>No prep needed</option>
                  <option>Bagging required</option>
                  <option>Bubble wrapping required</option>
                  <option>Taping required</option>
                  <option>Labelling required</option>
                  <option>Set creation required</option>
                  <option>Black-shrink wrapping required</option>
                </select>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>FBA Dangerous Goods Status</label>
                <select>
                  <option>Not a dangerous good</option>
                  <option>Battery included</option>
                  <option>Flammable liquid</option>
                  <option>Corrosive substance</option>
                  <option>Requires special handling</option>
                </select>
              </div>
            </div>
          </div>

          <!-- AMAZON BRAND REGISTRY -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏛️</span><span class="card-title">Brand Registry &amp; A+ Content</span></div>
            <div class="card-body" style="padding:6px 20px 14px">
              <div class="trow" style="padding-top:8px">
                <div><div class="tlbl">Brand Registry Enrolled</div><div class="tdsc">Brand is registered with Amazon</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">A+ Content (EBC) Enabled</div><div class="tdsc">Enhanced Brand Content on listing</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">A++ / Brand Story</div><div class="tdsc">Premium brand module</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Sponsored Products Eligible</div><div class="tdsc">Can run Amazon PPC ads</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Subscribe &amp; Save Eligible</div><div class="tdsc">Customers can auto-reorder</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Amazon Vine Eligible</div><div class="tdsc">Send to Vine Voices for early reviews</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Climate Pledge Friendly</div><div class="tdsc">Has sustainability certification</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
            </div>
          </div>

          <!-- AMAZON CATEGORY GATING -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔒</span><span class="card-title">Category Approval Status</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Gating Status</label>
                <select>
                  <option>Open — No approval required</option>
                  <option>Gated — Approval required</option>
                  <option>Approval pending</option>
                  <option>Approved &amp; ungated</option>
                </select>
              </div>
              <div class="form-group">
                <label>Approval Reference No.</label>
                <input type="text" placeholder="e.g. AMZN-APPR-2024-XXXXX" class="mono">
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Referral Fee %</label>
                <div class="iw sfx"><input type="number" placeholder="e.g. 15" step="0.1"><span class="isfx">%</span></div>
                <div class="hint">Amazon's category commission on each sale</div>
              </div>
            </div>
          </div>

          <!-- AMAZON LISTING QUALITY -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📊</span><span class="card-title">Amazon Listing Quality (PIS)</span></div>
            <div class="card-body">
              <div class="q-lbl"><span>Content Score</span><span id="amzQPct" style="font-weight:700;color:var(--red)">0%</span></div>
              <div class="q-bar"><div class="q-fill" id="amzQFill" style="width:0%"></div></div>
              <div style="margin-top:12px;display:grid;grid-template-columns:1fr 1fr;gap:8px">
                <div style="padding:8px;background:var(--bg);border-radius:8px;border:1px solid var(--border);text-align:center">
                  <div id="amzTitleScore" style="font-size:16px;font-weight:700;color:var(--navy)">—</div>
                  <div style="font-size:10.5px;color:var(--muted)">Title Score</div>
                </div>
                <div style="padding:8px;background:var(--bg);border-radius:8px;border:1px solid var(--border);text-align:center">
                  <div id="amzBPScore" style="font-size:16px;font-weight:700;color:var(--navy)">—</div>
                  <div style="font-size:10.5px;color:var(--muted)">Bullets Score</div>
                </div>
              </div>
              <div id="amzQTips" style="margin-top:10px;font-size:11.5px;color:var(--muted);line-height:1.8">Fill Amazon listing fields to see quality score.</div>
            </div>
          </div>

          <!-- ═══ COMPETITIVELY PRICED ITEM ═══ -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏆</span><span class="card-title">Competitively Priced Item</span><span class="card-subtitle">Amazon Buy Box &amp; price positioning</span></div>
            <div class="card-body">
              <div class="info-box">💡 Amazon's algorithm awards the <b>Buy Box</b> partly based on competitive pricing. Use this section to monitor competitor prices and ensure your listing qualifies for Amazon's <em>"Competitively Priced"</em> badge.</div>

              <div class="sec-head">Price Competitiveness Status</div>
              <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
                <div style="flex:1;min-width:120px;border:2px solid var(--success);background:#f0fff6;border-radius:10px;padding:12px;text-align:center;cursor:pointer" onclick="document.querySelectorAll('.price-status-pill').forEach(p=>p.style.opacity='0.4');this.style.opacity='1'" class="price-status-pill">
                  <div style="font-size:20px">✅</div><div style="font-size:12px;font-weight:700;color:var(--success);margin-top:4px">Competitively Priced</div><div style="font-size:10.5px;color:var(--muted)">Within 2% of lowest</div>
                </div>
                <div style="flex:1;min-width:120px;border:2px solid var(--warn);background:#fffbeb;border-radius:10px;padding:12px;text-align:center;cursor:pointer;opacity:0.4" onclick="document.querySelectorAll('.price-status-pill').forEach(p=>p.style.opacity='0.4');this.style.opacity='1'" class="price-status-pill">
                  <div style="font-size:20px">⚠️</div><div style="font-size:12px;font-weight:700;color:var(--warn);margin-top:4px">Price Alert</div><div style="font-size:10.5px;color:var(--muted)">Competitor is lower</div>
                </div>
                <div style="flex:1;min-width:120px;border:2px solid var(--red);background:var(--red-light);border-radius:10px;padding:12px;text-align:center;cursor:pointer;opacity:0.4" onclick="document.querySelectorAll('.price-status-pill').forEach(p=>p.style.opacity='0.4');this.style.opacity='1'" class="price-status-pill">
                  <div style="font-size:20px">❌</div><div style="font-size:12px;font-weight:700;color:var(--red);margin-top:4px">Not Competitive</div><div style="font-size:10.5px;color:var(--muted)">Buy Box at risk</div>
                </div>
                <div style="flex:1;min-width:120px;border:2px solid var(--border);background:var(--bg);border-radius:10px;padding:12px;text-align:center;cursor:pointer;opacity:0.4" onclick="document.querySelectorAll('.price-status-pill').forEach(p=>p.style.opacity='0.4');this.style.opacity='1'" class="price-status-pill">
                  <div style="font-size:20px">📊</div><div style="font-size:12px;font-weight:700;color:var(--slate);margin-top:4px">Monitoring</div><div style="font-size:10.5px;color:var(--muted)">Awaiting data</div>
                </div>
              </div>

              <div class="sec-head">Your Price vs. Market</div>
              <div class="form-row-3">
                <div class="form-group">
                  <label>Your Listed Price (QAR)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" id="cpYours" placeholder="0.00" step="0.01" oninput="updateCPScore()"></div>
                </div>
                <div class="form-group">
                  <label>Lowest Competitor Price</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" id="cpLowest" placeholder="0.00" step="0.01" oninput="updateCPScore()"></div>
                </div>
                <div class="form-group">
                  <label>Price Difference</label>
                  <input type="text" id="cpDiff" readonly placeholder="—" class="mono" style="background:var(--bg);font-weight:700">
                </div>
              </div>

              <div class="sec-head">Competitor Price Intelligence</div>
              <table style="width:100%;border-collapse:collapse;font-size:12.5px" id="competitorTable">
                <thead><tr style="background:var(--bg)">
                  <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);letter-spacing:0.4px;text-transform:uppercase;border-bottom:1px solid var(--border)">Platform / Retailer</th>
                  <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);letter-spacing:0.4px;text-transform:uppercase;border-bottom:1px solid var(--border)">Competitor Price (QAR)</th>
                  <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);letter-spacing:0.4px;text-transform:uppercase;border-bottom:1px solid var(--border)">Stock Status</th>
                  <th style="padding:8px 12px;text-align:left;font-size:10px;font-weight:700;color:var(--muted);letter-spacing:0.4px;text-transform:uppercase;border-bottom:1px solid var(--border)">Last Checked</th>
                  <th style="border-bottom:1px solid var(--border)"></th>
                </tr></thead>
                <tbody id="compBody">
                  <tr>
                    <td style="padding:6px 8px"><input type="text" placeholder="e.g. Amazon.ae" style="font-size:12px;padding:6px 10px"></td>
                    <td style="padding:6px 8px"><div class="iw"><span class="ipfx" style="font-size:11px">QAR</span><input type="number" placeholder="0.00" step="0.01" style="font-size:12px;padding:6px 10px;padding-left:44px"></div></td>
                    <td style="padding:6px 8px"><select style="font-size:12px;padding:6px 10px"><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select></td>
                    <td style="padding:6px 8px"><input type="date" style="font-size:12px;padding:6px 10px"></td>
                    <td style="padding:6px 8px"><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                  <tr>
                    <td style="padding:6px 8px"><input type="text" placeholder="e.g. Carrefour.qa" style="font-size:12px;padding:6px 10px"></td>
                    <td style="padding:6px 8px"><div class="iw"><span class="ipfx" style="font-size:11px">QAR</span><input type="number" placeholder="0.00" step="0.01" style="font-size:12px;padding:6px 10px;padding-left:44px"></div></td>
                    <td style="padding:6px 8px"><select style="font-size:12px;padding:6px 10px"><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select></td>
                    <td style="padding:6px 8px"><input type="date" style="font-size:12px;padding:6px 10px"></td>
                    <td style="padding:6px 8px"><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                </tbody>
              </table>
              <button class="btn-add" style="margin-top:10px" onclick="(function(){var tb=document.getElementById('compBody');var tr=document.createElement('tr');tr.innerHTML='<td style=\\'padding:6px 8px\\'><input type=\\'text\\' placeholder=\\'Retailer name\\' style=\\'font-size:12px;padding:6px 10px\\'></td><td style=\\'padding:6px 8px\\'><div class=\\'iw\\'><span class=\\'ipfx\\' style=\\'font-size:11px\\'>QAR</span><input type=\\'number\\' placeholder=\\'0.00\\' step=\\'0.01\\' style=\\'font-size:12px;padding:6px 10px;padding-left:44px\\'></div></td><td style=\\'padding:6px 8px\\'><select style=\\'font-size:12px;padding:6px 10px\\'><option>In Stock</option><option>Low Stock</option><option>Out of Stock</option></select></td><td style=\\'padding:6px 8px\\'><input type=\\'date\\' style=\\'font-size:12px;padding:6px 10px\\'></td><td style=\\'padding:6px 8px\\'><button class=\\'btn-tiny del\\' onclick=\\'this.closest(\\"tr\\").remove()\\'>✕</button></td>';tb.appendChild(tr)})()">+ Add Competitor Row</button>

              <div class="divider"></div>
              <div class="form-row">
                <div class="form-group">
                  <label>Repricing Strategy</label>
                  <select>
                    <option>Manual — I set the price</option>
                    <option>Beat lowest by 1%</option>
                    <option>Beat lowest by 2%</option>
                    <option>Match Buy Box price</option>
                    <option>Floor price protection (no lower than cost + margin)</option>
                    <option>Algorithmic repricing (3rd-party tool)</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Floor Price (Minimum Acceptable)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="e.g. 28.00" step="0.01"></div>
                  <div class="hint">Repricing will never go below this price</div>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Competitive Price Notes</label>
                <textarea rows="2" placeholder="e.g. Key competitor is Amazon.ae selling at QAR 33. Our cost price is QAR 22, target margin 30%."></textarea>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT AMAZON PANEL (continued) -->
        <div>

          <!-- ═══ CHECK COMPATIBILITY ═══ -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔗</span><span class="card-title">Check Compatibility</span><span class="card-subtitle">Amazon "Works With" &amp; fitment</span></div>
            <div class="card-body">
              <div class="info-box">🔗 Amazon uses compatibility data to power its <b>"Check if this fits your model"</b> widget. Complete these fields if your product is compatible with specific devices, appliances, vehicles, or systems.</div>

              <div class="sec-head">Product Compatibility Type</div>
              <div class="chk-grid" style="margin-bottom:14px">
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____smartphones___tablets]" value="1"> 📱 Smartphones / Tablets</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____smart_home_devices]" value="1"> 🏠 Smart Home Devices</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____laptops___computers]" value="1"> 💻 Laptops / Computers</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____vehicles___automotive]" value="1"> 🚗 Vehicles / Automotive</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][____electrical_appliances]" value="1"> ⚡ Electrical Appliances</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][________kitchen_appliances]" value="1"> 🍽️ Kitchen Appliances</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][________printers___office]" value="1"> 🖨️ Printers / Office</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][____universal___all]" value="1" checked> ✅ Universal / All</label>
              </div>

              <div class="sec-head">Compatible Models / Systems</div>
              <div class="tag-wrap" id="compatWrap" onclick="this.querySelector('input').focus()">
                <span class="tag t-blue">Universal <span class="tag-rm" onclick="rmTag(this)">×</span></span>
                <input type="text" id="compatInput" placeholder="Add compatible model/brand, press Enter…" onkeydown="addTag(event,'compatWrap','compatInput','t-blue')">
              </div>
              <div class="hint" style="margin-top:4px">e.g. Samsung Galaxy S24, iPhone 15, Toyota Corolla 2020–2024</div>

              <div class="sec-head" style="margin-top:14px">Incompatible / Exclusions</div>
              <div class="tag-wrap" id="incompatWrap" onclick="this.querySelector('input').focus()">
                <input type="text" id="incompatInput" placeholder="Add incompatible model, press Enter…" onkeydown="addTag(event,'incompatWrap','incompatInput','t-red')">
              </div>

              <div class="divider"></div>
              <div class="form-group">
                <label>Compatibility Note <span class="opt">(shown to customers)</span></label>
                <textarea rows="2" placeholder="e.g. This product is a food item and does not require device compatibility checks. Compatible with all dietary preferences when Halal certified."></textarea>
              </div>
              <div class="form-row" style="margin-bottom:0">
                <div class="form-group" style="margin-bottom:0">
                  <label>Connector / Interface Type <span class="opt">(electronics)</span></label>
                  <select>
                    <option value="">N/A — Not applicable</option>
                    <option>USB-C</option><option>USB-A</option><option>Lightning</option>
                    <option>Micro-USB</option><option>Bluetooth 5.0</option>
                    <option>Wi-Fi 2.4GHz</option><option>Wi-Fi 5GHz (dual-band)</option>
                    <option>Zigbee</option><option>Z-Wave</option><option>Thread / Matter</option>
                    <option>3.5mm Audio Jack</option><option>HDMI</option><option>DisplayPort</option>
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Operating System Compatibility</label>
                  <div class="tag-wrap" id="osWrap" onclick="this.querySelector('input').focus()">
                    <input type="text" id="osInput" placeholder="e.g. iOS 16+, Android 13, Windows 11…" onkeydown="addTag(event,'osWrap','osInput','t-purple')">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB: B2B & WHOLESALE ══════════════════ -->
    <div style="display:none" id="__amazon_tab_closed__"></div>
    <div class="tab-panel" id="tab-b2b">
      <div class="grid">
        <div>

          <!-- PRICING TIERS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">💲</span><span class="card-title">Volume Price Tiers</span><span class="card-subtitle">B2B tiered pricing</span></div>
            <div class="card-body" style="padding:0">
              <table class="tier-table">
                <thead><tr><th>Min Qty</th><th>Max Qty</th><th>Unit Price (QAR)</th><th>Discount %</th><th></th></tr></thead>
                <tbody id="tierBody">
                  <tr>
                    <td><input type="number" value="1" min="1"></td>
                    <td><input type="number" value="9" min="1"></td>
                    <td><input type="number" value="35.00" step="0.01"></td>
                    <td><input type="text" value="—" readonly style="background:var(--bg);color:var(--muted)"></td>
                    <td><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                  <tr>
                    <td><input type="number" value="10" min="1"></td>
                    <td><input type="number" value="49" min="1"></td>
                    <td><input type="number" value="32.00" step="0.01"></td>
                    <td><input type="text" value="8.5% OFF" readonly style="background:#f0fff6;color:var(--success);font-weight:600"></td>
                    <td><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                  <tr>
                    <td><input type="number" value="50" min="1"></td>
                    <td><input type="number" placeholder="∞"></td>
                    <td><input type="number" value="28.00" step="0.01"></td>
                    <td><input type="text" value="20% OFF" readonly style="background:#f0fff6;color:var(--success);font-weight:600"></td>
                    <td><button class="btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                  </tr>
                </tbody>
              </table>
              <div style="padding:10px 16px"><button class="btn-add" onclick="addTierRow()">+ Add Price Tier</button></div>
            </div>
          </div>

          <!-- MOQ -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📦</span><span class="card-title">Minimum Order Quantity (MOQ)</span></div>
            <div class="card-body">
              <div class="form-row-3">
                <div class="form-group">
                  <label>MOQ (Units) <span class="req">*</span></label>
                  <input type="number" placeholder="e.g. 1" min="1" value="1">
                </div>
                <div class="form-group">
                  <label>Sample Order MOQ</label>
                  <input type="number" placeholder="e.g. 1" min="1">
                </div>
                <div class="form-group">
                  <label>Max. Order Qty</label>
                  <input type="number" placeholder="No limit">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>MOQ Unit</label>
                  <select>
                    <option>Unit / Piece</option><option>Pack</option><option>Carton</option>
                    <option>Pallet</option><option>Kilogram</option><option>Litre</option>
                  </select>
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Negotiable MOQ?</label>
                  <select>
                    <option>Yes — MOQ negotiable</option>
                    <option>No — Fixed MOQ</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <!-- OEM / ODM -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏭</span><span class="card-title">OEM / ODM &amp; Customisation</span></div>
            <div class="card-body">
              <div class="oem-grid" style="margin-bottom:16px">
                <div class="oem-card sel" onclick="this.classList.toggle('sel')">
                  <div class="oem-title">✅ OEM</div>
                  <div class="oem-desc">Original Equipment Manufacturer — produce under buyer's brand</div>
                </div>
                <div class="oem-card" onclick="this.classList.toggle('sel')">
                  <div class="oem-title">🔬 ODM</div>
                  <div class="oem-desc">Original Design Manufacturer — design + produce for buyer</div>
                </div>
                <div class="oem-card" onclick="this.classList.toggle('sel')">
                  <div class="oem-title">🏷️ Private Label</div>
                  <div class="oem-desc">White-label product with custom branding</div>
                </div>
                <div class="oem-card sel" onclick="this.classList.toggle('sel')">
                  <div class="oem-title">📦 Custom Packaging</div>
                  <div class="oem-desc">Customised packaging / box / label printing</div>
                </div>
              </div>
              <div class="form-group">
                <label>Customisation Details</label>
                <textarea rows="3" placeholder="Describe what can be customised: logo, packaging, colour, size, formulation, labelling…"></textarea>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Customisation MOQ</label>
                  <input type="number" placeholder="e.g. 500 units">
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Sample Lead Time</label>
                  <div class="iw sfx"><input type="number" placeholder="e.g. 7"><span class="isfx">days</span></div>
                </div>
              </div>
            </div>
          </div>

          <!-- SUPPLY ABILITY -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏗️</span><span class="card-title">Supply Ability &amp; Lead Time</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label>Monthly Supply Capacity</label>
                  <div class="iw sfx"><input type="number" placeholder="e.g. 10000"><span class="isfx">units/mo</span></div>
                </div>
                <div class="form-group">
                  <label>Annual Supply Capacity</label>
                  <div class="iw sfx"><input type="number" placeholder="e.g. 120000"><span class="isfx">units/yr</span></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Production Lead Time</label>
                  <div class="iw sfx"><input type="number" placeholder="e.g. 15"><span class="isfx">days</span></div>
                </div>
                <div class="form-group">
                  <label>Delivery Lead Time (after payment)</label>
                  <div class="iw sfx"><input type="number" placeholder="e.g. 25"><span class="isfx">days</span></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Peak Season</label>
                  <input type="text" placeholder="e.g. Nov – Jan (Ramadan)">
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Off-Season Availability</label>
                  <select><option>Year-round</option><option>Seasonal only</option><option>On request</option></select>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT B2B -->
        <div>

          <!-- TRADE ASSURANCE -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🛡️</span><span class="card-title">Trade Assurance</span></div>
            <div class="card-body" style="padding:6px 20px 14px">
              <div class="trow" style="padding-top:8px">
                <div><div class="tlbl">Trade Assurance Eligible</div><div class="tdsc">Payment protected until delivery confirmed</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Quality Guarantee</div><div class="tdsc">Refund if product doesn't match specs</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">On-Time Delivery Guarantee</div><div class="tdsc">Compensation if delivery is late</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">After-Sales Support</div><div class="tdsc">Technical support post delivery</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
            </div>
          </div>

          <!-- PAYMENT METHODS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">💳</span><span class="card-title">Accepted Payment Methods</span></div>
            <div class="card-body">
              <div class="chk-grid">
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____credit_card]" value="1" checked> 💳 Credit Card</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____bank_transfer__t_t_]" value="1" checked> 🏦 Bank Transfer (T/T)</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][________paypal]" value="1" checked> 🅿️ PayPal</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____letter_of_credit__l_c_]" value="1"> 📜 Letter of Credit (L/C)</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____d_p__documents_against_payment_]" value="1"> 📋 D/P (Documents Against Payment)</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____d_a__documents_against_acceptance_]" value="1"> 📄 D/A (Documents Against Acceptance)</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____cash_on_delivery]" value="1" checked> 💰 Cash on Delivery</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][____western_union]" value="1"> ⬜ Western Union</label>
              </div>
              <div class="divider"></div>
              <div class="form-group" style="margin-bottom:0">
                <label>Payment Terms</label>
                <select>
                  <option>100% payment in advance</option>
                  <option>30% deposit, 70% before shipment</option>
                  <option>50% deposit, 50% before shipment</option>
                  <option>Net 30 days</option>
                  <option>Net 60 days</option>
                  <option>Letter of Credit at sight</option>
                </select>
              </div>
            </div>
          </div>

          <!-- SUPPLIER INFO -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏢</span><span class="card-title">Supplier / Company Info</span></div>
            <div class="card-body">
              <div class="form-group"><label>Company Name</label><input type="text" placeholder="e.g. Carrefour Qatar LLC"></div>
              <div class="form-group"><label>Business Type</label><select><option>Retailer</option><option>Manufacturer</option><option>Wholesaler</option><option>Trading Company</option><option>Distributor</option><option>Agent</option></select></div>
              <div class="form-group"><label>Years in Business</label><div class="iw sfx"><input type="number" placeholder="e.g. 25"><span class="isfx">years</span></div></div>
              <div class="form-group"><label>Annual Revenue (USD)</label><select><option>Below $1M</option><option>$1M–$5M</option><option>$5M–$10M</option><option>$10M–$50M</option><option>$50M–$100M</option><option>Above $100M</option></select></div>
              <div class="form-group" style="margin-bottom:0"><label>No. of Employees</label><select><option>1–10</option><option>11–50</option><option>51–200</option><option>201–500</option><option>501–1000</option><option>1000+</option></select></div>
            </div>
          </div>

          <!-- SAMPLE POLICY -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔬</span><span class="card-title">Sample Policy</span></div>
            <div class="card-body">
              <div class="form-group"><label>Sample Available?</label><select><option>Yes — Free Sample</option><option>Yes — Paid Sample</option><option>No Samples Available</option></select></div>
              <div class="form-group"><label>Sample Cost (QAR)</label><div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="0.00" step="0.01"></div></div>
              <div class="form-group" style="margin-bottom:0"><label>Sample Lead Time</label><div class="iw sfx"><input type="number" placeholder="e.g. 3"><span class="isfx">days</span></div></div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 8: LOGISTICS & SHIPPING ══════════════════ -->
    <div class="tab-panel" id="tab-logistics">
      <div class="grid">
        <div>

          <!-- INCOTERMS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">⚖️</span><span class="card-title">Incoterms (International Trade Terms)</span></div>
            <div class="card-body">
              <div class="info-box">📌 Incoterms define who is responsible for shipping, insurance, and import duties between seller and buyer.</div>
              <div class="inco-grid">
                <div class="inco-pill sel" onclick="this.classList.toggle('sel')">EXW<span class="inco-sub">Ex Works</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">FCA<span class="inco-sub">Free Carrier</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">CPT<span class="inco-sub">Carriage Paid To</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">CIP<span class="inco-sub">Carriage &amp; Ins. Paid</span></div>
                <div class="inco-pill sel" onclick="this.classList.toggle('sel')">FOB<span class="inco-sub">Free On Board</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">CFR<span class="inco-sub">Cost &amp; Freight</span></div>
                <div class="inco-pill sel" onclick="this.classList.toggle('sel')">CIF<span class="inco-sub">Cost, Ins. &amp; Freight</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">DPU<span class="inco-sub">Delivered at Place Unloaded</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">DAP<span class="inco-sub">Delivered at Place</span></div>
                <div class="inco-pill sel" onclick="this.classList.toggle('sel')">DDP<span class="inco-sub">Delivered Duty Paid</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">FAS<span class="inco-sub">Free Alongside Ship</span></div>
                <div class="inco-pill" onclick="this.classList.toggle('sel')">DAT<span class="inco-sub">Delivered at Terminal</span></div>
              </div>
            </div>
          </div>

          <!-- SHIPPING METHODS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">✈️</span><span class="card-title">Shipping Methods</span></div>
            <div class="card-body">
              <div class="chk-grid">
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____sea_freight__fcl_]" value="1" checked> 🚢 Sea Freight (FCL)</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____sea_freight__lcl_]" value="1" checked> 📦 Sea Freight (LCL)</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_______air_freight]" value="1" checked> ✈️ Air Freight</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____rail_freight]" value="1"> 🚂 Rail Freight</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____road___truck]" value="1" checked> 🚚 Road / Truck</label>
                <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____express__dhl_fedex_]" value="1" checked> 📮 Express (DHL/FedEx)</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____same_day_local]" value="1"> 🛵 Same-Day Local</label>
                <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____drone_delivery]" value="1"> 🚁 Drone Delivery</label>
              </div>
              <div class="divider"></div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Preferred Carrier</label>
                  <input type="text" placeholder="e.g. DHL, FedEx, Aramex, COSCO">
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Port of Loading</label>
                  <input type="text" placeholder="e.g. Hamad Port, Doha">
                </div>
              </div>
            </div>
          </div>

          <!-- SHIPPING RATES -->
          <div class="card">
            <div class="card-header"><span class="card-icon">💰</span><span class="card-title">Shipping Rates</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group">
                  <label>Free Shipping Threshold (QAR)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="e.g. 150" step="0.01"></div>
                </div>
                <div class="form-group">
                  <label>Standard Shipping Fee (QAR)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="e.g. 15.00" step="0.01"></div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Express Shipping Fee (QAR)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="e.g. 30.00" step="0.01"></div>
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>International Shipping Fee (QAR)</label>
                  <div class="iw"><span class="ipfx">QAR</span><input type="number" placeholder="e.g. 60.00" step="0.01"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- MULTI-WAREHOUSE STOCK -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🏭</span><span class="card-title">Multi-Warehouse Inventory</span></div>
            <div class="card-body">
              <div class="info-box">🏬 Manage stock levels across multiple fulfilment centres and dark stores in Qatar.</div>
              <div id="whRows">
                <div class="wh-row">
                  <div style="font-size:13px;font-weight:500;color:var(--text)">🏬 Doha Central WH</div>
                  <input type="number" placeholder="Qty" value="80" style="text-align:center">
                  <input type="number" placeholder="Alert" value="10" style="text-align:center">
                  <select style="font-size:12px;padding:7px 10px"><option>Active</option><option>Inactive</option></select>
                </div>
                <div class="wh-row">
                  <div style="font-size:13px;font-weight:500;color:var(--text)">🏬 The Pearl WH</div>
                  <input type="number" placeholder="Qty" value="40" style="text-align:center">
                  <input type="number" placeholder="Alert" value="5" style="text-align:center">
                  <select style="font-size:12px;padding:7px 10px"><option>Active</option><option>Inactive</option></select>
                </div>
                <div class="wh-row">
                  <div style="font-size:13px;font-weight:500;color:var(--text)">🏬 Al Wakrah WH</div>
                  <input type="number" placeholder="Qty" value="0" style="text-align:center">
                  <input type="number" placeholder="Alert" value="5" style="text-align:center">
                  <select style="font-size:12px;padding:7px 10px"><option>Active</option><option selected>Inactive</option></select>
                </div>
              </div>
              <button class="btn-add" onclick="addWarehouseRow()">+ Add Warehouse</button>
            </div>
          </div>

          <!-- DANGEROUS GOODS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">⚠️</span><span class="card-title">Dangerous Goods &amp; Restrictions</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Dangerous Goods Classification</label>
                <select>
                  <option>Not Dangerous</option>
                  <option>Class 1 — Explosives</option>
                  <option>Class 2 — Gases</option>
                  <option>Class 3 — Flammable Liquids</option>
                  <option>Class 4 — Flammable Solids</option>
                  <option>Class 5 — Oxidising Substances</option>
                  <option>Class 6 — Toxic Substances</option>
                  <option>Class 7 — Radioactive</option>
                  <option>Class 8 — Corrosives</option>
                  <option>Class 9 — Miscellaneous</option>
                </select>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>UN Number</label>
                  <input type="text" placeholder="e.g. UN1263" class="mono">
                </div>
                <div class="form-group">
                  <label>Packing Group</label>
                  <select><option>N/A</option><option>I</option><option>II</option><option>III</option></select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                  <label>Air Freight Restriction</label>
                  <select><option>No restriction</option><option>Cargo aircraft only</option><option>Prohibited</option></select>
                </div>
                <div class="form-group" style="margin-bottom:0">
                  <label>Temperature Controlled Shipping</label>
                  <select><option>Not required</option><option selected>Chilled (2–8°C)</option><option>Frozen (−18°C)</option></select>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- RIGHT LOGISTICS -->
        <div>

          <!-- EXPORT MARKETS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🌐</span><span class="card-title">Export Markets</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Primary Export Regions</label>
                <div class="chk-grid">
                  <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____gcc]" value="1" checked> 🌍 GCC</label>
                  <label class="chk-item on" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____middle_east]" value="1" checked> 🌍 Middle East</label>
                  <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____africa]" value="1"> 🌍 Africa</label>
                  <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____europe]" value="1"> 🌍 Europe</label>
                  <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____north_america]" value="1"> 🌎 North America</label>
                  <label class="chk-item" onclick="togChk(this)"><input type="checkbox" name="meta_data[allergens][_____asia_pacific]" value="1"> 🌏 Asia Pacific</label>
                </div>
              </div>
              <div class="form-group" style="margin-bottom:0">
                <label>Restricted / Prohibited Countries</label>
                <div class="tag-wrap" id="restrictWrap" onclick="this.querySelector('input').focus()">
                  <input type="text" id="restrictInput" placeholder="Add country, press Enter…" onkeydown="addTag(event,'restrictWrap','restrictInput','t-orange')">
                </div>
              </div>
            </div>
          </div>

          <!-- CUSTOMS & IMPORT -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🛂</span><span class="card-title">Customs &amp; Import Details</span></div>
            <div class="card-body">
              <div class="form-group"><label>HS Tariff Code</label><input type="text" placeholder="e.g. 0406.10.00" class="mono"></div>
              <div class="form-group"><label>Customs Description</label><input type="text" placeholder="e.g. Fresh (unripened) cheese"></div>
              <div class="form-group"><label>Import Duty Rate (est. %)</label><div class="iw sfx"><input type="number" placeholder="e.g. 5" step="0.1"><span class="isfx">%</span></div></div>
              <div class="form-group" style="margin-bottom:0"><label>VAT / GST Rate</label><div class="iw sfx"><input type="number" placeholder="e.g. 5" step="0.1"><span class="isfx">%</span></div></div>
            </div>
          </div>

          <!-- QUALITY INSPECTION -->
          <div class="card">
            <div class="card-header"><span class="card-icon">🔍</span><span class="card-title">Quality Inspection</span></div>
            <div class="card-body" style="padding:6px 20px 12px">
              <div class="trow" style="padding-top:8px">
                <div><div class="tlbl">Pre-shipment Inspection</div><div class="tdsc">Third-party QC before dispatch</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Lab Testing Available</div><div class="tdsc">SGS / Intertek / Bureau Veritas</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Factory Audit Report</div><div class="tdsc">Available on request</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="form-group" style="margin-top:14px;margin-bottom:0">
                <label>Inspection Agency</label>
                <input type="text" placeholder="e.g. SGS, Intertek, QIMA">
              </div>
            </div>
          </div>

          <!-- PALLET & CARTON -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📦</span><span class="card-title">Pallet &amp; Carton Details</span></div>
            <div class="card-body">
              <div class="form-row">
                <div class="form-group"><label>Units per Carton</label><input type="number" name="meta_data[packs_per_carton]" placeholder="e.g. 12"></div>
                <div class="form-group"><label>Cartons per Pallet</label><input type="number" placeholder="e.g. 48"></div>
              </div>
              <div class="form-row">
                <div class="form-group"><label>Carton Length</label><div class="iw sfx"><input type="number" placeholder="0.0"><span class="isfx">cm</span></div></div>
                <div class="form-group"><label>Carton Width</label><div class="iw sfx"><input type="number" placeholder="0.0"><span class="isfx">cm</span></div></div>
              </div>
              <div class="form-row">
                <div class="form-group" style="margin-bottom:0"><label>Carton Height</label><div class="iw sfx"><input type="number" placeholder="0.0"><span class="isfx">cm</span></div></div>
                <div class="form-group" style="margin-bottom:0"><label>Carton Gross Weight</label><div class="iw sfx"><input type="number" placeholder="0.0"><span class="isfx">kg</span></div></div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ══════════════════ TAB 9: REVIEWS & ANALYTICS ══════════════════ -->
    <div class="tab-panel" id="tab-reviews">

      <!-- STAT CARDS -->
      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-val">4.7</div>
          <div class="star-row" style="justify-content:center;margin-bottom:4px"><span class="star lit">★</span><span class="star lit">★</span><span class="star lit">★</span><span class="star lit">★</span><span class="star" style="color:#f59e0b;opacity:0.4">★</span></div>
          <div class="stat-lbl">Avg. Rating</div>
        </div>
        <div class="stat-card">
          <div class="stat-val" style="color:var(--success)">248</div>
          <div class="stat-lbl">Total Reviews</div>
        </div>
        <div class="stat-card">
          <div class="stat-val" style="color:var(--info)">1,842</div>
          <div class="stat-lbl">Product Views</div>
        </div>
        <div class="stat-card">
          <div class="stat-val" style="color:var(--warn)">73%</div>
          <div class="stat-lbl">Conversion Rate</div>
        </div>
      </div>

      <div class="grid">
        <div>

          <!-- RATING BREAKDOWN -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📊</span><span class="card-title">Rating Breakdown</span></div>
            <div class="card-body">
              <div class="chart-row"><span class="chart-lbl">5 ★</span><div class="chart-bar-wrap"><div class="chart-bar-fill" style="width:72%;background:#00a550"></div></div><span class="chart-count">179</span></div>
              <div class="chart-row"><span class="chart-lbl">4 ★</span><div class="chart-bar-wrap"><div class="chart-bar-fill" style="width:18%;background:#34d399"></div></div><span class="chart-count">45</span></div>
              <div class="chart-row"><span class="chart-lbl">3 ★</span><div class="chart-bar-wrap"><div class="chart-bar-fill" style="width:6%;background:#f59e0b"></div></div><span class="chart-count">14</span></div>
              <div class="chart-row"><span class="chart-lbl">2 ★</span><div class="chart-bar-wrap"><div class="chart-bar-fill" style="width:2%;background:#f97316"></div></div><span class="chart-count">6</span></div>
              <div class="chart-row"><span class="chart-lbl">1 ★</span><div class="chart-bar-wrap"><div class="chart-bar-fill" style="width:1%;background:var(--red)"></div></div><span class="chart-count">4</span></div>
            </div>
          </div>

          <!-- REVIEWS LIST -->
          <div class="card">
            <div class="card-header"><span class="card-icon">💬</span><span class="card-title">Customer Reviews</span><span class="card-subtitle">Most recent first</span></div>
            <div class="card-body">

              <div class="review-card">
                <div class="review-meta">
                  <div class="reviewer-avatar">AM</div>
                  <div>
                    <div class="reviewer-name">Ahmed M.</div>
                    <div class="review-stars">★★★★★</div>
                  </div>
                  <div class="reviewer-date">Feb 18, 2026</div>
                </div>
                <div class="review-body">Excellent quality cheese, my kids love it! Arrived cold and fresh. Will definitely re-order. Packaging was intact and well sealed.</div>
                <div class="review-actions">
                  <span class="review-badge rb-pub">✅ Published</span>
                  <button class="btn-tiny">✏️ Reply</button>
                  <button class="btn-tiny del">🚩 Flag</button>
                  <button class="btn-tiny del">🙈 Hide</button>
                </div>
              </div>

              <div class="review-card">
                <div class="review-meta">
                  <div class="reviewer-avatar" style="background:linear-gradient(135deg,#7c3aed,#a855f7)">SR</div>
                  <div>
                    <div class="reviewer-name">Sara R.</div>
                    <div class="review-stars">★★★★<span style="color:#d1d5db">★</span></div>
                  </div>
                  <div class="reviewer-date">Feb 12, 2026</div>
                </div>
                <div class="review-body">Good product but slightly expensive. The individual portions are very convenient for school lunchboxes. Delivery was fast — within 2 hours.</div>
                <div class="review-actions">
                  <span class="review-badge rb-pub">✅ Published</span>
                  <button class="btn-tiny">✏️ Reply</button>
                  <button class="btn-tiny del">🚩 Flag</button>
                  <button class="btn-tiny del">🙈 Hide</button>
                </div>
              </div>

              <div class="review-card" style="border-color:#fee2e2;background:#fffafa">
                <div class="review-meta">
                  <div class="reviewer-avatar" style="background:linear-gradient(135deg,#0ea5e9,#06b6d4)">KA</div>
                  <div>
                    <div class="reviewer-name">Khalid A.</div>
                    <div class="review-stars"><span style="color:#f59e0b">★★★</span><span style="color:#d1d5db">★★</span></div>
                  </div>
                  <div class="reviewer-date">Jan 30, 2026</div>
                </div>
                <div class="review-body">Packaging was slightly damaged on arrival but product inside was fine. Customer service resolved it quickly.</div>
                <div class="review-actions">
                  <span class="review-badge rb-flag">🚩 Flagged</span>
                  <button class="btn-tiny">✏️ Reply</button>
                  <button class="btn-tiny del">🙈 Hide</button>
                  <button class="btn-tiny" style="color:var(--success);border-color:var(--success)">✅ Resolve</button>
                </div>
              </div>

              <button class="btn-add" style="margin-top:10px">Load More Reviews…</button>
            </div>
          </div>

        </div>

        <!-- RIGHT ANALYTICS -->
        <div>

          <!-- ADD REVIEW MANUALLY -->
          <div class="card">
            <div class="card-header"><span class="card-icon">✍️</span><span class="card-title">Add / Seed Review</span></div>
            <div class="card-body">
              <div class="form-group"><label>Reviewer Name</label><input type="text" placeholder="e.g. Mohammed Al-Farsi"></div>
              <div class="form-group">
                <label>Star Rating</label>
                <div class="star-row" id="starRow">
                  <span class="star lit" onclick="setStars(1)">★</span>
                  <span class="star lit" onclick="setStars(2)">★</span>
                  <span class="star lit" onclick="setStars(3)">★</span>
                  <span class="star lit" onclick="setStars(4)">★</span>
                  <span class="star lit" onclick="setStars(5)">★</span>
                  <span style="font-size:12px;color:var(--muted);margin-left:6px" id="starLabel">5 / 5</span>
                </div>
              </div>
              <div class="form-group"><label>Review Title</label><input type="text" placeholder="e.g. Great product!"></div>
              <div class="form-group"><label>Review Body</label><textarea rows="3" placeholder="Write review text…"></textarea></div>
              <div class="form-group"><label>Review Date</label><input type="date"></div>
              <div class="form-group" style="margin-bottom:0"><label>Verified Purchase?</label><select><option>Yes — Verified</option><option>No — Manual Entry</option></select></div>
              <button class="btn btn-primary" style="width:100%;justify-content:center;margin-top:14px" onclick="showToast('✅ Review added!','var(--success)')">Add Review</button>
            </div>
          </div>

          <!-- PRODUCT ANALYTICS -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📈</span><span class="card-title">Product Analytics</span></div>
            <div class="card-body">
              <div class="form-group">
                <label>Product Information Score (PIS)</label>
                <div style="display:flex;align-items:center;gap:10px;margin-top:4px">
                  <div style="flex:1"><div class="mini-bar"><div class="mini-fill" style="width:88%;background:var(--success)"></div></div></div>
                  <span style="font-size:13px;font-weight:700;color:var(--success)">4.4 / 5.0</span>
                </div>
                <div class="hint">Premium product threshold: 4.0+</div>
              </div>
              <div class="divider"></div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                <div style="text-align:center;padding:12px;background:var(--bg);border-radius:10px;border:1px solid var(--border)">
                  <div style="font-size:20px;font-weight:700;color:var(--navy)">1,842</div>
                  <div style="font-size:11px;color:var(--muted)">Page Views (30d)</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--bg);border-radius:10px;border:1px solid var(--border)">
                  <div style="font-size:20px;font-weight:700;color:var(--info)">312</div>
                  <div style="font-size:11px;color:var(--muted)">Add to Cart (30d)</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--bg);border-radius:10px;border:1px solid var(--border)">
                  <div style="font-size:20px;font-weight:700;color:var(--success)">228</div>
                  <div style="font-size:11px;color:var(--muted)">Orders (30d)</div>
                </div>
                <div style="text-align:center;padding:12px;background:var(--bg);border-radius:10px;border:1px solid var(--border)">
                  <div style="font-size:20px;font-weight:700;color:var(--warn)">QAR 7,980</div>
                  <div style="font-size:11px;color:var(--muted)">Revenue (30d)</div>
                </div>
              </div>
            </div>
          </div>

          <!-- INQUIRY MANAGEMENT -->
          <div class="card">
            <div class="card-header"><span class="card-icon">📩</span><span class="card-title">Inquiry / RFQ Settings</span></div>
            <div class="card-body" style="padding:6px 20px 12px">
              <div class="trow" style="padding-top:8px">
                <div><div class="tlbl">Accept Inquiries</div><div class="tdsc">Allow buyers to send RFQs</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Auto-Reply Enabled</div><div class="tdsc">Respond instantly to new inquiries</div></div>
                <label class="tog"><input type="checkbox" checked><span class="tog-track"></span></label>
              </div>
              <div class="trow">
                <div><div class="tlbl">Bulk Inquiry Form</div><div class="tdsc">Show bulk order form on page</div></div>
                <label class="tog"><input type="checkbox"><span class="tog-track"></span></label>
              </div>
              <div class="form-group" style="margin-top:14px;margin-bottom:0">
                <label>Auto-Reply Message</label>
                <textarea rows="2" placeholder="Thank you for your inquiry! We will get back to you within 24 hours…"></textarea>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- ACTION BAR -->
    <div class="action-bar">
      <div class="action-info">Last saved: <strong id="lastSaved">Never</strong></div>
      <div class="btn-row">
        <button class="btn btn-ghost" onclick="saveDraft()">💾 Save Draft</button>
        <button class="btn btn-outline" onclick="showToast('👁 Opening preview…','var(--slate)')">👁 Preview</button>
        <button class="btn btn-primary" onclick="publishProduct()">🚀 Publish Product</button>
      </div>
    </div>

  </div><!-- /content -->
</div><!-- /main -->


            
            <div class="action-bar mt-4">
                <div class="action-info">Last saved: <strong id="lastSaved">Never</strong></div>
                <div class="btn-row">
                    <button type="button" class="btn btn-ghost" onclick="saveDraft()">💾 Save Draft</button>
                    <button type="button" class="btn btn-outline">👁️ Preview</button>
                    <button type="submit" id="submitButton" class="btn btn-primary">🚀 Publish</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/tags-input.min.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        function switchTab(btn, panelId) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(panelId).classList.add('active');
        }
        
        // Include the rest of task2.html JS or a custom submit handler later
    </script>
@endpush