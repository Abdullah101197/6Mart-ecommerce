@extends('layouts.admin.app')
@section('title', translate('messages.add_new_item'))

@push('css_or_js')
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{ asset('assets/admin/css/tags-input.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/admin/css/AI/animation/product/ai-sidebar.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/admin/css/new-product.css') }}" rel="stylesheet">
  <style>
    /* Ensure Bootstrap modal overlays custom layout */
    #npBrandModal { z-index: 2000; }
    #npCategoryModal { z-index: 2000; }
    #npUnitModal { z-index: 2000; }
    #npInstacartOptionModal { z-index: 2000; }
    #npItemTypeModal { z-index: 2000; }
    #npProductSelectOptionModal { z-index: 2000; }
    #npCustomTabModal { z-index: 2000; }
    .modal-backdrop { z-index: 1990; }
  </style>
@endpush

@section('content')
@php
  $openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config');
@endphp
<div class="content container-fluid">

  {{-- Page Header --}}
  <div class="page-header d-flex flex-wrap __gap-15px justify-content-between align-items-center mb-3">
    <h1 class="page-header-title">
      <span class="page-header-icon">
        <img src="{{ asset('assets/admin/img/items.png') }}" class="w--22" alt="">
      </span>
      <span>{{ translate('messages.add_new_item') }}</span>
    </h1>
    <div class="d-flex align-items-end">
      <a href="{{ route('admin.item.product_gallery') }}"
        class="btn btn-outline-primary btn--primary d-flex align-items-center bg-not-hover-primary-ash rounded-8 gap-2">
        <img src="{{ asset('assets/admin/img/product-gallery.png') }}" class="w--22" alt="">
        <span>{{ translate('Add Info From Gallery') }}</span>
      </a>
    </div>
  </div>

  <form id="item_form" enctype="multipart/form-data" class="custom-validation new-product-form" data-ajax="true">
    <input type="hidden" id="request_type" value="admin">
    <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">
    <input type="hidden" id="item_id" name="item_id" value="">
    <input type="hidden" id="draft_mode" name="draft_mode" value="0">

    {{-- ═══ TAB NAVIGATION ═══ --}}
    <div class="np-tab-nav">
      <button type="button" class="np-tab-btn active" onclick="npSwitchTab(this,'np-tab-general')">📝 General</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-attributes')">🔧 Attributes</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-nutrition')">🧪 Nutrition &amp;
        Allergens</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-variants')">🎨 Variants</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-seo')">🔍 SEO &amp; Meta</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-media')">🖼️ Media</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-logistics')">✈️ Logistics &amp;
        Shipping</button>
      <button type="button" class="np-tab-btn" onclick="npSwitchTab(this,'np-tab-reviews')">⭐ Reviews &amp;
        Analytics</button>
      <button type="button" class="np-tab-btn" id="npAddTabBtn" onclick="npOpenCustomTabModal()">+ Add Tab</button>
    </div>
    <input type="hidden" name="custom_tabs_json" id="custom_tabs_json" value="">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- TAB 1: GENERAL --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="np-tab-panel active" id="np-tab-general">
      <div class="np-grid">
        {{-- LEFT --}}
        <div>
          {{-- Basic Information --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">📝</span>
              <span class="np-card-title">Basic Information</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label">{{ translate('messages.item_name') }} (English)
                  <span class="np-req">*</span></label>
                <input type="text" name="name[]" id="productNameEn" class="np-input"
                  placeholder="e.g. Kiri Spreadable Cream Cheese Squares 48 Portions 864g" required oninput="npUpdateQuality();npUpdateSEO()">
                <input type="hidden" name="lang[]" value="default">
              </div>

              @php
                $langsLower = array_map('strtolower', $languages ?? []);
                $optionalNameLocalesRendered = in_array('ar', $langsLower);
              @endphp
              @unless($optionalNameLocalesRendered)
                <div class="np-form-group">
                  <label class="np-label">{{ translate('messages.item_name') }} (Arabic) <span
                      class="np-opt">(optional)</span></label>
                  <input type="text" name="name[]" id="productNameAr" class="np-input" placeholder="اسم المنتج بالعربي"
                    style="direction:rtl;text-align:right">
                  <input type="hidden" name="lang[]" value="ar">
                </div>
              @endunless

              @foreach($languages as $lang)
                @if($lang != 'en')
                  <div class="np-form-group">
                    <label class="np-label">{{ translate('messages.item_name') }} ({{ strtoupper($lang) }}) <span
                        class="np-opt">(optional)</span></label>
                    <input type="text" name="name[]" class="np-input"
                      placeholder="{{ translate('messages.item_name') }} in {{ strtoupper($lang) }}" @if($lang == 'ar')
                      style="direction:rtl;text-align:right" @endif>
                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                  </div>
                @endif
              @endforeach

              <div class="np-form-group">
                <label class="np-label">Short Description <span class="np-req">*</span></label>
                <textarea id="npShortDesc" name="description[]" class="np-textarea" rows="3"
                  placeholder="A brief description shown on listing cards…"
                  oninput="npDescCount();npUpdateQuality()"></textarea>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px">
                  <span class="np-hint">Shown on product listing cards (max 160 chars)</span>
                  <span id="npDescCount" class="np-cc np-cc-ok">0/160</span>
                </div>
              </div>

              <div class="np-form-group">
                <label class="np-label">Full Description</label>
                <textarea name="full_description" class="np-textarea" rows="5"
                  placeholder="Detailed product description including features, usage, storage instructions…"></textarea>
              </div>

              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Brand</label>
                  <div class="d-flex align-items-center" style="gap:8px">
                    <select name="brand_id" id="npBrand" class="np-select" onchange="npUpdateQuality()">
                      <option value="">Select brand…</option>
                      @foreach(\App\Models\Brand::where('status', 1)->get() as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                      @endforeach
                    </select>
                    <button type="button"
                            class="btn btn-outline-primary"
                            style="min-width:40px;height:38px;display:flex;align-items:center;justify-content:center;padding:0"
                            onclick="npOpenBrandModal()"
                            title="{{ translate('messages.add_new_brand') }}">+</button>
                  </div>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Pack / Weight <span class="np-req">*</span></label>
                  <input type="text" name="pack_weight" id="npWeight" class="np-input"
                    placeholder="e.g. 864g, 2×24 portions" oninput="npUpdateQuality()">
                </div>
              </div>
            </div>
          </div>

          {{-- Pricing --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">💰</span>
              <span class="np-card-title">Pricing</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-row-3">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Regular Price <span class="np-req">*</span></label>
                  <div class="np-iw">
                    <span class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                    <input type="number" name="price" id="npRegPrice" class="np-input" placeholder="0.00" step="0.01"
                      min="0" oninput="npUpdateDiscount();npUpdateQuality()">
                  </div>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Sale / Discounted Price</label>
                  <div class="np-iw">
                    <span class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                    <input type="number" name="discount" id="npSalePrice" class="np-input" placeholder="0.00"
                      step="0.01" min="0" oninput="npUpdateDiscount()">
                  </div>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Discount Type</label>
                  <select name="discount_type" id="discount_type" class="np-select">
                    <option value="percent">Percent (%)</option>
                    <option value="amount">Amount ({{ \App\CentralLogics\Helpers::currency_symbol() }})</option>
                  </select>
                </div>
              </div>
              <div class="np-divider"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Promo Start Date</label>
                  <input type="date" name="promo_start_date" class="np-input">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Promo End Date</label>
                  <input type="date" name="promo_end_date" class="np-input">
                </div>
              </div>
            </div>
          </div>

          {{-- About This Item --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">📋</span>
              <span class="np-card-title">About This Item</span>
              <span class="np-card-subtitle">Key Selling Points</span>
            </div>
            <div class="np-card-body">
              <div class="np-info-box">📌 Write clear, benefit-led statements that answer buyer questions instantly.
              </div>
              <div class="np-sec-head">Key Selling Points (up to 6)</div>
              <div id="npAboutRows">
                @for($i = 1; $i <= 6; $i++)
                  <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px">
                    <span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span>
                    <input type="text" name="selling_point[]" class="np-input" placeholder="Add a key selling point…">
                  </div>
                @endfor
              </div>
              <button type="button" class="np-btn-add" onclick="npAddSellingPoint()">+ Add Selling Point</button>

              <div class="np-divider" style="margin:16px 0"></div>

              <div class="np-form-group">
                <label class="np-label">Product Overview (long-form) <span class="np-opt">(Shown in "Product Description" section on Amazon A+)</span></label>
                <textarea name="meta_data[product_overview_long]" class="np-textarea" rows="4"
                  placeholder="Write a 2–4 sentence paragraph expanding on what makes this product special — flavour profile, story, occasions, origin…"></textarea>
              </div>
              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Usage Directions / How to Use</label>
                <textarea name="meta_data[usage_directions]" class="np-textarea" rows="2"
                  placeholder="e.g. Spread directly from the individual portion on bread, toast or crackers. Ideal chilled. Can also be used in dips, sauces, and cheesecakes."></textarea>
              </div>
            </div>
          </div>

          {{-- Instacart Listing Details --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🛒</span>
              <span class="np-card-title">Instacart Listing Details</span>
              <span class="np-card-subtitle">Storefront optimisation</span>
            </div>
            <div class="np-card-body">
              <div class="np-info-box">🛒 Instacart pulls product data directly from your catalogue. Complete these fields to ensure your item appears in the right department, with accurate promo labels and filters.</div>

              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Instacart Department</label>
                  <div class="np-inline-add">
                    <select name="meta_data[instacart_department]" id="npInstacartDepartment" class="np-select js-select2-custom">
                      <option value="">— Select —</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('instacart_options'))
                        @foreach(\App\Models\InstacartOption::where('type','department')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                        @endforeach
                      @else
                        <option value="" disabled>(Run migration to enable options)</option>
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenInstacartOptionModal('department')">+</button>
                  </div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Instacart Aisle / Shelf</label>
                  <input type="text" name="meta_data[instacart_aisle]" class="np-input" placeholder="e.g. Cream Cheese &amp; Soft Cheese">
                </div>
              </div>

              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Instacart Promo Label</label>
                  <div class="np-inline-add">
                    <select name="meta_data[instacart_promo_label]" id="npInstacartPromoLabel" class="np-select js-select2-custom">
                      <option value="">None</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('instacart_options'))
                        @foreach(\App\Models\InstacartOption::where('type','promo_label')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenInstacartOptionModal('promo_label')">+</button>
                  </div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Instacart Unit Pricing Display</label>
                  <div class="np-inline-add">
                    <select name="meta_data[instacart_unit_pricing_display]" id="npInstacartUnitPricingDisplay" class="np-select js-select2-custom">
                      <option value="">— Select —</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('instacart_options'))
                        @foreach(\App\Models\InstacartOption::where('type','unit_pricing_display')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenInstacartOptionModal('unit_pricing_display')">+</button>
                  </div>
                </div>
              </div>

              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Instacart Product Tags</label>
                <input type="text" name="meta_data[instacart_product_tags]" class="np-input" placeholder="e.g. Halal, Gluten-free, Family size">
                <div class="np-hint">Comma-separated. Tags map to Instacart dietary filters (e.g. Organic, Vegan, Kosher, Gluten-Free).</div>
              </div>
            </div>
          </div>

          {{-- Categories --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🗂️</span>
              <span class="np-card-title">Categories &amp; Classification</span>
              <span class="np-card-subtitle">Amazon-style deep tree</span>
            </div>
            <div class="np-card-body">
              <div class="np-info-box">🗂️ Select the deepest (leaf) category whenever possible. Leaf selection can auto-fill Amazon backend fields like Browse Node.</div>
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">{{ translate('messages.store') }} <span class="np-req">*</span></label>
                  <select name="store_id" id="store_id" class="np-select js-select2-custom" required>
                    <option value="">{{ translate('messages.select_store') }}</option>
                    @foreach(\App\Models\Store::when(Config::get('module.current_module_id'), function ($query, $moduleId) {
                      $query->where('module_id', $moduleId);
                    })->orderBy('name')->get(['id', 'name']) as $store)
                      <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Main Category <span class="np-req">*</span></label>
                  <div class="np-inline-add">
                    <select name="category_id" id="category_id" class="np-select js-select2-custom"
                      onchange="npUpdateQuality()">
                      <option value="">— Select Category —</option>
                      @foreach(\App\Models\Category::where('position', 0)->where('status', 1)->where('module_id', Config::get('module.current_module_id'))->get() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenCategoryModal(0)">+</button>
                  </div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Sub Category (Level 2)</label>
                  <div class="np-inline-add">
                    <select name="sub_category_id" id="sub_category_id" class="np-select js-select2-custom">
                      <option value="">Select main first…</option>
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenCategoryModal(1)">+</button>
                  </div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Sub-Sub Category (Level 3)</label>
                  <div class="np-inline-add">
                    <select name="sub_sub_category_id" id="sub_sub_category_id" class="np-select js-select2-custom">
                      <option value="">Select Level 2 first…</option>
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenCategoryModal(2)">+</button>
                  </div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Level 4 (Leaf Node)</label>
                  <div class="np-inline-add">
                    <select name="sub_sub_sub_category_id" id="sub_sub_sub_category_id" class="np-select js-select2-custom">
                      <option value="">Select Level 3 first…</option>
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenCategoryModal(3)">+</button>
                  </div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">{{ translate('messages.unit') }}</label>
                  <div class="np-inline-add">
                    <select name="unit" id="unit" class="np-select js-select2-custom">
                      <option value="">{{ translate('messages.select_unit') }}</option>
                      @foreach(\App\Models\Unit::get(['id', 'unit']) as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                      @endforeach
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenUnitModal()">+</button>
                  </div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Browse Node ID <span class="np-opt">(Amazon)</span></label>
                  <input type="text" id="npBrowseNodeId" name="meta_data[browse_node_id]" class="np-input"
                    placeholder="e.g. 16310101" readonly style="background:var(--np-bg)">
                  <div class="np-hint">Auto-populated from category selection</div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Product Type Keyword <span class="np-opt">(Amazon backend)</span></label>
                  <input type="text" id="npProductTypeKeyword" name="meta_data[product_type_keyword]" class="np-input"
                    placeholder="e.g. CHEESE, CREAM_CHEESE">
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">{{ translate('messages.item_type') }}</label>
                  <div class="np-inline-add">
                    <select id="npItemTypeSelect" class="np-select js-select2-custom">
                      <option value="">— Select —</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('item_types'))
                        @foreach(\App\Models\ItemType::where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name','is_veg']) as $t)
                          <option value="{{ $t->id }}" data-is-veg="{{ (int)$t->is_veg }}">{{ $t->name }}</option>
                        @endforeach
                      @else
                        <option value="" disabled>(Run migration to enable item types)</option>
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenItemTypeModal()">+</button>
                  </div>
                  {{-- Keep backend compatibility: ItemController expects veg=0/1 --}}
                  <input type="hidden" name="veg" id="veg" value="0">
                  <input type="hidden" name="meta_data[item_type_id]" id="npItemTypeId" value="">
                  <input type="hidden" name="meta_data[item_type_name]" id="npItemTypeName" value="">
                </div>
                <div class="np-form-group">
                  <label class="np-label">Tags</label>
                  <input type="text" id="np_tags_input" name="tags" class="np-input"
                    placeholder="Enter tags, comma separated">
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
          {{-- Status --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🚦</span>
              <span class="np-card-title">Status</span>
            </div>
            <div class="np-card-body">
              <div class="np-pill-row">
                <div class="np-pill sel" onclick="npSelStatus(this,'active')">
                  <span class="np-sdot"></span>Active
                  <input type="radio" name="status" value="1" checked style="display:none">
                </div>
                <div class="np-pill" onclick="npSelStatus(this,'out')">
                  <span class="np-sdot"></span>Out of Stock
                  <input type="radio" name="status" value="0" style="display:none">
                </div>
                <div class="np-pill" onclick="npSelStatus(this,'draft')">
                  <span class="np-sdot"></span>Draft
                  <input type="radio" name="status" value="2" style="display:none">
                </div>
              </div>
            </div>
          </div>

          {{-- Origin & Seller --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🌍</span>
              <span class="np-card-title">Origin &amp; Seller</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label">Country of Origin <span class="np-req">*</span></label>
                <div class="np-inline-add">
                  <select name="meta_data[country_of_origin]" id="npOriginCountry" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','origin_country')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                      @endforeach
                    @else
                      <option value="" disabled>(Run migration to enable options)</option>
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('origin_country')">+</button>
                </div>
              </div>

              <div class="np-form-group">
                <label class="np-label">Manufacturer / Producer</label>
                <input type="text" name="meta_data[manufacturer]" class="np-input" placeholder="e.g. Groupe Bel S.A.">
              </div>

              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Seller</label>
                <div class="np-inline-add">
                  <select name="meta_data[seller]" id="npSeller" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','seller')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}">{{ $opt->name }}</option>
                      @endforeach
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('seller')">+</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Visibility & Features --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">👁️</span>
              <span class="np-card-title">Visibility &amp; Features</span>
            </div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Featured Product</div>
                  <div class="np-tdsc">Show in homepage carousel</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="is_featured" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Recommended</div>
                  <div class="np-tdsc">Show in recommended section</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="is_recommended" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Special Offer Badge</div>
                  <div class="np-tdsc">Display sale badge on listing</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="is_sale" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Search Indexed</div>
                  <div class="np-tdsc">Appear in search results</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="search_indexed" value="1" checked><span
                    class="np-tog-track"></span></label>
              </div>
            </div>
          </div>

          {{-- Delivery Options --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🚚</span>
              <span class="np-card-title">Delivery Options</span>
            </div>
            <div class="np-card-body">
              <div class="np-dlv-grid">
                <div class="np-dlv-card sel" onclick="this.classList.toggle('sel')">
                  <div class="np-dlv-icon">📅</div>
                  <div class="np-dlv-name">Scheduled</div>
                  <div class="np-dlv-time">Next day</div>
                  <input type="checkbox" name="meta_data[delivery_scheduled]" value="1" checked style="display:none">
                </div>
                <div class="np-dlv-card sel" onclick="this.classList.toggle('sel')">
                  <div class="np-dlv-icon">⚡</div>
                  <div class="np-dlv-name">Express</div>
                  <div class="np-dlv-time">60–120 mins</div>
                  <input type="checkbox" name="meta_data[delivery_express]" value="1" checked style="display:none">
                </div>
                <div class="np-dlv-card" onclick="this.classList.toggle('sel')">
                  <div class="np-dlv-icon">🏎️</div>
                  <div class="np-dlv-name">Rapid</div>
                  <div class="np-dlv-time">35 mins</div>
                  <input type="checkbox" name="meta_data[delivery_rapid]" value="1" style="display:none">
                </div>
                <div class="np-dlv-card" onclick="this.classList.toggle('sel')">
                  <div class="np-dlv-icon">🏬</div>
                  <div class="np-dlv-name">Click &amp; Collect</div>
                  <div class="np-dlv-time">In-store</div>
                  <input type="checkbox" name="meta_data[delivery_click_collect]" value="1" style="display:none">
                </div>
              </div>
            </div>
          </div>

          {{-- Inventory --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">📊</span>
              <span class="np-card-title">Inventory</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label">SKU / Barcode</label>
                <input type="text" name="sku" id="sku" class="np-input np-mono" placeholder="e.g. SKU-001234">
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Stock Qty</label>
                  <input type="number" name="current_stock" id="quantity" class="np-input" placeholder="0" min="0">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">{{ translate('messages.Maximum_Purchase_Quantity_Limit') }}</label>
                  <input type="number" name="maximum_cart_quantity" id="cart_quantity" class="np-input" placeholder="10"
                    min="0">
                </div>
              </div>
            </div>
          </div>

          {{-- Listing Quality --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">⭐</span>
              <span class="np-card-title">Listing Quality Score</span>
            </div>
            <div class="np-card-body">
              <div class="np-q-lbl">
                <span>Completeness</span>
                <span id="npQualityPct" style="font-weight:700;color:var(--np-primary)">0%</span>
              </div>
              <div class="np-q-bar">
                <div class="np-q-fill" id="npQualityFill" style="width:0%"></div>
              </div>
              <div id="npQualityTips" style="margin-top:10px;font-size:11.5px;color:#7a8fa6;line-height:1.8">Complete
                required fields to improve quality.</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Custom tabs get injected here --}}
    <div id="npCustomTabsMount"></div>
    {{-- ══ TAB 2: ATTRIBUTES ══ --}}
    <div class="np-tab-panel" id="np-tab-attributes">
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📐</span><span class="np-card-title">Physical
                Dimensions &amp; Weight</span></div>
            <div class="np-card-body">
              <div class="np-sec-head">Product Dimensions</div>
              <div class="np-form-row-3">
                <div class="np-form-group"><label class="np-label">Length</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[length]" class="np-input"
                      placeholder="0.0" step="0.1"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Width</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[width]" class="np-input" placeholder="0.0"
                      step="0.1"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Height</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[height]" class="np-input"
                      placeholder="0.0" step="0.1"><span class="np-isfx">cm</span></div>
                </div>
              </div>
              <div class="np-sec-head">Weight</div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Net Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[net_weight]" class="np-input"
                      placeholder="0.00" step="0.01"><span class="np-isfx">g</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Gross Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[gross_weight]" class="np-input"
                      placeholder="0.00" step="0.01"><span class="np-isfx">g</span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📋</span><span class="np-card-title">Product
                Identifiers</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">EAN / GTIN Barcode</label><input type="text"
                    name="meta_data[ean]" class="np-input np-mono" placeholder="e.g. 3073781039180"></div>
                <div class="np-form-group"><label class="np-label">Internal SKU</label><input type="text"
                    name="meta_data[internal_sku]" class="np-input np-mono" placeholder="e.g. PRD-001"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Manufacturer Part No.</label><input type="text"
                    name="meta_data[mpn]" class="np-input np-mono" placeholder="e.g. MPN-001"></div>
                <div class="np-form-group"><label class="np-label">Model / Item Number</label><input type="text"
                    name="meta_data[model_no]" class="np-input" placeholder="e.g. MDL-2024-V1"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">HS Code
                    (Customs)</label><input type="text" name="meta_data[hs_code]" class="np-input np-mono"
                    placeholder="e.g. 0406.10"></div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Country of
                    Manufacture</label><input type="text" name="meta_data[country_of_manufacture]" class="np-input"
                    placeholder="e.g. Poland"></div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📦</span><span class="np-card-title">Packaging
                Specifications</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Packaging Type</label><select
                    name="meta_data[packaging_type]" class="np-select">
                    <option value="">Select…</option>
                    <option>Cardboard Box</option>
                    <option>Plastic Tray</option>
                    <option>Vacuum Pack</option>
                    <option>Glass Jar</option>
                    <option>Tin Can</option>
                    <option>Pouch / Sachet</option>
                    <option>Bottle</option>
                    <option>Blister Pack</option>
                    <option>Resealable Bag</option>
                  </select></div>
                <div class="np-form-group"><label class="np-label">Units per Pack</label><input type="number"
                    name="meta_data[units_per_pack]" class="np-input" placeholder="e.g. 24"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Packs per Carton</label><input type="number"
                    name="meta_data[packs_per_carton]" class="np-input" placeholder="e.g. 12"></div>
                <div class="np-form-group"><label class="np-label">Recyclable Packaging</label><select
                    name="meta_data[recyclable]" class="np-select">
                    <option>Select…</option>
                    <option>Yes — Fully Recyclable</option>
                    <option>Partially Recyclable</option>
                    <option>No</option>
                  </select></div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🌡️</span><span class="np-card-title">Storage &amp;
                Shelf Life</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Storage Type</label><select
                    name="meta_data[storage_type]" class="np-select">
                    <option>Select…</option>
                    <option>Ambient (Room Temp)</option>
                    <option>Refrigerated (2–8°C)</option>
                    <option>Frozen (−18°C or below)</option>
                    <option>Cool &amp; Dry</option>
                  </select></div>
                <div class="np-form-group"><label class="np-label">Temperature Range</label>
                  <div class="np-iw sfx"><input type="text" name="meta_data[temp_range]" class="np-input"
                      placeholder="2 – 8"><span class="np-isfx">°C</span></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Shelf Life from Production</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[shelf_life_days]" class="np-input"
                      placeholder="e.g. 180"><span class="np-isfx">days</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Min. Days on Delivery</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[min_days_delivery]" class="np-input"
                      placeholder="e.g. 30"><span class="np-isfx">days</span></div>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Storage
                  Instructions</label><textarea name="meta_data[storage_instructions]" class="np-textarea" rows="2"
                  placeholder="e.g. Once opened, keep refrigerated and consume within 3 days."></textarea></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">✏️</span><span class="np-card-title">Custom
                Attributes</span><span class="np-card-subtitle">Key–Value pairs</span></div>
            <div class="np-card-body" style="padding-bottom:12px">
              <div class="np-info-box">💡 Add product-specific attributes shown in the specifications table on the
                product page.</div>
              <div id="npCustomAttrs">
                <div class="np-form-row" style="margin-bottom:10px"><input type="text"
                    name="meta_data[custom_attr_name][]" class="np-input"
                    placeholder="Attribute name (e.g. Fat Content)"><input type="text"
                    name="meta_data[custom_attr_val][]" class="np-input" placeholder="Value (e.g. 24g per 100g)"></div>
                <div class="np-form-row" style="margin-bottom:10px"><input type="text"
                    name="meta_data[custom_attr_name][]" class="np-input" placeholder="Attribute name"><input
                    type="text" name="meta_data[custom_attr_val][]" class="np-input" placeholder="Value"></div>
              </div>
              <button type="button" class="np-btn-add" onclick="npAddCustomAttr()">+ Add Attribute Row</button>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🏷️</span><span class="np-card-title">Product
                Type</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Product Type</label><select name="product_type"
                  class="np-select">
                  <option value="simple">Simple Product</option>
                  <option value="variable">Variable Product</option>
                  <option value="bundle">Bundle / Multipack</option>
                </select></div>
              <div class="np-form-group"><label class="np-label">Condition</label><select name="meta_data[condition]"
                  class="np-select">
                  <option>New</option>
                  <option>Refurbished</option>
                  <option>Used</option>
                </select></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Age Restriction</label><select
                  name="meta_data[age_restriction]" class="np-select">
                  <option value="">None</option>
                  <option value="18">18+</option>
                  <option value="21">21+</option>
                </select></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📅</span><span class="np-card-title">Dates &amp;
                Lifecycle</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Product Launch Date</label><input type="date"
                  name="meta_data[launch_date]" class="np-input"></div>
              <div class="np-form-group"><label class="np-label">End-of-Life Date</label><input type="date"
                  name="meta_data[eol_date]" class="np-input"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Warranty Period</label><select
                  name="meta_data[warranty]" class="np-select">
                  <option value="">N/A</option>
                  <option>1 Month</option>
                  <option>3 Months</option>
                  <option>6 Months</option>
                  <option>1 Year</option>
                  <option>2 Years</option>
                  <option>3 Years</option>
                </select></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">⚖️</span><span
                class="np-card-title">Compliance</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Approval / Permit No.</label><input type="text"
                  name="meta_data[approval_no]" class="np-input np-mono" placeholder="e.g. QFSSA-2024-XXXX"></div>
              <div class="np-form-group"><label class="np-label">Import Permit No.</label><input type="text"
                  name="meta_data[import_permit]" class="np-input np-mono" placeholder="e.g. IMP-XXXX"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">CE / Safety
                  Marking</label><input type="text" name="meta_data[safety_marking]" class="np-input"
                  placeholder="e.g. CE, FCC, ROHS"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🏅</span><span
                class="np-card-title">Certifications</span></div>
            <div class="np-card-body">
              <div class="np-chk-grid">
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="halal"> 🟢 Halal</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="organic"> 🌱 Organic</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="free_range"> 🐄 Free Range</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="non_gmo"> 🌿 Non-GMO</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="iso22000"> 🔬 ISO 22000</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="haccp"> 📋 HACCP</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="msc"> 🐟 MSC Certified</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="energy_star"> ⚡ Energy Star</label>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">↩️</span><span class="np-card-title">Return
                Policy</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Returnable</label><select
                  name="meta_data[return_policy]" class="np-select">
                  <option>Yes — Within 7 days</option>
                  <option>Yes — Within 14 days</option>
                  <option>Yes — Within 30 days</option>
                  <option>No — Non-returnable (perishable)</option>
                </select></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Return
                  Conditions</label><textarea name="meta_data[return_conditions]" class="np-textarea" rows="2"
                  placeholder="e.g. Original packaging, unopened, within expiry date."></textarea></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 3: NUTRITION & ALLERGENS ══ --}}
    <div class="np-tab-panel" id="np-tab-nutrition">
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🧾</span><span
                class="np-card-title">Ingredients</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Ingredients List (English) <span
                    class="np-req">*</span></label><textarea name="meta_data[ingredients_en]" class="np-textarea"
                  rows="4" placeholder="e.g. Pasteurised cow's milk, cream, salt, lactic acid bacteria…"></textarea>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Ingredients List
                  (Arabic)</label><textarea name="meta_data[ingredients_ar]" class="np-textarea" rows="3"
                  placeholder="قائمة المكونات بالعربي…" style="direction:rtl;text-align:right"></textarea></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🧪</span><span class="np-card-title">Nutrition Facts
                (per 100g / per serving)</span></div>
            <div class="np-card-body" style="padding:0">
              <table class="np-ntbl">
                <thead>
                  <tr>
                    <th>Nutrient</th>
                    <th>Per 100g</th>
                    <th>Per Serving</th>
                    <th>Unit</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Energy</td>
                    <td><input type="text" name="meta_data[nutr_energy_100g]" class="np-input" placeholder="263"></td>
                    <td><input type="text" name="meta_data[nutr_energy_srv]" class="np-input" placeholder="47"></td>
                    <td>kcal</td>
                  </tr>
                  <tr>
                    <td>Total Fat</td>
                    <td><input type="text" name="meta_data[nutr_fat_100g]" class="np-input" placeholder="24"></td>
                    <td><input type="text" name="meta_data[nutr_fat_srv]" class="np-input" placeholder="4.3"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Saturated Fat</td>
                    <td><input type="text" name="meta_data[nutr_satfat_100g]" class="np-input" placeholder="15"></td>
                    <td><input type="text" name="meta_data[nutr_satfat_srv]" class="np-input" placeholder="2.7"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Trans Fat</td>
                    <td><input type="text" name="meta_data[nutr_transfat_100g]" class="np-input" placeholder="0"></td>
                    <td><input type="text" name="meta_data[nutr_transfat_srv]" class="np-input" placeholder="0"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Total Carbohydrates</td>
                    <td><input type="text" name="meta_data[nutr_carbs_100g]" class="np-input" placeholder="4.5"></td>
                    <td><input type="text" name="meta_data[nutr_carbs_srv]" class="np-input" placeholder="0.8"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Total Sugars</td>
                    <td><input type="text" name="meta_data[nutr_sugars_100g]" class="np-input" placeholder="2.2"></td>
                    <td><input type="text" name="meta_data[nutr_sugars_srv]" class="np-input" placeholder="0.4"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Dietary Fibre</td>
                    <td><input type="text" name="meta_data[nutr_fibre_100g]" class="np-input" placeholder="0"></td>
                    <td><input type="text" name="meta_data[nutr_fibre_srv]" class="np-input" placeholder="0"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Protein</td>
                    <td><input type="text" name="meta_data[nutr_protein_100g]" class="np-input" placeholder="7.8"></td>
                    <td><input type="text" name="meta_data[nutr_protein_srv]" class="np-input" placeholder="1.4"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Salt</td>
                    <td><input type="text" name="meta_data[nutr_salt_100g]" class="np-input" placeholder="1.2"></td>
                    <td><input type="text" name="meta_data[nutr_salt_srv]" class="np-input" placeholder="0.2"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Calcium</td>
                    <td><input type="text" name="meta_data[nutr_calcium_100g]" class="np-input" placeholder="100"></td>
                    <td><input type="text" name="meta_data[nutr_calcium_srv]" class="np-input" placeholder="18"></td>
                    <td>mg</td>
                  </tr>
                  <tr>
                    <td>Vitamin A</td>
                    <td><input type="text" name="meta_data[nutr_vita_100g]" class="np-input" placeholder="180"></td>
                    <td><input type="text" name="meta_data[nutr_vita_srv]" class="np-input" placeholder="32"></td>
                    <td>µg</td>
                  </tr>
                  <tr>
                    <td>Cholesterol</td>
                    <td><input type="text" name="meta_data[nutr_chol_100g]" class="np-input" placeholder="70"></td>
                    <td><input type="text" name="meta_data[nutr_chol_srv]" class="np-input" placeholder="13"></td>
                    <td>mg</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🍽️</span><span class="np-card-title">Serving
                Information</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Serving Size</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[serving_size]" class="np-input"
                      placeholder="18" step="0.5"><span class="np-isfx">g</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Servings per
                    Container</label><input type="number" name="meta_data[servings_per_container]" class="np-input"
                    placeholder="48"></div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">⚠️</span><span class="np-card-title">Allergens (EU 14
                + GCC)</span></div>
            <div class="np-card-body">
              <div class="np-sec-head">Contains</div>
              <div class="np-chk-grid">
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="milk"> 🥛 Milk</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="gluten"> 🌾 Gluten/Wheat</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="eggs"> 🥚 Eggs</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="fish"> 🐟 Fish</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="crustaceans"> 🦐 Crustaceans</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="peanuts"> 🥜 Peanuts</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="tree_nuts"> 🌰 Tree Nuts</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="soybeans"> 🫘 Soybeans</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="sesame"> 🌱 Sesame</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="mustard"> 🌻 Mustard</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="celery"> 🥬 Celery</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="sulphites"> 🍇 Sulphites</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="molluscs"> 🦑 Molluscs</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="lupin"> 🫘 Lupin</label>
              </div>
              <div class="np-sec-head" style="margin-top:14px">May Contain (Cross-contamination)</div>
              <div class="np-tag-wrap" id="npMcWrap" onclick="this.querySelector('input').focus()">
                <input type="text" id="npMcInput" class="np-input" placeholder="e.g. Tree Nuts, Soy… press Enter"
                  onkeydown="npAddTag(event,'npMcWrap','npMcInput','t-orange','meta_data[may_contain][]')">
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🥗</span><span class="np-card-title">Dietary
                Information</span></div>
            <div class="np-card-body">
              <div class="np-chk-grid">
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="halal"> ✅ Halal</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="kosher"> ✡️ Kosher</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="vegetarian"> 🌱 Vegetarian</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="vegan"> 🌿 Vegan</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="gluten_free"> 🚫🌾 Gluten-Free</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="dairy_free"> 🚫🥛 Dairy-Free</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="low_sugar"> 🍬 Low Sugar</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="no_preservatives"> 🫀 No Preservatives</label>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔬</span><span class="np-card-title">Additives &amp;
                E-Numbers</span></div>
            <div class="np-card-body">
              <div class="np-tag-wrap" id="npENumWrap" onclick="this.querySelector('input').focus()">
                <input type="text" id="npENumInput" class="np-input"
                  placeholder="Add E-number, press Enter… (e.g. E330)"
                  onkeydown="npAddTag(event,'npENumWrap','npENumInput','t-blue','meta_data[e_numbers][]')">
              </div>
              <div class="np-hint" style="margin-top:6px">E.g. E330 (Citric Acid), E471 (Emulsifier)</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 4: VARIANTS ══ --}}
    <div class="np-tab-panel" id="np-tab-variants">
      <div class="np-grid">
        <div>
          @if (Config::get('module.current_module_type') == 'food')
            @includeif('admin-views.product.partials._food_variations')
          @else
            @includeif('admin-views.product.partials._other_variations')
          @endif
          <div class="np-card" style="margin-top:18px">
            <div class="np-card-header"><span class="np-card-icon">📊</span><span class="np-card-title">Variant SKU
                Matrix</span><span class="np-card-subtitle">Per variant pricing &amp; stock</span></div>
            <div class="np-card-body" style="padding:0">
              <table class="np-vtbl">
                <thead>
                  <tr>
                    <th>Variant</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="npVariantBody">
                  <tr>
                    <td><input type="text" name="meta_data[var_name][]" class="np-input"
                        placeholder="e.g. Original / 864g"></td>
                    <td><input type="text" name="meta_data[var_sku][]" class="np-input np-mono" placeholder="SKU-001">
                    </td>
                    <td><input type="number" name="meta_data[var_price][]" class="np-input" placeholder="0.00"
                        step="0.01"></td>
                    <td><input type="number" name="meta_data[var_stock][]" class="np-input" placeholder="0"></td>
                    <td><select name="meta_data[var_status][]" class="np-select"
                        style="font-size:11.5px;padding:5px 28px 5px 8px">
                        <option>Active</option>
                        <option>Out of Stock</option>
                        <option>Disabled</option>
                      </select></td>
                    <td><button type="button" class="np-btn-tiny del" onclick="this.closest('tr').remove()">✕</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div style="padding:12px 16px"><button type="button" class="np-btn-add" onclick="npAddVariantRow()">+ Add
                  Variant Row</button></div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🎨</span><span class="np-card-title">Colour / Flavour
                Variants</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Variant Type</label><select
                  name="meta_data[variant_type]" class="np-select">
                  <option>Flavour</option>
                  <option>Colour</option>
                  <option>Scent</option>
                  <option>Style</option>
                </select></div>
              <div class="np-form-group"><label class="np-label">Colour Palette</label>
                <div class="np-swatches">
                  @foreach(['#006161' => 'Teal', '#2563eb' => 'Blue', '#16a34a' => 'Green', '#f59e0b' => 'Amber', '#7c3aed' => 'Purple', '#0d1b2a' => 'Black', '#f9fafb' => 'White', '#d97706' => 'Orange', '#ec4899' => 'Pink', '#9ca3af' => 'Grey'] as $hex => $name)
                    <div class="np-swatch" style="background:{{ $hex }}" title="{{ $name }}"
                      onclick="this.classList.toggle('sel')"></div>
                  @endforeach
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Custom Flavour / Colour
                  Names</label>
                <div class="np-tag-wrap" id="npFlavWrap" onclick="this.querySelector('input').focus()">
                  <input type="text" id="npFlavInput" class="np-input" placeholder="Add variant, press Enter…"
                    onkeydown="npAddTag(event,'npFlavWrap','npFlavInput','t-green','meta_data[flavor_names][]')">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📏</span><span class="np-card-title">Size / Weight
                Variants</span></div>
            <div class="np-card-body">
              <div class="np-tag-wrap" id="npSizeWrap" onclick="this.querySelector('input').focus()">
                <input type="text" id="npSizeInput" class="np-input" placeholder="Add size/weight, press Enter…"
                  onkeydown="npAddTag(event,'npSizeWrap','npSizeInput','t-purple','meta_data[size_variants][]')">
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔗</span><span class="np-card-title">Related
                Products</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Upsell Products</label><input type="text"
                  name="meta_data[upsell]" class="np-input" placeholder="Search by name or SKU…"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Cross-Sell
                  Products</label><input type="text" name="meta_data[crosssell]" class="np-input"
                  placeholder="Search by name or SKU…"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔢</span><span class="np-card-title">Purchase
                Limits</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Min. Order Quantity</label><input type="number"
                  name="min_order_qty" class="np-input" placeholder="1" min="1"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Max. Order Quantity per
                  Customer</label><input type="number" name="meta_data[max_order_qty]" class="np-input" placeholder="10"
                  min="1"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 5: SEO & META ══ --}}
    <div class="np-tab-panel" id="np-tab-seo">
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔍</span><span class="np-card-title">Google Search
                Preview</span></div>
            <div class="np-card-body">
              <div class="np-seo-preview">
                <div class="np-seo-url" id="npSeoUrl">https://{{ request()->getHost() }}/p/your-product-slug</div>
                <div class="np-seo-title" id="npSeoTitle">Product Name — {{ config('app.name') }}</div>
                <div class="np-seo-desc" id="npSeoDesc">Add a meta description to preview it here…</div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">✍️</span><span class="np-card-title">SEO
                Details</span></div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label">SEO Title <span class="np-req">*</span></label>
                <input type="text" name="meta_title" id="npSeoTitleInp" class="np-input"
                  placeholder="e.g. Cream Cheese 864g — Buy Online | {{ config('app.name') }}" oninput="npUpdateSEO()">
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="np-hint">Recommended: 50–60 characters</span>
                  <span id="npSeoTitleCC" class="np-cc np-cc-ok">0 chars</span>
                </div>
              </div>
              <div class="np-form-group">
                <label class="np-label">Meta Description <span class="np-req">*</span></label>
                <textarea name="meta_description" id="npSeoDescInp" class="np-textarea" rows="3"
                  placeholder="Concise product description for search engines…" oninput="npUpdateSEO()"></textarea>
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="np-hint">Recommended: 150–160 characters</span>
                  <span id="npSeoDescCC" class="np-cc np-cc-ok">0 chars</span>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">URL Slug</label>
                <input type="text" name="slug" id="npSeoSlug" class="np-input np-mono" placeholder="product-name-slug"
                  oninput="npUpdateSEO()">
                <div class="np-hint">Auto-generated from product name. Lowercase, hyphens only.</div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📣</span><span class="np-card-title">Social Meta (OG
                Tags)</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">OG Title</label><input type="text"
                  name="meta_data[og_title]" class="np-input"
                  placeholder="e.g. Great Product — {{ config('app.name') }}"></div>
              <div class="np-form-group"><label class="np-label">OG Description</label><textarea
                  name="meta_data[og_desc]" class="np-textarea" rows="2"
                  placeholder="Description for social media sharing…"></textarea></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">OG Image URL</label><input
                  type="url" name="meta_data[og_image]" class="np-input"
                  placeholder="https://cdn.example.com/product-og.jpg"></div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🏷️</span><span class="np-card-title">SEO
                Keywords</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Focus Keyword</label><input type="text"
                  name="meta_data[focus_keyword]" class="np-input" placeholder="e.g. cream cheese online"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Additional Keywords</label>
                <div class="np-tag-wrap" id="npSeoKWrap" onclick="this.querySelector('input').focus()">
                  <input type="text" id="npSeoKInput" class="np-input" placeholder="Add keyword, press Enter…"
                    onkeydown="npAddTag(event,'npSeoKWrap','npSeoKInput','t-blue','meta_data[seo_keywords][]')">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🤖</span><span class="np-card-title">Robots &amp;
                Indexing</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:0">
                <div>
                  <div class="np-tlbl">Index this page</div>
                  <div class="np-tdsc">Allow search engines to index</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_index]" value="1" checked><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Follow Links</div>
                  <div class="np-tdsc">Allow crawlers to follow</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_follow]" value="1" checked><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Canonical URL</div>
                  <div class="np-tdsc">Set as canonical version</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_canonical]" value="1" checked><span
                    class="np-tog-track"></span></label>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🗃️</span><span class="np-card-title">Structured Data
                / Schema</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">GTIN (for Google Shopping)</label><input type="text"
                  name="meta_data[gtin]" class="np-input np-mono" placeholder="e.g. 3073781039180"></div>
              <div class="np-form-group"><label class="np-label">Google Product Category</label><input type="text"
                  name="meta_data[google_category]" class="np-input"
                  placeholder="e.g. Food &amp; Drink > Dairy > Cheese"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Schema Type</label><select
                  name="meta_data[schema_type]" class="np-select">
                  <option>Product</option>
                  <option>FoodProduct</option>
                  <option>IndividualProduct</option>
                </select></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 6: MEDIA ══ --}}
    <div class="np-tab-panel" id="np-tab-media">
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🖼️</span><span class="np-card-title">Product
                Thumbnail</span><span class="np-card-subtitle">Ratio 1:1</span></div>
            <div class="np-card-body">
              <label class="np-label">Main Thumbnail <span class="np-req">*</span></label>
              <label class="d-inline-block m-0 position-relative error-wrapper">
                <img class="img--176 border" id="viewer" src="{{ asset('assets/admin/img/upload-img.png') }}"
                  alt="thumbnail">
                <div class="icon-file-group">
                  <div class="icon-file">
                    <input type="file" name="image" id="customFileEg1" class="custom-file-input d-none"
                      accept=".jpg,.png,.webp,.jpeg,.gif,.bmp|image/*" required>
                    <i class="tio-edit"></i>
                  </div>
                </div>
              </label>
              <div class="np-hint mt-2">JPG, PNG, WEBP — max 2MB — 1:1 ratio</div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🖼️</span><span class="np-card-title">Product Gallery
                Images</span><span class="np-card-subtitle">Max 8 · JPG/PNG/WEBP · 2MB each</span></div>
            <div class="np-card-body">
              <div class="np-upload-zone" id="npUploadZone" onclick="document.getElementById('npFileInput').click()"
                ondragover="event.preventDefault();this.classList.add('drag')"
                ondragleave="this.classList.remove('drag')" ondrop="npHandleDrop(event)">
                <div class="np-upload-icon">📤</div>
                <div class="np-upload-title">Drag &amp; drop images here</div>
                <div class="np-upload-sub">or <span class="np-upload-link">browse from your computer</span></div>
                <div class="np-upload-sub" style="margin-top:5px;font-size:11px;color:#b0bec5">First image = main
                  gallery image</div>
                <input type="file" id="npFileInput" name="images[]" multiple accept="image/*" style="display:none"
                  onchange="npHandleFiles(event)">
              </div>
              <div class="np-img-grid" id="npImagePreviews"></div>

              {{-- Existing spartan multi-image picker for backend --}}
              <div class="d-flex __gap-12px __new-coba overflow-x-auto pb-2 mt-3" id="coba"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🎥</span><span class="np-card-title">Product
                Video</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Video URL (YouTube / Vimeo)</label><input type="url"
                  name="meta_data[video_url]" class="np-input" placeholder="https://www.youtube.com/watch?v=…"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Video Title</label><input
                  type="text" name="meta_data[video_title]" class="np-input" placeholder="e.g. Product Demo"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📄</span><span class="np-card-title">Documents</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Product Datasheet URL</label><input type="url"
                  name="meta_data[datasheet_url]" class="np-input" placeholder="https://…/datasheet.pdf"></div>
              <div class="np-form-group"><label class="np-label">Certificate of Analysis</label><input type="url"
                  name="meta_data[cert_url]" class="np-input" placeholder="https://…/cert.pdf"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">User Manual / Recipe
                  Guide</label><input type="url" name="meta_data[manual_url]" class="np-input"
                  placeholder="https://…/manual.pdf"></div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📐</span><span class="np-card-title">Image
                Guidelines</span></div>
            <div class="np-card-body">
              <div style="font-size:12.5px;color:#3d5166;line-height:2">
                <div>📌 <b>Main image:</b> White background, centred</div>
                <div>📐 <b>Minimum size:</b> 800×800 px</div>
                <div>🎯 <b>Recommended:</b> 1500×1500 px</div>
                <div>📁 <b>Formats:</b> JPG, PNG, WEBP</div>
                <div>📦 <b>Max file size:</b> 2 MB per image</div>
                <div>🖼️ <b>Additional:</b> Multiple angles, label, usage shots</div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">♿</span><span class="np-card-title">Image Alt
                Tags</span><span class="np-card-subtitle">For accessibility &amp; SEO</span></div>
            <div class="np-card-body">
              <div id="npAltTagsContainer">
                <div class="np-hint" style="font-size:12px">Upload images above to add alt tags.</div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔄</span><span class="np-card-title">360° View &amp;
                AR Assets</span></div>
            <div class="np-card-body">
              <div class="np-trow" style="padding-top:0">
                <div>
                  <div class="np-tlbl">Enable 360° View</div>
                  <div class="np-tdsc">Upload a sequence of images</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[enable_360]" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Enable AR</div>
                  <div class="np-tdsc">Requires USDZ / GLB 3D model</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[enable_ar]" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">3D Model File
                  URL (.glb / .usdz)</label><input type="url" name="meta_data[model_3d_url]" class="np-input"
                  placeholder="https://cdn.example.com/product.glb"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 7: LOGISTICS & SHIPPING ══ --}}
    <div class="np-tab-panel" id="np-tab-logistics">
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">⚖️</span><span class="np-card-title">Incoterms
                (International Trade Terms)</span></div>
            <div class="np-card-body">
              <div class="np-info-box">📌 Incoterms define who is responsible for shipping, insurance, and import duties
                between seller and buyer.</div>
              <div class="np-inco-grid">
                @foreach(['EXW' => 'Ex Works', 'FCA' => 'Free Carrier', 'CPT' => 'Carriage Paid To', 'CIP' => 'Carriage & Ins. Paid', 'FOB' => 'Free On Board', 'CFR' => 'Cost & Freight', 'CIF' => 'Cost, Ins. & Freight', 'DPU' => 'Delivered at Place Unloaded', 'DAP' => 'Delivered at Place', 'DDP' => 'Delivered Duty Paid', 'FAS' => 'Free Alongside Ship', 'DAT' => 'Delivered at Terminal'] as $code => $label)
                  <div class="np-inco-pill" onclick="this.classList.toggle('sel')">{{ $code }}<span
                      class="np-inco-sub">{{ $label }}</span><input type="hidden"
                      name="meta_data[incoterm_{{ strtolower($code) }}]" value="0"></div>
                @endforeach
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">✈️</span><span class="np-card-title">Shipping
                Methods</span></div>
            <div class="np-card-body">
              <div class="np-chk-grid">
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="sea_fcl"> 🚢 Sea Freight (FCL)</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="sea_lcl"> 📦 Sea Freight (LCL)</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="air"> ✈️ Air Freight</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="rail"> 🚂 Rail Freight</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="road"> 🚚 Road / Truck</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="express"> 📮 Express (DHL/FedEx)</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="same_day"> 🛵 Same-Day Local</label>
                <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[ship][]"
                    value="drone"> 🚁 Drone Delivery</label>
              </div>
              <div class="np-divider"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Preferred
                    Carrier</label><input type="text" name="meta_data[preferred_carrier]" class="np-input"
                    placeholder="e.g. DHL, FedEx, Aramex"></div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Port of Loading</label><input
                    type="text" name="meta_data[port_of_loading]" class="np-input" placeholder="e.g. Hamad Port, Doha">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">💰</span><span class="np-card-title">Shipping
                Rates</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Free Shipping Threshold</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[free_ship_threshold]" class="np-input" placeholder="150" step="0.01"></div>
                </div>
                <div class="np-form-group"><label class="np-label">Standard Shipping Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[std_ship_fee]" class="np-input" placeholder="15.00" step="0.01"></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Express Shipping Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[exp_ship_fee]" class="np-input" placeholder="30.00" step="0.01"></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">International Shipping
                    Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[intl_ship_fee]" class="np-input" placeholder="60.00" step="0.01"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">⚠️</span><span class="np-card-title">Dangerous Goods
                &amp; Restrictions</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Dangerous Goods Classification</label><select
                  name="meta_data[dangerous_goods]" class="np-select">
                  <option>Not Dangerous</option>
                  <option>Class 1 — Explosives</option>
                  <option>Class 2 — Gases</option>
                  <option>Class 3 — Flammable Liquids</option>
                  <option>Class 4 — Flammable Solids</option>
                  <option>Class 8 — Corrosives</option>
                  <option>Class 9 — Miscellaneous</option>
                </select></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Air Freight
                    Restriction</label><select name="meta_data[air_restriction]" class="np-select">
                    <option>No restriction</option>
                    <option>Cargo aircraft only</option>
                    <option>Prohibited</option>
                  </select></div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Temperature Controlled
                    Shipping</label><select name="meta_data[temp_controlled]" class="np-select">
                    <option>Not required</option>
                    <option>Chilled (2–8°C)</option>
                    <option>Frozen (−18°C)</option>
                  </select></div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🌐</span><span class="np-card-title">Export
                Markets</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Primary Export Regions</label>
                <div class="np-chk-grid">
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="gcc"> 🌍 GCC</label>
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="me"> 🌍 Middle East</label>
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="africa"> 🌍 Africa</label>
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="europe"> 🌍 Europe</label>
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="na"> 🌎 North America</label>
                  <label class="np-chk-item" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[export][]"
                      value="apac"> 🌏 Asia Pacific</label>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Restricted Countries</label>
                <div class="np-tag-wrap" id="npRestrictWrap" onclick="this.querySelector('input').focus()">
                  <input type="text" id="npRestrictInput" class="np-input" placeholder="Add country, press Enter…"
                    onkeydown="npAddTag(event,'npRestrictWrap','npRestrictInput','t-orange','meta_data[restricted_countries][]')">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🛂</span><span class="np-card-title">Customs &amp;
                Import Details</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">HS Tariff Code</label><input type="text"
                  name="meta_data[hs_tariff]" class="np-input np-mono" placeholder="e.g. 0406.10.00"></div>
              <div class="np-form-group"><label class="np-label">Customs Description</label><input type="text"
                  name="meta_data[customs_desc]" class="np-input" placeholder="e.g. Fresh cream cheese"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Import Duty Rate</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[import_duty]" class="np-input"
                      placeholder="5" step="0.1"><span class="np-isfx">%</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">VAT / GST Rate</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[vat_rate]" class="np-input"
                      placeholder="5" step="0.1"><span class="np-isfx">%</span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📦</span><span class="np-card-title">Pallet &amp;
                Carton Details</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Units per Carton</label><input type="number"
                    name="meta_data[units_per_carton]" class="np-input" placeholder="12"></div>
                <div class="np-form-group"><label class="np-label">Cartons per Pallet</label><input type="number"
                    name="meta_data[cartons_per_pallet]" class="np-input" placeholder="48"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Carton Length</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_l]" class="np-input"
                      placeholder="0.0"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Carton Width</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_w]" class="np-input"
                      placeholder="0.0"><span class="np-isfx">cm</span></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Carton Height</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_h]" class="np-input"
                      placeholder="0.0"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Carton Gross Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_kg]" class="np-input"
                      placeholder="0.0"><span class="np-isfx">kg</span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔍</span><span class="np-card-title">Quality
                Inspection</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Pre-shipment Inspection</div>
                  <div class="np-tdsc">Third-party QC before dispatch</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[pre_ship_inspection]" value="1"
                    checked><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Lab Testing Available</div>
                  <div class="np-tdsc">SGS / Intertek / Bureau Veritas</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[lab_testing]" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Factory Audit Report</div>
                  <div class="np-tdsc">Available on request</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[factory_audit]" value="1"
                    checked><span class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">Inspection
                  Agency</label><input type="text" name="meta_data[inspection_agency]" class="np-input"
                  placeholder="e.g. SGS, Intertek, QIMA"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 8: REVIEWS & ANALYTICS ══ --}}
    <div class="np-tab-panel" id="np-tab-reviews">
      <div class="np-stat-grid">
        <div class="np-stat-card">
          <div class="np-stat-val">—</div>
          <div class="np-stat-lbl">Avg. Rating</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:var(--np-success)">0</div>
          <div class="np-stat-lbl">Total Reviews</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:#0ea5e9">0</div>
          <div class="np-stat-lbl">Product Views</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:var(--np-warn)">—</div>
          <div class="np-stat-lbl">Conversion Rate</div>
        </div>
      </div>
      <div class="np-grid">
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📊</span><span class="np-card-title">Rating
                Breakdown</span></div>
            <div class="np-card-body">
              <div class="np-chart-row"><span class="np-chart-lbl">5 ★</span>
                <div class="np-chart-bar-wrap">
                  <div class="np-chart-bar-fill" style="width:0%;background:#00a550"></div>
                </div><span class="np-chart-count">0</span>
              </div>
              <div class="np-chart-row"><span class="np-chart-lbl">4 ★</span>
                <div class="np-chart-bar-wrap">
                  <div class="np-chart-bar-fill" style="width:0%;background:#34d399"></div>
                </div><span class="np-chart-count">0</span>
              </div>
              <div class="np-chart-row"><span class="np-chart-lbl">3 ★</span>
                <div class="np-chart-bar-wrap">
                  <div class="np-chart-bar-fill" style="width:0%;background:#f59e0b"></div>
                </div><span class="np-chart-count">0</span>
              </div>
              <div class="np-chart-row"><span class="np-chart-lbl">2 ★</span>
                <div class="np-chart-bar-wrap">
                  <div class="np-chart-bar-fill" style="width:0%;background:#f97316"></div>
                </div><span class="np-chart-count">0</span>
              </div>
              <div class="np-chart-row"><span class="np-chart-lbl">1 ★</span>
                <div class="np-chart-bar-wrap">
                  <div class="np-chart-bar-fill" style="width:0%;background:#e2001a"></div>
                </div><span class="np-chart-count">0</span>
              </div>
              <div class="np-hint mt-2">Reviews will appear here after the product is published.</div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">💬</span><span class="np-card-title">Customer
                Reviews</span><span class="np-card-subtitle">Will appear after publishing</span></div>
            <div class="np-card-body">
              <div class="np-hint">No reviews yet. Reviews will appear here after the product is published and customers
                leave feedback.</div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📩</span><span class="np-card-title">Inquiry / RFQ
                Settings</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Accept Inquiries</div>
                  <div class="np-tdsc">Allow buyers to send RFQs</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[accept_inquiries]" value="1"
                    checked><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Auto-Reply Enabled</div>
                  <div class="np-tdsc">Respond instantly to new inquiries</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[auto_reply]" value="1" checked><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Bulk Inquiry Form</div>
                  <div class="np-tdsc">Show bulk order form on page</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[bulk_inquiry]" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">Auto-Reply
                  Message</label><textarea name="meta_data[auto_reply_msg]" class="np-textarea" rows="2"
                  placeholder="Thank you for your inquiry! We will get back to you within 24 hours…"></textarea></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📈</span><span class="np-card-title">Review
                Settings</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Allow Reviews</div>
                  <div class="np-tdsc">Customers can leave reviews</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[allow_reviews]" value="1"
                    checked><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Verified Purchase Only</div>
                  <div class="np-tdsc">Only buyers can review</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[verified_only]" value="1"><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Auto-publish Reviews</div>
                  <div class="np-tdsc">No manual moderation needed</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[auto_publish_reviews]" value="1"
                    checked><span class="np-tog-track"></span></label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ACTION BAR --}}
    <div class="np-action-bar">
      <div class="np-action-info">Last saved: <strong id="npLastSaved">Never</strong></div>
      <div class="np-btn-row">
        <a href="{{ route('admin.item.list') }}" class="np-btn np-btn-ghost">Cancel</a>
        <button type="button" class="np-btn np-btn-outline" onclick="npPrevTab()">Previous</button>
        <button type="button" class="np-btn np-btn-outline" onclick="npSaveDraft()">Save Draft</button>
        <button type="button" class="np-btn np-btn-outline" onclick="npNextTab()">Next</button>
        <button type="submit" id="submitButton" form="item_form" class="np-btn np-btn-primary">Publish
          Product</button>
      </div>
    </div>

  </form>

  {{-- Brand modal must live OUTSIDE #item_form: global .custom-validation iterates ALL descendant inputs and calls .rules().
       Inputs with form="npBrandForm" still matched .find() and broke jQuery Validate (element.form !== item_form). --}}
  <div class="modal fade" id="npBrandModal" tabindex="-1" role="dialog" aria-labelledby="npBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npBrandForm" enctype="multipart/form-data">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="npBrandModalLabel">{{ translate('messages.add_new_brand') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="input-label">{{ translate('messages.name') }} <small class="text-danger">*</small></label>
              <input type="text" name="name[]" class="form-control" placeholder="{{ translate('messages.new_brand') }}" required>
              <input type="hidden" name="lang[]" value="default">
            </div>
            <div class="form-group mb-0">
              <label class="input-label">{{ translate('messages.Brand Logo') }} <small class="text-danger">*</small></label>
              <input type="file" name="image" class="form-control" accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
            </div>
            <small class="text-muted d-block mt-2">{{ translate('messages.JPG, JPEG, PNG Less Than 1MB (Ratio 1 : 1)') }}</small>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Category modal (quick add) --}}
  <div class="modal fade" id="npCategoryModal" tabindex="-1" role="dialog" aria-labelledby="npCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npCategoryForm" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="position" id="npCategoryPosition" value="0">
          <input type="hidden" name="parent_id" id="npCategoryParentId" value="">
          <div class="modal-header">
            <h5 class="modal-title" id="npCategoryModalLabel">Add Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="input-label">{{ translate('messages.name') }} <small class="text-danger">*</small></label>
              <input type="text" name="name[]" class="form-control" placeholder="Category name" required>
              <input type="hidden" name="lang[]" value="default">
            </div>
            <div class="form-group mb-0" id="npCategoryImageWrap" style="display:none">
              <label class="input-label">{{ translate('messages.image') }} <small class="text-danger">*</small></label>
              <input type="file" name="image" id="npCategoryImage" class="form-control" accept="image/*">
              <small class="text-muted d-block mt-2">{{ translate('messages.JPG, JPEG, PNG Less Than 1MB (Ratio 1 : 1)') }}</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Unit modal (quick add) --}}
  <div class="modal fade" id="npUnitModal" tabindex="-1" role="dialog" aria-labelledby="npUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npUnitForm">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="npUnitModalLabel">{{ translate('messages.add_new_unit') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label class="input-label">{{ translate('messages.unit') }} <small class="text-danger">*</small></label>
              <input type="text" name="unit[]" class="form-control" placeholder="{{ translate('messages.unit') }}" required>
              <input type="hidden" name="lang[]" value="default">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Instacart option modal (quick add) --}}
  <div class="modal fade" id="npInstacartOptionModal" tabindex="-1" role="dialog" aria-labelledby="npInstacartOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npInstacartOptionForm">
          @csrf
          <input type="hidden" name="type" id="npInstacartOptionType" value="">
          <div class="modal-header">
            <h5 class="modal-title" id="npInstacartOptionModalLabel">Add Option</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label class="input-label">{{ translate('messages.name') }} <small class="text-danger">*</small></label>
              <input type="text" name="name" class="form-control" placeholder="Option name" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Item type label modal (quick add) --}}
  <div class="modal fade" id="npItemTypeModal" tabindex="-1" role="dialog" aria-labelledby="npItemTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npItemTypeForm">
          <div class="modal-header">
            <h5 class="modal-title" id="npItemTypeModalLabel">Item Type Label</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label class="input-label">Item type name <small class="text-danger">*</small></label>
              <input type="text" id="npItemTypeNameInput" class="form-control" placeholder="e.g. Vegan, Organic, Ready-to-eat" required>
              <div class="d-flex align-items-center mt-3" style="gap:10px">
                <label class="m-0" style="font-weight:600">Veg?</label>
                <label class="np-tog m-0">
                  <input type="checkbox" id="npItemTypeIsVeg">
                  <span class="np-tog-track"></span>
                </label>
              </div>
              <small class="text-muted d-block mt-2">This creates a new selectable item type and sets veg/non-veg automatically.</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Product select option modal (Origin & Seller quick add) --}}
  <div class="modal fade" id="npProductSelectOptionModal" tabindex="-1" role="dialog" aria-labelledby="npProductSelectOptionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npProductSelectOptionForm">
          @csrf
          <input type="hidden" name="type" id="npProductSelectOptionType" value="">
          <div class="modal-header">
            <h5 class="modal-title" id="npProductSelectOptionModalLabel">Add Option</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label class="input-label">{{ translate('messages.name') }} <small class="text-danger">*</small></label>
              <input type="text" name="name" class="form-control" placeholder="Option name" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Custom tab modal --}}
  <div class="modal fade" id="npCustomTabModal" tabindex="-1" role="dialog" aria-labelledby="npCustomTabModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npCustomTabForm">
          <div class="modal-header">
            <h5 class="modal-title" id="npCustomTabModalLabel">Add New Tab</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="input-label">Tab title <small class="text-danger">*</small></label>
              <input type="text" id="npCustomTabTitle" class="form-control" placeholder="e.g. Certifications" required>
            </div>
            <div class="form-group mb-0">
              <label class="input-label">Icon (emoji) <small class="text-danger">*</small></label>
              <input type="text" id="npCustomTabIcon" class="form-control" placeholder="e.g. 🏷️" required maxlength="4">
              <small class="text-muted d-block mt-2">Tip: paste an emoji like 🧾, 🧊, 🏷️, 📦</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
            <button type="submit" class="btn btn-primary">{{ translate('messages.save') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<span id="message-enter-choice-values" data-text="{{ translate('enter_choice_values') }}"></span>

<div class="modal" id="food-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close foodModalClose" data-dismiss="modal"><span>&times;</span></button>
        <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"
            src="https://www.youtube.com/embed/IkoF9gPH6zs" allowfullscreen></iframe></div>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="attribute-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <button type="button" class="close attributeModalClose" data-dismiss="modal"><span>&times;</span></button>
        <div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item"
            src="https://www.youtube.com/embed/xG8fO7TXPbk" allowfullscreen></iframe></div>
      </div>
    </div>
  </div>
</div>

@includeif('admin-views.product.partials._ai_sidebar')
@endsection

@push('script_2')
  <script src="{{ asset('assets/admin') }}/js/tags-input.min.js"></script>
  <script src="{{ asset('assets/admin/js/spartan-multi-image-picker.js') }}"></script>
  {{-- Disabled legacy script: it builds broken URLs like /admin/item/NaN1add_on and conflicts with this new-product UI --}}
  {{-- <script src="{{ asset('assets/admin') }}/js/view-pages/product-index.js"></script> --}}
  <script src="{{ asset('assets/admin/js/AI/products/product-title-autofill.js') }}"></script>
  <script src="{{ asset('assets/admin/js/AI/products/product-description-autofill.js') }}"></script>
  <script src="{{ asset('assets/admin/js/AI/products/general-setup-autofill.js') }}"></script>
  <script src="{{ asset('assets/admin/js/AI/products/product-others-autofill.js') }}"></script>
  @if (Config::get('module.current_module_type') == 'food')
    <script src="{{ asset('assets/admin/js/AI/products/variation-setup-auto-fill.js') }}"></script>
  @else
    <script src="{{ asset('assets/admin/js/AI/products/other-variation-setup-auto-fill.js') }}"></script>
  @endif
  <script src="{{ asset('assets/admin/js/AI/products/seo-section-autofill.js') }}"></script>
  <script src="{{ asset('assets/admin/js/AI/products/ai-sidebar.js') }}"></script>
  <script src="{{ asset('/assets/admin/js/AI/products/compressor/image-compressor.js') }}"></script>
  <script src="{{ asset('/assets/admin/js/AI/products/compressor/compressor.min.js') }}"></script>

  <script>
    "use strict";

    const npTabIds = ['np-tab-general', 'np-tab-attributes', 'np-tab-nutrition', 'np-tab-variants', 'np-tab-seo', 'np-tab-media', 'np-tab-logistics', 'np-tab-reviews'];

    function npCurrentTabIndex() {
      const activePanel = document.querySelector('.np-tab-panel.active');
      return Math.max(npTabIds.indexOf(activePanel?.id), 0);
    }

    function npSetActionButtons() {
      const idx = npCurrentTabIndex();
      const prevBtn = document.querySelector('.np-btn-row button[onclick="npPrevTab()"]');
      const nextBtn = document.querySelector('.np-btn-row button[onclick="npNextTab()"]');
      if (prevBtn) prevBtn.disabled = idx === 0;
      if (nextBtn) nextBtn.style.display = idx === npTabIds.length - 1 ? 'none' : '';
    }

    // ═══ TABS ═══
    function npSwitchTab(btn, id) {
      document.querySelectorAll('.np-tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.np-tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(id).classList.add('active');
      npSetActionButtons();
    }

    function npOpenTabByIndex(index) {
      const targetIndex = Math.max(0, Math.min(index, npTabIds.length - 1));
      const btn = document.querySelectorAll('.np-tab-btn')[targetIndex];
      if (btn) {
        npSwitchTab(btn, npTabIds[targetIndex]);
      }
    }

    function npPrevTab() {
      npOpenTabByIndex(npCurrentTabIndex() - 1);
    }

    function npNextTab() {
      if (!npValidateCurrentTab()) {
        return;
      }
      npPersistWizardStep({ publish: false, moveNext: true });
    }

    function npValidateCurrentTab() {
      const currentTabId = npTabIds[npCurrentTabIndex()];
      const errors = [];

      if (currentTabId === 'np-tab-general') {
        if (!$('#productNameEn').val()?.trim()) errors.push('Product name is required.');
        if (!$('#npShortDesc').val()?.trim()) errors.push('Short description is required.');
        if (!$('#store_id').val()) errors.push('Store is required.');
        if (!$('#category_id').val()) errors.push('Category is required.');
        if (!(parseFloat($('#npRegPrice').val()) > 0)) errors.push('Price must be greater than 0.');
      }

      if (currentTabId === 'np-tab-media') {
        const hasThumbnail = $('#item_id').val() || ($('#customFileEg1')[0] && $('#customFileEg1')[0].files.length > 0);
        if (!hasThumbnail) errors.push('Thumbnail image is required.');
      }

      if (errors.length) {
        errors.forEach(message => toastr.error(message, { CloseButton: true, ProgressBar: true }));
        return false;
      }

      return true;
    }

    // ═══ STATUS PILLS ═══
    function npSelStatus(el, type) {
      document.querySelectorAll('.np-pill').forEach(p => { p.classList.remove('sel', 'out', 'draft'); p.querySelector('input').checked = false; });
      el.classList.add('sel');
      if (type === 'out') el.classList.add('out');
      if (type === 'draft') el.classList.add('draft');
      el.querySelector('input').checked = true;
    }

    // ═══ CHECKBOX TOGGLE ═══
    function npTogChk(el) {
      el.classList.toggle('on');
      el.querySelector('input').checked = el.classList.contains('on');
    }

    // ═══ TAG INPUT ═══
    function npAddTag(e, wId, iId, cls, fname) {
      if (e.key !== 'Enter') return; e.preventDefault();
      const inp = document.getElementById(iId); const v = inp.value.trim(); if (!v) return;
      const t = document.createElement('span'); t.className = 'np-tag ' + cls;
      t.innerHTML = `${v} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span>`;
      if (fname) { const hi = document.createElement('input'); hi.type = 'hidden'; hi.name = fname; hi.value = v; t.appendChild(hi); }
      document.getElementById(wId).insertBefore(t, inp); inp.value = '';
    }

    // ═══ IMAGES ═══
    let npImgs = [];
    function npHandleFiles(e) { Array.from(e.target.files).forEach(npAddImg); }
    function npHandleDrop(e) { e.preventDefault(); document.getElementById('npUploadZone').classList.remove('drag'); Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')).forEach(npAddImg); }
    function npAddImg(file) {
      if (npImgs.length >= 8) return;
      const r = new FileReader();
      r.onload = ev => { npImgs.push({ src: ev.target.result, alt: file.name.replace(/\.[^.]+$/, '') }); npRenderImgs(); npRenderAlt(); npUpdateQuality(); };
      r.readAsDataURL(file);
    }
    function npRenderImgs() {
      document.getElementById('npImagePreviews').innerHTML = npImgs.map((img, i) => `
              <div class="np-img-thumb"><img src="${img.src}">
              <span class="np-th-rm" onclick="npRmImg(${i})">×</span>
              ${i === 0 ? '<span class="np-th-main">MAIN</span>' : ''}</div>`).join('');
    }
    function npRmImg(i) { npImgs.splice(i, 1); npRenderImgs(); npRenderAlt(); npUpdateQuality(); }
    function npRenderAlt() {
      const c = document.getElementById('npAltTagsContainer');
      if (!npImgs.length) { c.innerHTML = '<div class="np-hint" style="font-size:12px">Upload images above to add alt tags.</div>'; return; }
      c.innerHTML = npImgs.map((img, i) => `
              <div class="np-form-group" ${i === npImgs.length - 1 ? 'style="margin-bottom:0"' : ''}>
                  <label class="np-label">Image ${i + 1} Alt Text${i === 0 ? ' <span class="np-req">*</span>' : ''}</label>
                  <input type="text" name="alt_text[]" value="${img.alt}" class="np-input" placeholder="Describe this image…">
              </div>`).join('');
    }

    // ═══ DISC CALC ═══
    function npUpdateDiscount() {
      const r = parseFloat(document.getElementById('npRegPrice')?.value) || 0;
      const s = parseFloat(document.getElementById('npSalePrice')?.value) || 0;
      // Visual only
    }

    // ═══ DESC COUNT ═══
    function npDescCount() {
      const len = document.getElementById('npShortDesc').value.length;
      const el = document.getElementById('npDescCount');
      el.textContent = len + '/160';
      el.className = 'np-cc ' + (len > 160 ? 'np-cc-over' : len > 130 ? 'np-cc-warn' : 'np-cc-ok');
    }

    // ═══ SEO UPDATE ═══
    function npUpdateSEO() {
      const name = document.getElementById('productNameEn')?.value || '';
      const ti = document.getElementById('npSeoTitleInp')?.value || '';
      const di = document.getElementById('npSeoDescInp')?.value || '';
      const sl = document.getElementById('npSeoSlug')?.value || '';
      if (document.getElementById('npSeoTitle')) document.getElementById('npSeoTitle').textContent = (ti || (name || 'Product Name') + ' — {{ config("app.name") }}').substring(0, 70);
      if (document.getElementById('npSeoDesc')) document.getElementById('npSeoDesc').textContent = (di || document.getElementById('npShortDesc')?.value || 'Add a meta description…').substring(0, 200);
      const slug = sl || name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'your-product-slug';
      if (document.getElementById('npSeoUrl')) document.getElementById('npSeoUrl').textContent = '{{ url("/") }}/p/' + slug;
      if (document.getElementById('npSeoTitleCC')) { const tl = ti.length; const tc = document.getElementById('npSeoTitleCC'); tc.textContent = tl + ' chars'; tc.className = 'np-cc ' + (tl > 60 ? 'np-cc-over' : tl > 50 ? 'np-cc-warn' : 'np-cc-ok'); }
      if (document.getElementById('npSeoDescCC')) { const dl = di.length; const dc = document.getElementById('npSeoDescCC'); dc.textContent = dl + ' chars'; dc.className = 'np-cc ' + (dl > 160 ? 'np-cc-over' : dl > 150 ? 'np-cc-warn' : 'np-cc-ok'); }
    }

    // ═══ QUALITY SCORE ═══
    function npUpdateQuality() {
      const c = [
        !!(document.getElementById('productNameEn')?.value.trim()),
        !!(document.getElementById('npBrand')?.value),
        !!(document.getElementById('npWeight')?.value.trim()),
        !!(document.getElementById('category_id')?.value),
        npImgs.length > 0, npImgs.length >= 3,
        !!(document.getElementById('npRegPrice')?.value),
        !!(document.getElementById('npShortDesc')?.value.trim()),
      ];
      const s = Math.round(c.filter(Boolean).length / c.length * 100);
      if (document.getElementById('npQualityFill')) document.getElementById('npQualityFill').style.width = s + '%';
      if (document.getElementById('npQualityPct')) document.getElementById('npQualityPct').textContent = s + '%';
      const tips = [];
      if (!c[0]) tips.push('• Add product name'); if (!c[1]) tips.push('• Select a brand');
      if (!c[7]) tips.push('• Add description'); if (!c[4]) tips.push('• Upload at least one image');
      if (!c[5]) tips.push('• Upload 3+ images'); if (!c[3]) tips.push('• Choose a category');
      if (!c[6]) tips.push('• Set a price');
      if (document.getElementById('npQualityTips')) document.getElementById('npQualityTips').innerHTML = tips.length ? tips.join('<br>') : '✅ Excellent listing quality!';
    }

    // ═══ SELLING POINT ═══
    function npAddSellingPoint() {
      const d = document.createElement('div'); d.style.cssText = 'display:flex;gap:8px;align-items:flex-start;margin-bottom:10px';
      d.innerHTML = '<span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><input type="text" name="selling_point[]" class="np-input" placeholder="Add a key selling point…">';
      document.getElementById('npAboutRows').appendChild(d);
    }

    // ═══ CUSTOM ATTRS ═══
    function npAddCustomAttr() {
      const d = document.createElement('div'); d.className = 'np-form-row'; d.style.marginBottom = '10px';
      d.innerHTML = '<input type="text" name="meta_data[custom_attr_name][]" class="np-input" placeholder="Attribute name"><input type="text" name="meta_data[custom_attr_val][]" class="np-input" placeholder="Value">';
      document.getElementById('npCustomAttrs').appendChild(d);
    }

    // ═══ VARIANT ROW ═══
    function npAddVariantRow() {
      const tr = document.createElement('tr');
      tr.innerHTML = `<td><input type="text" name="meta_data[var_name][]" class="np-input" placeholder="Variant name"></td>
              <td><input type="text" name="meta_data[var_sku][]" class="np-input np-mono" placeholder="SKU"></td>
              <td><input type="number" name="meta_data[var_price][]" class="np-input" placeholder="0.00" step="0.01"></td>
              <td><input type="number" name="meta_data[var_stock][]" class="np-input" placeholder="0"></td>
              <td><select name="meta_data[var_status][]" class="np-select" style="font-size:11.5px;padding:5px 28px 5px 8px"><option>Active</option><option>Out of Stock</option><option>Disabled</option></select></td>
              <td><button type="button" class="np-btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>`;
      document.getElementById('npVariantBody').appendChild(tr);
    }

    // ═══ DRAFT SAVE ═══
    function npSaveDraft() {
      document.getElementById('npLastSaved').textContent = `Draft — ${new Date().toLocaleTimeString()}`;
    }

    // ═══ EXISTING ADMIN SCRIPTS COMPAT ═══
    function validateImageSize(inputSelector, imageType = "Image", maxSizeMB = 2) {
      let fileInput = $(inputSelector)[0];
      if (fileInput && fileInput.files.length > 0) {
        if (fileInput.files[0].size > maxSizeMB * 1024 * 1024) {
          toastr.error(`${imageType} size should not exceed ${maxSizeMB}MB`, { CloseButton: true, ProgressBar: true });
          return false;
        }
      }
      return true;
    }

    /** Destroy/re-init Select2 after replacing <option> HTML — otherwise dropdowns look “frozen” / not editable. */
    function npDestroySelect2IfAny($el) {
      if (!$el || !$el.length) return;
      if ($el.hasClass('select2-hidden-accessible')) {
        try { $el.select2('destroy'); } catch (e) { /* ignore */ }
      }
    }
    function npInitSelect2One($el) {
      if ($el && $el.length && $.HSCore && $.HSCore.components && $.HSCore.components.HSSelect2) {
        $.HSCore.components.HSSelect2.init($el);
      }
    }
    function npReplaceCategorySelectOptions($el, html) {
      npDestroySelect2IfAny($el);
      $el.html(html);
      npInitSelect2One($el);
    }

    function npSaveDraft() {
      npPersistWizardStep({ publish: false, moveNext: false });
    }

    function npPersistWizardStep({ publish = false, moveNext = false } = {}) {
      const form = document.getElementById('item_form');
      if (!form) return;

      // Ensure custom tabs JSON is up-to-date before submit
      if (typeof npSaveCustomTabsToInput === 'function') {
        npSaveCustomTabsToInput();
      }

      $('#draft_mode').val(publish ? '0' : '1');

      if (publish) {
        let $form = $('#item_form');
        if ($form.length && typeof $form.valid === 'function' && !$form.valid()) {
          return false;
        }

        if (!validateImageSize('#customFileEg1', "Item image")) {
          return;
        }

        let fileInput = $('#customFileEg1')[0];
        if (fileInput && fileInput.files.length > 0 && fileInput.files[0].size > 2 * 1024 * 1024) {
          toastr.error('Image size should not exceed 2MB', { CloseButton: true, ProgressBar: true });
          return;
        }
      }

      const currentItemId = $('#item_id').val();
      const formData = new FormData(form);
      const url = currentItemId ? ('{{ url("admin/item/update") }}/' + currentItemId) : '{{ route('admin.item.store') }}';

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          $('#loading').show();
          $('#submitButton').attr('disabled', true);
        },
        success: function (data) {
          $('#loading').hide();
          $('#submitButton').attr('disabled', false);
          if (data.errors) {
            for (let i = 0; i < data.errors.length; i++) {
              toastr.error(data.errors[i].message, { CloseButton: true, ProgressBar: true });
            }
            return;
          }

          if (data.item_id) {
            $('#item_id').val(data.item_id);
          }

          document.getElementById('npLastSaved').textContent = new Date().toLocaleTimeString();

          if (publish) {
            toastr.success(data.success || "{{ translate('messages.product_added_successfully') }}", { CloseButton: true, ProgressBar: true });
            try { localStorage.removeItem('np_custom_tabs_draft'); } catch (e) { }
            setTimeout(function () {
              location.href = "{{ route('admin.item.list') }}";
            }, 1000);
            return;
          }

          toastr.success('Draft saved', { CloseButton: true, ProgressBar: true });
          if (moveNext) {
            npOpenTabByIndex(npCurrentTabIndex() + 1);
          }
        },
        error: function (xhr) {
          $('#loading').hide();
          $('#submitButton').attr('disabled', false);
          toastr.error(xhr.responseJSON?.message || 'Request failed', { CloseButton: true, ProgressBar: true });
        }
      });
    }

    $(document).on('change', '#discount_type', function () {
      var discountType = document.getElementById("discount_type");
      if (discountType.value === 'amount') { $('#symble').text("({{ \App\CentralLogics\Helpers::currency_symbol() }})"); }
      else { $('#symble').text("(%)"); }
    });

    $(document).ready(function () {
      $("#add_new_option_button").click(function (e) { if (typeof add_new_option_button === 'function') add_new_option_button(); });
      $('.js-select2-custom').each(function () { if ($.HSCore && $.HSCore.components && $.HSCore.components.HSSelect2) $.HSCore.components.HSSelect2.init($(this)); });

      $('#item_form').on('submit', function (e) {
        e.preventDefault();
        npPersistWizardStep({ publish: true, moveNext: false });
      });

      function initImagePicker() {
        if (!$.fn.spartanMultiImagePicker) {
          return;
        }

        $("#coba").spartanMultiImagePicker({
          fieldName: 'item_images[]',
          maxCount: 5,
          rowHeight: '176px !important',
          groupClassName: 'spartan_item_wrapper min-w-176px max-w-176px',
          maxFileSize: 1024 * 1024 * 2,
          placeholderImage: {
            image: "{{ asset('assets/admin/img/upload-img.png') }}",
            width: '176px'
          },
          dropFileLabel: "Drop Here",
          onExtensionErr: function () {
            toastr.error("{{ translate('messages.please_only_input_png_or_jpg_type_file') }}", { CloseButton: true, ProgressBar: true });
          },
          onSizeErr: function () {
            toastr.error("{{ translate('messages.file_size_too_big') }}", { CloseButton: true, ProgressBar: true });
          }
        });
      }

      initImagePicker();

      // Category cascade (DB-backed via admin.item.get-categories)
      const npModuleId = "{{ Config::get('module.current_module_id') }}";
      function npFetchChildCategories(parentId) {
        return $.get("{{ route('admin.item.get-categories') }}", {
          parent_id: parentId,
          module_id: npModuleId,
          sub_category: 1
        });
      }

      $('#category_id').on('change', function () {
        const id = $(this).val();
        if (!id) {
          npReplaceCategorySelectOptions($('#sub_category_id'), '<option value="">Select main first…</option>');
          npReplaceCategorySelectOptions($('#sub_sub_category_id'), '<option value="">Select Level 2 first…</option>');
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
          return;
        }
        npFetchChildCategories(id).done(function (data) {
          let opts = '<option value="">Select sub-category…</option>';
          $.each(data || [], function (i, v) { opts += `<option value="${v.id}">${v.text}</option>`; });
          npReplaceCategorySelectOptions($('#sub_category_id'), opts);
          npReplaceCategorySelectOptions($('#sub_sub_category_id'), '<option value="">Select Level 2 first…</option>');
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
        }).fail(function () {
          toastr.error('Failed to load sub-categories', { CloseButton: true, ProgressBar: true });
        });
      });
      $('#sub_category_id').on('change', function () {
        const id = $(this).val();
        if (!id) {
          npReplaceCategorySelectOptions($('#sub_sub_category_id'), '<option value="">Select Level 2 first…</option>');
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
          return;
        }
        npFetchChildCategories(id).done(function (data) {
          let opts = '<option value="">Select sub-sub-category…</option>';
          $.each(data || [], function (i, v) { opts += `<option value="${v.id}">${v.text}</option>`; });
          npReplaceCategorySelectOptions($('#sub_sub_category_id'), opts);
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
        }).fail(function () {
          toastr.error('Failed to load level 3 categories', { CloseButton: true, ProgressBar: true });
        });
      });

      $('#sub_sub_category_id').on('change', function () {
        const id = $(this).val();
        if (!id) {
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
          $('#npBrowseNodeId').val('');
          return;
        }
        npFetchChildCategories(id).done(function (data) {
          let opts = '<option value="">Select level 4…</option>';
          $.each(data || [], function (i, v) { opts += `<option value="${v.id}">${v.text}</option>`; });
          npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), opts);
        }).fail(function () {
          toastr.error('Failed to load level 4 categories', { CloseButton: true, ProgressBar: true });
        });
      });

      // Auto-populate Browse Node ID (fallback to category id) when selecting leaf node.
      $('#sub_sub_sub_category_id').on('change', function () {
        const id = $(this).val();
        $('#npBrowseNodeId').val(id ? String(id) : '');
      });

      $('#reset_btn').click(function () {
        $('#item_id').val('');
        $('#store_id').val(null).trigger('change');
        $('#category_id').val(null).trigger('change');
        npReplaceCategorySelectOptions($('#sub_category_id'), '<option value="">Select main first…</option>');
        npReplaceCategorySelectOptions($('#sub_sub_category_id'), '<option value="">Select Level 2 first…</option>');
        npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), '<option value="">Select Level 3 first…</option>');
        $('#npBrowseNodeId').val('');
        $('#npProductTypeKeyword').val('');
        $('#unit').val(null).trigger('change');
        $('#veg').val(0).trigger('change');
        $('#discount_type').val('percent').trigger('change');
        $('#viewer').attr('src', "{{ asset('assets/admin/img/upload-img.png') }}");
        $('#customFileEg1').val(null).trigger('change');
        $("#coba").empty();
        initImagePicker();
      });
      npSetActionButtons();
    });
  </script>

    <script>
      "use strict";

      function npOpenBrandModal() {
        const modal = $('#npBrandModal');
        if (!modal.length) return;
        // Ensure modal is not constrained by parent overflow/z-index
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npBrandForm');
        if (form) form.reset();
        modal.modal('show');
      }

      $('#npBrandForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        $.ajax({
          url: "{{ route('admin.brand.store') }}",
          method: "POST",
          data: fd,
          processData: false,
          contentType: false,
          success: function (res) {
            if (res && res.id) {
              const opt = new Option(res.name, res.id, true, true);
              document.getElementById('npBrand')?.appendChild(opt);
              $('#npBrand').val(String(res.id)).trigger('change');
              toastr.success(res.message || "{{ translate('messages.brand_added_successfully') }}", { CloseButton: true, ProgressBar: true });
              $('#npBrandModal').modal('hide');
              npUpdateQuality();
              return;
            }
            toastr.success("{{ translate('messages.brand_added_successfully') }}", { CloseButton: true, ProgressBar: true });
            $('#npBrandModal').modal('hide');
          },
          error: function (xhr) {
            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add brand', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      function npOpenCategoryModal(level) {
        const modal = $('#npCategoryModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');

        const form = document.getElementById('npCategoryForm');
        if (form) form.reset();

        // level: 0=main, 1=sub, 2=sub-sub, 3=level4
        const position = Number(level);
        $('#npCategoryPosition').val(String(position));

        let parentId = '';
        if (position === 1) {
          parentId = $('#category_id').val() || '';
          if (!parentId) {
            toastr.warning("{{ translate('messages.select_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
        }
        if (position === 2) {
          parentId = $('#sub_category_id').val() || '';
          if (!($('#category_id').val() || '')) {
            toastr.warning("{{ translate('messages.select_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
          if (!parentId) {
            toastr.warning("{{ translate('messages.select_sub_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
        }
        if (position === 3) {
          parentId = $('#sub_sub_category_id').val() || '';
          if (!($('#category_id').val() || '')) {
            toastr.warning("{{ translate('messages.select_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
          if (!($('#sub_category_id').val() || '')) {
            toastr.warning("{{ translate('messages.select_sub_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
          if (!parentId) {
            toastr.warning("{{ translate('messages.select_sub_category') }}", { CloseButton: true, ProgressBar: true });
            return;
          }
        }
        $('#npCategoryParentId').val(parentId);

        // Main category requires image
        const imgWrap = $('#npCategoryImageWrap');
        const imgInput = document.getElementById('npCategoryImage');
        if (position === 0) {
          imgWrap.show();
          if (imgInput) imgInput.required = true;
        } else {
          imgWrap.hide();
          if (imgInput) imgInput.required = false;
        }

        modal.modal('show');
      }
      window.npOpenCategoryModal = npOpenCategoryModal;

      $('#npCategoryForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);
        const position = fd.get('position') || '0';

        // Ensure native required validation runs (main category needs image).
        if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
          return;
        }

        // Use item-module category store to avoid module:category middleware
        $.ajax({
          url: "{{ route('admin.item.category.store') }}/" + encodeURIComponent(position),
          method: "POST",
          dataType: "json",
          data: fd,
          processData: false,
          contentType: false,
          success: function (res) {
            if (res && res.id) {
              function npAppendOptionRefresh($sel, text, val) {
                // Keep Select2 instance; just append + select + notify Select2.
                $sel.append(new Option(text, val, true, true));
                $sel.val(String(val));
                if ($sel.hasClass('select2-hidden-accessible')) {
                  $sel.trigger('change.select2');
                } else {
                  $sel.trigger('change');
                }
              }

              if (String(res.position) === '0') {
                npAppendOptionRefresh($('#category_id'), res.name, res.id);
              } else if (String(res.position) === '1') {
                npAppendOptionRefresh($('#sub_category_id'), res.name, res.id);
              } else if (String(res.position) === '2') {
                npAppendOptionRefresh($('#sub_sub_category_id'), res.name, res.id);
              } else if (String(res.position) === '3') {
                npAppendOptionRefresh($('#sub_sub_sub_category_id'), res.name, res.id);
              }

              toastr.success(res.message || 'Category added successfully', { CloseButton: true, ProgressBar: true });
              $('#npCategoryModal').modal('hide');
              npUpdateQuality();
              return;
            }

            toastr.error((res && (res.message || res.error)) ? (res.message || res.error) : 'Failed to add category. Please try again.', { CloseButton: true, ProgressBar: true });
          },
          error: function (xhr, status) {
            if (status === 'parsererror') {
              toastr.error('Server returned an unexpected response. Please refresh and try again.', { CloseButton: true, ProgressBar: true });
              return;
            }

            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add category', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      function npOpenUnitModal() {
        const modal = $('#npUnitModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npUnitForm');
        if (form) form.reset();
        modal.modal('show');
      }
      window.npOpenUnitModal = npOpenUnitModal;

      $('#npUnitForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const fd = new FormData(form);

        if (typeof form.reportValidity === 'function' && !form.reportValidity()) return;

        $.ajax({
          url: "{{ route('admin.item.unit.store') }}",
          method: "POST",
          dataType: "json",
          data: fd,
          processData: false,
          contentType: false,
          success: function (res) {
            if (res && res.id) {
              const $sel = $('#unit');
              $sel.append(new Option(res.name, res.id, true, true));
              $sel.val(String(res.id));
              if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
              else $sel.trigger('change');
              toastr.success(res.message || "{{ translate('messages.unit_added_successfully') }}", { CloseButton: true, ProgressBar: true });
              $('#npUnitModal').modal('hide');
              return;
            }
            toastr.error((res && (res.message || res.error)) ? (res.message || res.error) : 'Failed to add unit', { CloseButton: true, ProgressBar: true });
          },
          error: function (xhr, status) {
            if (status === 'parsererror') {
              toastr.error('Server returned an unexpected response. Please refresh and try again.', { CloseButton: true, ProgressBar: true });
              return;
            }
            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add unit', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      function npOpenTagsHelper() {
        const el = document.getElementById('np_tags_input');
        if (!el) return;
        el.focus();
      }
      window.npOpenTagsHelper = npOpenTagsHelper;

      function npOpenInstacartOptionModal(type) {
        const modal = $('#npInstacartOptionModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npInstacartOptionForm');
        if (form) form.reset();
        $('#npInstacartOptionType').val(String(type || ''));
        const titleMap = {
          department: 'Add Instacart Department',
          promo_label: 'Add Instacart Promo Label',
          unit_pricing_display: 'Add Instacart Unit Pricing Display',
        };
        document.getElementById('npInstacartOptionModalLabel').textContent = titleMap[type] || 'Add Option';
        modal.modal('show');
      }
      window.npOpenInstacartOptionModal = npOpenInstacartOptionModal;

      $('#npInstacartOptionForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        if (typeof form.reportValidity === 'function' && !form.reportValidity()) return;

        $.ajax({
          url: "{{ route('admin.item.instacart.options.store') }}",
          method: "POST",
          dataType: "json",
          data: $(form).serialize(),
          success: function (res) {
            if (!res || !res.name || !res.type) {
              toastr.error('Failed to add option', { CloseButton: true, ProgressBar: true });
              return;
            }

            const map = {
              department: '#npInstacartDepartment',
              promo_label: '#npInstacartPromoLabel',
              unit_pricing_display: '#npInstacartUnitPricingDisplay',
            };
            const sel = map[res.type];
            const $sel = sel ? $(sel) : $();
            if ($sel.length) {
              $sel.append(new Option(res.name, res.name, true, true));
              $sel.val(String(res.name));
              if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
              else $sel.trigger('change');
            }

            toastr.success(res.message || 'Added successfully', { CloseButton: true, ProgressBar: true });
            $('#npInstacartOptionModal').modal('hide');
          },
          error: function (xhr) {
            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add option', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      function npOpenItemTypeModal() {
        const modal = $('#npItemTypeModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npItemTypeForm');
        if (form) form.reset();
        modal.modal('show');
      }
      window.npOpenItemTypeModal = npOpenItemTypeModal;

      function npApplyItemTypeSelection({ id, name, isVeg }) {
        const $sel = $('#npItemTypeSelect');
        if (!$sel.length) return;
        if (id) {
          // Ensure option exists
          if ($sel.find(`option[value="${String(id).replace(/"/g, '\\"')}"]`).length === 0) {
            $sel.append(new Option(name, id, true, true));
          }
          $sel.val(String(id));
          if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
          else $sel.trigger('change');
        }
        document.getElementById('npItemTypeId').value = id ? String(id) : '';
        document.getElementById('npItemTypeName').value = name ? String(name) : '';
        document.getElementById('veg').value = String(isVeg ? 1 : 0);
      }

      $('#npItemTypeForm').on('submit', function (e) {
        e.preventDefault();
        const name = (document.getElementById('npItemTypeNameInput')?.value || '').trim();
        const isVeg = !!document.getElementById('npItemTypeIsVeg')?.checked;
        if (!name) return;

        $.ajax({
          url: "{{ route('admin.item.item-types.store') }}",
          method: "POST",
          dataType: "json",
          data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name,
            is_veg: isVeg ? 1 : 0,
          },
          success: function (res) {
            if (res && res.id) {
              npApplyItemTypeSelection({ id: res.id, name: res.name, isVeg: !!Number(res.is_veg) });
              toastr.success(res.message || 'Item type added successfully', { CloseButton: true, ProgressBar: true });
              $('#npItemTypeModal').modal('hide');
              return;
            }
            toastr.error('Failed to add item type', { CloseButton: true, ProgressBar: true });
          },
          error: function (xhr) {
            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add item type', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      $('#npItemTypeSelect').on('change', function () {
        const $opt = $(this).find('option:selected');
        const id = $(this).val();
        const name = $opt.text();
        const isVeg = Number($opt.data('is-veg')) === 1;
        if (!id) {
          npApplyItemTypeSelection({ id: '', name: '', isVeg: false });
          return;
        }
        npApplyItemTypeSelection({ id, name, isVeg });
      });

      function npOpenProductSelectOptionModal(type) {
        const modal = $('#npProductSelectOptionModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npProductSelectOptionForm');
        if (form) form.reset();
        $('#npProductSelectOptionType').val(String(type || ''));
        const titleMap = {
          origin_country: 'Add Country of Origin',
          seller: 'Add Seller',
        };
        document.getElementById('npProductSelectOptionModalLabel').textContent = titleMap[type] || 'Add Option';
        modal.modal('show');
      }
      window.npOpenProductSelectOptionModal = npOpenProductSelectOptionModal;

      $('#npProductSelectOptionForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        if (typeof form.reportValidity === 'function' && !form.reportValidity()) return;

        $.ajax({
          url: "{{ route('admin.item.product-select-options.store') }}",
          method: "POST",
          dataType: "json",
          data: $(form).serialize(),
          success: function (res) {
            if (!res || !res.name || !res.type) {
              toastr.error('Failed to add option', { CloseButton: true, ProgressBar: true });
              return;
            }

            const map = {
              origin_country: '#npOriginCountry',
              seller: '#npSeller',
            };
            const sel = map[res.type];
            const $sel = sel ? $(sel) : $();
            if ($sel.length) {
              $sel.append(new Option(res.name, res.name, true, true));
              $sel.val(String(res.name));
              if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
              else $sel.trigger('change');
            }

            toastr.success(res.message || 'Added successfully', { CloseButton: true, ProgressBar: true });
            $('#npProductSelectOptionModal').modal('hide');
          },
          error: function (xhr) {
            const data = xhr.responseJSON || {};
            if (data.errors) {
              Object.keys(data.errors).forEach(function (k) {
                const msg = Array.isArray(data.errors[k]) ? data.errors[k][0] : data.errors[k];
                toastr.error(msg, { CloseButton: true, ProgressBar: true });
              });
              return;
            }
            toastr.error(data.message || 'Failed to add option', { CloseButton: true, ProgressBar: true });
          }
        });
      });

      // ── Custom tabs (builder) ────────────────────────────────────────────
      let npCustomTabs = [];
      const npCustomTabsDraftKey = 'np_custom_tabs_draft';

      function npLoadCustomTabsFromInput() {
        const raw = document.getElementById('custom_tabs_json')?.value || '';
        if (raw) {
          try { return JSON.parse(raw) || []; } catch { /* fallthrough */ }
        }
        // If creating a new item and nothing in hidden input yet, restore from local draft
        const currentItemId = document.getElementById('item_id')?.value || '';
        if (!currentItemId) {
          try {
            const draft = localStorage.getItem(npCustomTabsDraftKey);
            return draft ? (JSON.parse(draft) || []) : [];
          } catch { return []; }
        }
        return [];
      }
      function npSaveCustomTabsToInput() {
        const el = document.getElementById('custom_tabs_json');
        if (el) el.value = JSON.stringify(npCustomTabs || []);
        // Keep a draft until the item is published/saved
        const currentItemId = document.getElementById('item_id')?.value || '';
        if (!currentItemId) {
          try { localStorage.setItem(npCustomTabsDraftKey, el?.value || '[]'); } catch { /* ignore */ }
        }
      }
      function npUid(prefix = 'ct') {
        return `${prefix}_` + Math.random().toString(36).slice(2, 9) + Date.now().toString(36);
      }

      function npFindTab(tabId) {
        return (npCustomTabs || []).find(t => t.id === tabId);
      }
      function npFindSection(tab, sectionId) {
        return (tab?.sections || []).find(s => s.id === sectionId);
      }
      function npFindField(section, fieldId) {
        return (section?.fields || []).find(f => f.id === fieldId);
      }

      function npOpenCustomTabModal() {
        const modal = $('#npCustomTabModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npCustomTabForm');
        if (form) form.reset();
        modal.modal('show');
      }
      window.npOpenCustomTabModal = npOpenCustomTabModal;

      function npAddSection(tabId) {
        const tab = npFindTab(tabId);
        if (!tab) return;
        const title = (prompt('Section title', 'New Section') || '').trim();
        if (!title) return;
        const icon = (prompt('Section icon (emoji)', '📌') || '').trim() || '📌';
        tab.sections = tab.sections || [];
        tab.sections.push({ id: npUid('sec'), title, icon, fields: [] });
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
      }
      window.npAddSection = npAddSection;
      function npAddField(tabId, sectionId) {
        const tab = npFindTab(tabId);
        const sec = npFindSection(tab, sectionId);
        if (!sec) return;
        const label = (prompt('Field label', 'New Field') || '').trim();
        if (!label) return;
        const type = (prompt('Field type: text / textarea / number / checkbox / select', 'text') || 'text').trim().toLowerCase();
        const allowed = ['text', 'textarea', 'number', 'checkbox', 'select'];
        if (!allowed.includes(type)) return;
        const field = { id: npUid('fld'), label, type, value: (type === 'checkbox' ? false : '') };
        if (type === 'select') {
          const opts = (prompt('Select options (comma separated)', 'Option A, Option B') || '').split(',').map(s => s.trim()).filter(Boolean);
          field.options = opts;
          field.value = opts[0] || '';
        }
        sec.fields = sec.fields || [];
        sec.fields.push(field);
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
      }
      window.npAddField = npAddField;
      function npDeleteSection(tabId, sectionId) {
        const tab = npFindTab(tabId);
        if (!tab) return;
        if (!confirm('Delete this section?')) return;
        tab.sections = (tab.sections || []).filter(s => s.id !== sectionId);
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
      }
      window.npDeleteSection = npDeleteSection;
      function npDeleteField(tabId, sectionId, fieldId) {
        const tab = npFindTab(tabId);
        const sec = npFindSection(tab, sectionId);
        if (!sec) return;
        if (!confirm('Delete this field?')) return;
        sec.fields = (sec.fields || []).filter(f => f.id !== fieldId);
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
      }
      window.npDeleteField = npDeleteField;
      function npRenameSection(tabId, sectionId) {
        const tab = npFindTab(tabId);
        const sec = npFindSection(tab, sectionId);
        if (!sec) return;
        const t = prompt('New section title', sec.title || '');
        if (t != null) sec.title = t;
        const ic = prompt('New icon (emoji)', sec.icon || '');
        if (ic != null) sec.icon = ic;
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
      }
      window.npRenameSection = npRenameSection;

      function npSetFieldValue(tabId, sectionId, fieldId, value) {
        const tab = npFindTab(tabId);
        const sec = npFindSection(tab, sectionId);
        const fld = npFindField(sec, fieldId);
        if (!fld) return;
        fld.value = value;
        npSaveCustomTabsToInput();
      }
      window.npSetFieldValue = npSetFieldValue;

      function npRenderCustomTabs() {
        // Remove old injected buttons/panels
        document.querySelectorAll('[data-np-custom-tab-btn="1"]').forEach(el => el.remove());
        const mount = document.getElementById('npCustomTabsMount');
        if (!mount) return;
        mount.innerHTML = '';

        const nav = document.querySelector('.np-tab-nav');
        const addBtn = document.getElementById('npAddTabBtn');

        (npCustomTabs || []).forEach(tab => {
          const panelId = `np-tab-custom-${tab.id}`;

          // Tab button inserted before "+ Add Tab"
          if (nav && addBtn) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'np-tab-btn';
            btn.setAttribute('data-np-custom-tab-btn', '1');
            btn.textContent = `${tab.icon || '🧩'} ${tab.title || 'Custom'}`;
            btn.onclick = function () { npSwitchTab(btn, panelId); };
            // Right-click actions
            btn.oncontextmenu = function (e) {
              e.preventDefault();
              const action = prompt('Type: rename / delete', 'rename');
              if (!action) return;
              if (action.toLowerCase() === 'delete') {
                if (confirm('Delete this tab?')) {
                  npCustomTabs = npCustomTabs.filter(t => t.id !== tab.id);
                  npSaveCustomTabsToInput();
                  npRenderCustomTabs();
                }
              } else if (action.toLowerCase() === 'rename') {
                const t = prompt('New tab title', tab.title || '');
                if (t != null) tab.title = t;
                const ic = prompt('New icon (emoji)', tab.icon || '');
                if (ic != null) tab.icon = ic;
                npSaveCustomTabsToInput();
                npRenderCustomTabs();
              }
            };
            nav.insertBefore(btn, addBtn);
          }

          // Panel
          const panel = document.createElement('div');
          panel.className = 'np-tab-panel';
          panel.id = panelId;
          const sections = (tab.sections || []);
          const sectionHtml = sections.map(sec => {
            const fields = (sec.fields || []);
            const fieldsHtml = fields.map(f => {
              const safeLabel = String(f.label || '').replace(/</g, '&lt;');
              const actions = `
                <div style="display:flex;gap:6px;align-items:center">
                  <button type="button" class="np-btn-tiny" onclick="npRenameSection('${tab.id}','${sec.id}')">Edit</button>
                  <button type="button" class="np-btn-tiny del" onclick="npDeleteField('${tab.id}','${sec.id}','${f.id}')">Del</button>
                </div>
              `;
              let input = '';
              if (f.type === 'textarea') {
                input = `<textarea class="np-textarea" rows="3" oninput="npSetFieldValue('${tab.id}','${sec.id}','${f.id}', this.value)">${String(f.value ?? '').replace(/</g,'&lt;')}</textarea>`;
              } else if (f.type === 'number') {
                input = `<input type="number" class="np-input" value="${String(f.value ?? '').replace(/"/g,'&quot;')}" oninput="npSetFieldValue('${tab.id}','${sec.id}','${f.id}', this.value)">`;
              } else if (f.type === 'checkbox') {
                const checked = f.value ? 'checked' : '';
                input = `<label class="np-tog"><input type="checkbox" ${checked} onchange="npSetFieldValue('${tab.id}','${sec.id}','${f.id}', this.checked)"><span class="np-tog-track"></span></label>`;
              } else if (f.type === 'select') {
                const opts = (f.options || []).map(o => {
                  const sel = (String(o) === String(f.value)) ? 'selected' : '';
                  return `<option ${sel} value="${String(o).replace(/"/g,'&quot;')}">${String(o).replace(/</g,'&lt;')}</option>`;
                }).join('');
                input = `<select class="np-select" onchange="npSetFieldValue('${tab.id}','${sec.id}','${f.id}', this.value)">${opts}</select>`;
              } else {
                input = `<input type="text" class="np-input" value="${String(f.value ?? '').replace(/"/g,'&quot;')}" oninput="npSetFieldValue('${tab.id}','${sec.id}','${f.id}', this.value)">`;
              }

              return `
                <div class="np-form-group">
                  <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
                    <label class="np-label" style="margin:0">${safeLabel}</label>
                    ${actions}
                  </div>
                  ${input}
                </div>
              `;
            }).join('');

            return `
              <div class="np-card" style="margin-top:12px">
                <div class="np-card-header">
                  <span class="np-card-icon">${sec.icon || '📌'}</span>
                  <span class="np-card-title">${String(sec.title || 'Section').replace(/</g,'&lt;')}</span>
                  <span class="np-card-subtitle">Custom section</span>
                </div>
                <div class="np-card-body">
                  <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap">
                    <button type="button" class="np-btn-tiny" onclick="npAddField('${tab.id}','${sec.id}')">+ Add Field</button>
                    <button type="button" class="np-btn-tiny" onclick="npRenameSection('${tab.id}','${sec.id}')">Rename</button>
                    <button type="button" class="np-btn-tiny del" onclick="npDeleteSection('${tab.id}','${sec.id}')">Delete Section</button>
                  </div>
                  ${fieldsHtml || '<div class="np-hint">No fields yet. Click “+ Add Field”.</div>'}
                </div>
              </div>
            `;
          }).join('');

          panel.innerHTML = `
            <div class="np-grid">
              <div>
                <div class="np-card">
                  <div class="np-card-header">
                    <span class="np-card-icon">${tab.icon || '🧩'}</span>
                    <span class="np-card-title">${String(tab.title || 'Custom Tab').replace(/</g,'&lt;')}</span>
                    <span class="np-card-subtitle">Builder</span>
                  </div>
                  <div class="np-card-body">
                    <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap">
                      <button type="button" class="np-btn-tiny" onclick="npAddSection('${tab.id}')">+ Add Section</button>
                      <div class="np-hint" style="margin:0">Right-click tab name to rename/delete.</div>
                    </div>
                    ${sectionHtml || '<div class="np-hint">No sections yet. Click “+ Add Section”.</div>'}
                  </div>
                </div>
              </div>
              <div></div>
            </div>
          `;
          mount.appendChild(panel);
        });
      }

      $('#npCustomTabForm').on('submit', function (e) {
        e.preventDefault();
        const title = (document.getElementById('npCustomTabTitle')?.value || '').trim();
        const icon = (document.getElementById('npCustomTabIcon')?.value || '').trim();
        if (!title || !icon) return;
        npCustomTabs.push({ id: npUid('tab'), title, icon, sections: [] });
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
        $('#npCustomTabModal').modal('hide');
      });

      // Initialize from hidden input (edit mode can prefill this)
      npCustomTabs = npLoadCustomTabsFromInput();
      // Ensure hidden input reflects restored draft so it gets submitted on save
      npSaveCustomTabsToInput();
      npRenderCustomTabs();

    </script>
@endpush
