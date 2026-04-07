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
    #npAttributeModal { z-index: 2000; }
    #npCustomTabModal { z-index: 2000; }
    #npManageTabsModal { z-index: 2000; }
    #npManageSectionsModal { z-index: 2000; }
    .modal-backdrop { z-index: 1990; }

    /* Inline validation highlighting */
    .new-product-form .np-invalid {
      border-color: #ea5455 !important;
      box-shadow: 0 0 0 0.15rem rgba(234, 84, 85, .18) !important;
      background-color: #fff6f6 !important;
    }
    .new-product-form .np-field-error {
      margin-top: 6px;
      font-size: 12px;
      line-height: 1.4;
      color: #ea5455;
      font-weight: 600;
    }
    .new-product-form .select2-container--default .select2-selection--single.np-invalid,
    .new-product-form .select2-container--default .select2-selection--multiple.np-invalid {
      border-color: #ea5455 !important;
      box-shadow: 0 0 0 0.15rem rgba(234, 84, 85, .18) !important;
      background-color: #fff6f6 !important;
    }

    /* Embed legacy attribute UI into new-product card */
    #npAttrEmbed #attribute_section { margin: 0 !important; padding: 0 !important; }
    #npAttrEmbed #attribute_section > .card { border: 0 !important; box-shadow: none !important; }
    #npAttrEmbed #attribute_section > .card > .card-header { display: none !important; }
    #npAttrEmbed #attribute_section > .card > .card-body { padding: 0 !important; }
    #npAttrEmbed .customer_choice_options { flex-wrap: wrap; }
  </style>
@endpush

@section('content')
@php
  $openai_config = \App\CentralLogics\Helpers::get_business_settings('openai_config');
  $product = $product ?? null;
  $isEdit = !blank($product?->id);
  $npMeta = is_array($product?->meta_data) ? $product->meta_data : [];
  $npSeo = null;
  try {
    $npSeo = $isEdit ? \App\Models\ItemSeoData::where('item_id', $product->id)->first() : null;
  } catch (\Throwable $e) { $npSeo = null; }
  $npSeoTitle = old('meta_title', $npSeo?->title ?? '');
  $npSeoDesc = old('meta_description', $npSeo?->description ?? '');
  $npSeoSlug = old('slug', $product?->slug ?? '');
  $npSeoKeywords = old('meta_data.seo_keywords', data_get($npMeta, 'seo_keywords', []));
  if (!is_array($npSeoKeywords)) { $npSeoKeywords = []; }
  $npShip = old('meta_data.ship', data_get($npMeta, 'ship', []));
  if (!is_array($npShip)) { $npShip = []; }
  $npShipCustom = old('meta_data.ship_custom', data_get($npMeta, 'ship_custom', []));
  if (!is_array($npShipCustom)) { $npShipCustom = []; }
  $npIncoterms = old('meta_data.incoterms', data_get($npMeta, 'incoterms', []));
  if (!is_array($npIncoterms)) { $npIncoterms = []; }
  $npIncotermsCustom = old('meta_data.incoterms_custom', data_get($npMeta, 'incoterms_custom', []));
  if (!is_array($npIncotermsCustom)) { $npIncotermsCustom = []; }
  $npExport = old('meta_data.export', data_get($npMeta, 'export', []));
  if (!is_array($npExport)) { $npExport = []; }
  $npExportCustom = old('meta_data.export_custom', data_get($npMeta, 'export_custom', []));
  if (!is_array($npExportCustom)) { $npExportCustom = []; }
  $npRestricted = old('meta_data.restricted_countries', data_get($npMeta, 'restricted_countries', []));
  if (!is_array($npRestricted)) { $npRestricted = []; }
  $npSellingPoints = data_get($npMeta, 'selling_points', []);
  if (!is_array($npSellingPoints)) { $npSellingPoints = []; }
  $npCert = data_get($npMeta, 'cert', []);
  if (!is_array($npCert)) { $npCert = []; }
  $npAllergen = data_get($npMeta, 'allergen', []);
  if (!is_array($npAllergen)) { $npAllergen = []; }
  $npDietary = data_get($npMeta, 'dietary', []);
  if (!is_array($npDietary)) { $npDietary = []; }
  $npMayContain = data_get($npMeta, 'may_contain', []);
  if (!is_array($npMayContain)) { $npMayContain = []; }
  $npENumbers = data_get($npMeta, 'e_numbers', []);
  if (!is_array($npENumbers)) { $npENumbers = []; }
  $npFlavorNames = data_get($npMeta, 'flavor_names', []);
  if (!is_array($npFlavorNames)) { $npFlavorNames = []; }
  $npSizeVariants = data_get($npMeta, 'size_variants', []);
  if (!is_array($npSizeVariants)) { $npSizeVariants = []; }
  $npSwatches = data_get($npMeta, 'variant_swatches', []);
  if (!is_array($npSwatches)) { $npSwatches = []; }
  $npCatIds = [];
  try { $npCatIds = $isEdit ? (json_decode($product?->category_ids ?? '[]', true) ?: []) : []; } catch (\Throwable $e) { $npCatIds = []; }
  $npCatPos1 = collect($npCatIds)->firstWhere('position', 1)['id'] ?? null;
  $npCatPos2 = collect($npCatIds)->firstWhere('position', 2)['id'] ?? null;
  $npCatPos3 = collect($npCatIds)->firstWhere('position', 3)['id'] ?? null;
  $npCatPos4 = collect($npCatIds)->firstWhere('position', 4)['id'] ?? null;
  // Fallback: derive chain from saved deepest category_id (older items may not have category_ids for all levels)
  if ($isEdit && (!$npCatPos1 || !$npCatPos2 || !$npCatPos3) && filled($product?->category_id)) {
    $leaf = \App\Models\Category::find($product->category_id);
    $chain = [];
    $guard = 0;
    while ($leaf && $guard < 10) {
      $chain[] = $leaf;
      $leaf = $leaf->parent_id ? \App\Models\Category::find($leaf->parent_id) : null;
      $guard++;
    }
    $chain = array_reverse($chain);
    $npCatPos1 = $npCatPos1 ?? ($chain[0]->id ?? null);
    $npCatPos2 = $npCatPos2 ?? ($chain[1]->id ?? null);
    $npCatPos3 = $npCatPos3 ?? ($chain[2]->id ?? null);
    $npCatPos4 = $npCatPos4 ?? ($chain[3]->id ?? null);
  }
  $npStatus = old('status', $product?->status ?? 1);
  $npBrandId = old('brand_id', data_get($npMeta,'brand_id',''));
  $npNameAr = '';
  if ($isEdit) {
    $npNameAr = optional(optional($product?->translations)->where('key','name')->where('locale','ar')->first())->value ?? '';
  }
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
    <div class="d-flex align-items-center" style="gap:10px">
      <button type="button"
        class="btn btn-outline-secondary d-flex align-items-center rounded-8"
        style="gap:8px;height:38px"
        onclick="npOpenManageTabsModal()">
        <span style="font-size:16px;line-height:1">⚙️</span>
        <span>Manage Tabs</span>
      </button>
      <a href="{{ route('admin.item.product_gallery') }}"
        class="btn btn-outline-primary btn--primary d-flex align-items-center bg-not-hover-primary-ash rounded-8"
        style="gap:8px;height:38px">
        <img src="{{ asset('assets/admin/img/product-gallery.png') }}" class="w--22" alt="">
        <span>{{ translate('Add Info From Gallery') }}</span>
      </a>
    </div>
  </div>

  <form id="item_form" enctype="multipart/form-data" class="custom-validation new-product-form" data-ajax="true">
    <input type="hidden" id="request_type" value="admin">
    <input type="hidden" id="module_type" value="{{ Config::get('module.current_module_type') }}">
    <input type="hidden" id="item_id" name="item_id" value="{{ $isEdit ? $product->id : '' }}">
    <input type="hidden" id="draft_mode" name="draft_mode" value="0">

    {{-- ═══ TAB NAVIGATION ═══ --}}
    <div class="np-tab-nav">
      <button type="button" class="np-tab-btn active" data-tab="np-tab-general" onclick="npSwitchTab(this,'np-tab-general')">📝 General</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-attributes" onclick="npSwitchTab(this,'np-tab-attributes')">🔧 Attributes</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-nutrition" onclick="npSwitchTab(this,'np-tab-nutrition')">🧪 Nutrition &amp;
        Allergens</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-variants" onclick="npSwitchTab(this,'np-tab-variants')">🎨 Variants</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-seo" onclick="npSwitchTab(this,'np-tab-seo')">🔍 SEO &amp; Meta</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-media" onclick="npSwitchTab(this,'np-tab-media')">🖼️ Media</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-logistics" onclick="npSwitchTab(this,'np-tab-logistics')">✈️ Logistics &amp;
        Shipping</button>
      <button type="button" class="np-tab-btn" data-tab="np-tab-reviews" onclick="npSwitchTab(this,'np-tab-reviews')">⭐ Reviews &amp;
        Analytics</button>
      <button type="button" class="np-tab-btn" id="npManageTabsBtn" onclick="npOpenManageTabsModal()">⚙️ Manage Tabs</button>
      <button type="button" class="np-tab-btn" id="npAddTabBtn" onclick="npOpenCustomTabModal()">+ Add Tab</button>
    </div>
    <input type="hidden" name="custom_tabs_json" id="custom_tabs_json" value="">
    <input type="hidden" name="custom_tabs_ui_json" id="custom_tabs_ui_json" value="">

    {{-- ══════════════════════════════════════════════════ --}}
    {{-- TAB 1: GENERAL --}}
    {{-- ══════════════════════════════════════════════════ --}}
    <div class="np-tab-panel active" id="np-tab-general">
      <div class="np-grid">
        {{-- LEFT --}}
        <div>
          {{-- Category & Classification (Top) --}}
          <div class="np-card" data-np-section="general.categories">
            <div class="np-card-header">
              <span class="np-card-icon">🗂️</span>
              <span class="np-card-title">Category &amp; Classification</span>
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
                      <option value="{{ $store->id }}" @selected(old('store_id', $product?->store_id ?? '') == $store->id)>{{ $store->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Main Category <span class="np-req">*</span></label>
                  <div class="np-inline-add">
                    <select name="category_id" id="category_id" class="np-select js-select2-custom"
                      onchange="npUpdateQuality()">
                      <option value="">— Select Category —</option>
                      @foreach(\App\Models\Category::where('position', 0)->where('status', 1)->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', Config::get('module.current_module_id')); })->get() as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $npCatPos1 ?? '') == $cat->id)>{{ $cat->name }}</option>
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
                    <select name="sub_category_id" id="sub_category_id" class="np-select js-select2-custom"
                      data-selected="{{ old('sub_category_id', $npCatPos2 ?? '') }}">
                      <option value="">Select main first…</option>
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenCategoryModal(1)">+</button>
                  </div>
                </div>
                <div class="np-form-group">
                  <label class="np-label">Sub-Sub Category (Level 3)</label>
                  <div class="np-inline-add">
                    <select name="sub_sub_category_id" id="sub_sub_category_id" class="np-select js-select2-custom"
                      data-selected="{{ old('sub_sub_category_id', $npCatPos3 ?? '') }}">
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
                    <select name="sub_sub_sub_category_id" id="sub_sub_sub_category_id" class="np-select js-select2-custom"
                      data-selected="{{ old('sub_sub_sub_category_id', $npCatPos4 ?? '') }}">
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
                        <option value="{{ $unit->id }}" @selected(old('unit', $product?->unit_id ?? '') == $unit->id)>{{ $unit->unit }}</option>
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
                  <input type="hidden" name="veg" id="veg" value="{{ old('veg', $product?->veg ?? 0) }}">
                  <input type="hidden" name="meta_data[item_type_id]" id="npItemTypeId" value="{{ old('meta_data.item_type_id', data_get($npMeta,'item_type_id','')) }}">
                  <input type="hidden" name="meta_data[item_type_name]" id="npItemTypeName" value="{{ old('meta_data.item_type_name', data_get($npMeta,'item_type_name','')) }}">
                </div>
                <div class="np-form-group">
                  <label class="np-label">Tags</label>
                  <input type="text" id="np_tags_input" name="tags" class="np-input"
                    placeholder="Enter tags, comma separated">
                </div>
              </div>
            </div>
          </div>

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
                  placeholder="e.g. Kiri Spreadable Cream Cheese Squares 48 Portions 864g" required oninput="npUpdateQuality();npUpdateSEO()" value="{{ old('name.0', $product?->name ?? '') }}">
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
                    style="direction:rtl;text-align:right" value="{{ old('name.1', $npNameAr) }}">
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
                  placeholder="Detailed product description including features, usage, storage instructions…">{{ old('full_description', data_get($npMeta,'full_description','')) }}</textarea>
              </div>

              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Brand</label>
                  <div class="d-flex align-items-center" style="gap:8px">
                    <select name="brand_id" id="npBrand" class="np-select" onchange="npUpdateQuality()">
                      <option value="">Select brand…</option>
                      @foreach(\App\Models\Brand::where('status', 1)->get() as $brand)
                        <option value="{{ $brand->id }}" @selected($npBrandId == $brand->id)>{{ $brand->name }}</option>
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

          {{-- Status (part of Basic Information) --}}
          <div class="np-card">
            <div class="np-card-header">
              <span class="np-card-icon">🚦</span>
              <span class="np-card-title">Status</span>
            </div>
            <div class="np-card-body">
              <div class="np-pill-row">
                <div class="np-pill {{ (int)$npStatus === 1 ? 'sel' : '' }}" onclick="npSelStatus(this,'active')">
                  <span class="np-sdot"></span>Active
                  <input type="radio" name="status" value="1" @checked((int)$npStatus === 1) style="display:none">
                </div>
                <div class="np-pill {{ (int)$npStatus === 0 ? 'sel out' : '' }}" onclick="npSelStatus(this,'out')">
                  <span class="np-sdot"></span>Out of Stock
                  <input type="radio" name="status" value="0" @checked((int)$npStatus === 0) style="display:none">
                </div>
                <div class="np-pill {{ (int)$npStatus === 2 ? 'sel draft' : '' }}" onclick="npSelStatus(this,'draft')">
                  <span class="np-sdot"></span>Draft
                  <input type="radio" name="status" value="2" @checked((int)$npStatus === 2) style="display:none">
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
                      min="0" oninput="npUpdateDiscount();npUpdateQuality()" value="{{ old('price', $product?->price ?? '') }}">
                  </div>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Sale / Discounted Price</label>
                  <div class="np-iw">
                    <span class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span>
                    <input type="number" name="discount" id="npSalePrice" class="np-input" placeholder="0.00"
                      step="0.01" min="0" oninput="npUpdateDiscount()" value="{{ old('discount', $product?->discount ?? '') }}">
                  </div>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Discount Type</label>
                  <select name="discount_type" id="discount_type" class="np-select">
                    <option value="percent" @selected(old('discount_type', $product?->discount_type ?? 'percent') == 'percent')>Percent (%)</option>
                    <option value="amount" @selected(old('discount_type', $product?->discount_type ?? 'percent') == 'amount')>Amount ({{ \App\CentralLogics\Helpers::currency_symbol() }})</option>
                  </select>
                </div>
              </div>
              <div class="np-divider"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Promo Start Date</label>
                  <input type="date" name="promo_start_date" class="np-input" value="{{ old('promo_start_date', data_get($npMeta,'promo_start_date','')) }}">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Promo End Date</label>
                  <input type="date" name="promo_end_date" class="np-input" value="{{ old('promo_end_date', data_get($npMeta,'promo_end_date','')) }}">
                </div>
              </div>
            </div>
          </div>

          {{-- About This Item --}}
          <div class="np-card" data-np-section="general.about">
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
                    <input type="text" name="selling_point[]" class="np-input" placeholder="Add a key selling point…" value="{{ old('selling_point.'.($i-1), $npSellingPoints[$i-1] ?? '') }}">
                  </div>
                @endfor
              </div>
              <button type="button" class="np-btn-add" onclick="npAddSellingPoint()">+ Add Selling Point</button>

              <div class="np-divider" style="margin:16px 0"></div>

              <div class="np-form-group">
                <label class="np-label">Product Overview (long-form) <span class="np-opt">(Shown in "Product Description" section on Amazon A+)</span></label>
                <textarea name="meta_data[product_overview_long]" class="np-textarea" rows="4"
                  placeholder="Write a 2–4 sentence paragraph expanding on what makes this product special — flavour profile, story, occasions, origin…">{{ old('meta_data.product_overview_long', data_get($npMeta,'product_overview_long','')) }}</textarea>
              </div>
              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Usage Directions / How to Use</label>
                <textarea name="meta_data[usage_directions]" class="np-textarea" rows="2"
                  placeholder="e.g. Spread directly from the individual portion on bread, toast or crackers. Ideal chilled. Can also be used in dips, sauces, and cheesecakes.">{{ old('meta_data.usage_directions', data_get($npMeta,'usage_directions','')) }}</textarea>
              </div>
            </div>
          </div>

          {{-- Instacart Listing Details --}}
          <div class="np-card" data-np-section="general.instacart">
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
                          <option value="{{ $opt->name }}" @selected(old('meta_data.instacart_department', data_get($npMeta,'instacart_department','')) == $opt->name)>{{ $opt->name }}</option>
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
                  <input type="text" name="meta_data[instacart_aisle]" class="np-input" placeholder="e.g. Cream Cheese &amp; Soft Cheese" value="{{ old('meta_data.instacart_aisle', data_get($npMeta,'instacart_aisle','')) }}">
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
                          <option value="{{ $opt->name }}" @selected(old('meta_data.instacart_promo_label', data_get($npMeta,'instacart_promo_label','')) == $opt->name)>{{ $opt->name }}</option>
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
                          <option value="{{ $opt->name }}" @selected(old('meta_data.instacart_unit_pricing_display', data_get($npMeta,'instacart_unit_pricing_display','')) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenInstacartOptionModal('unit_pricing_display')">+</button>
                  </div>
                </div>
              </div>

              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Instacart Product Tags</label>
                <input type="text" name="meta_data[instacart_product_tags]" class="np-input" placeholder="e.g. Halal, Gluten-free, Family size" value="{{ old('meta_data.instacart_product_tags', data_get($npMeta,'instacart_product_tags','')) }}">
                <div class="np-hint">Comma-separated. Tags map to Instacart dietary filters (e.g. Organic, Vegan, Kosher, Gluten-Free).</div>
              </div>
            </div>
          </div>

        </div>

        {{-- RIGHT COLUMN --}}
        <div>
          {{-- Origin & Seller --}}
          <div class="np-card" data-np-section="general.origin_seller">
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
                        <option value="{{ $opt->name }}" @selected(old('meta_data.country_of_origin', data_get($npMeta,'country_of_origin','')) == $opt->name)>{{ $opt->name }}</option>
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
                <input type="text" name="meta_data[manufacturer]" class="np-input" placeholder="e.g. Groupe Bel S.A." value="{{ old('meta_data.manufacturer', data_get($npMeta,'manufacturer','')) }}">
              </div>

              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Seller</label>
                <div class="np-inline-add">
                  <select name="meta_data[seller]" id="npSeller" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','seller')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected(old('meta_data.seller', data_get($npMeta,'seller','')) == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('seller')">+</button>
                </div>
              </div>
            </div>
          </div>

          {{-- Visibility & Features --}}
          <div class="np-card" data-np-section="general.visibility">
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
                <label class="np-tog"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', data_get($npMeta,'is_featured',0)) == 1)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Recommended</div>
                  <div class="np-tdsc">Show in recommended section</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="is_recommended" value="1" @checked(old('is_recommended', $product?->recommended ?? 0) == 1)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Special Offer Badge</div>
                  <div class="np-tdsc">Display sale badge on listing</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="is_sale" value="1" @checked(old('is_sale', data_get($npMeta,'is_sale',0)) == 1)><span
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
                <div class="np-dlv-card {{ old('meta_data.delivery_scheduled', data_get($npMeta,'delivery_scheduled',1)) ? 'sel' : '' }}" onclick="npToggleDeliveryCard(this)">
                  <div class="np-dlv-icon">📅</div>
                  <div class="np-dlv-name">Scheduled</div>
                  <div class="np-dlv-time">Next day</div>
                  <input type="checkbox" name="meta_data[delivery_scheduled]" value="1" @checked(old('meta_data.delivery_scheduled', data_get($npMeta,'delivery_scheduled',1)) == 1) style="display:none">
                </div>
                <div class="np-dlv-card {{ old('meta_data.delivery_express', data_get($npMeta,'delivery_express',1)) ? 'sel' : '' }}" onclick="npToggleDeliveryCard(this)">
                  <div class="np-dlv-icon">⚡</div>
                  <div class="np-dlv-name">Express</div>
                  <div class="np-dlv-time">60–120 mins</div>
                  <input type="checkbox" name="meta_data[delivery_express]" value="1" @checked(old('meta_data.delivery_express', data_get($npMeta,'delivery_express',1)) == 1) style="display:none">
                </div>
                <div class="np-dlv-card {{ old('meta_data.delivery_rapid', data_get($npMeta,'delivery_rapid',0)) ? 'sel' : '' }}" onclick="npToggleDeliveryCard(this)">
                  <div class="np-dlv-icon">🏎️</div>
                  <div class="np-dlv-name">Rapid</div>
                  <div class="np-dlv-time">35 mins</div>
                  <input type="checkbox" name="meta_data[delivery_rapid]" value="1" @checked(old('meta_data.delivery_rapid', data_get($npMeta,'delivery_rapid',0)) == 1) style="display:none">
                </div>
                <div class="np-dlv-card {{ old('meta_data.delivery_click_collect', data_get($npMeta,'delivery_click_collect',0)) ? 'sel' : '' }}" onclick="npToggleDeliveryCard(this)">
                  <div class="np-dlv-icon">🏬</div>
                  <div class="np-dlv-name">Click &amp; Collect</div>
                  <div class="np-dlv-time">In-store</div>
                  <input type="checkbox" name="meta_data[delivery_click_collect]" value="1" @checked(old('meta_data.delivery_click_collect', data_get($npMeta,'delivery_click_collect',0)) == 1) style="display:none">
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
                <input type="text" name="sku" id="sku" class="np-input np-mono" placeholder="e.g. SKU-001234" value="{{ old('sku', $product?->sku ?? '') }}">
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Stock Qty</label>
                  <input type="number" name="current_stock" id="quantity" class="np-input" placeholder="0" min="0" value="{{ old('current_stock', $product?->stock ?? 0) }}">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">{{ translate('messages.Maximum_Purchase_Quantity_Limit') }}</label>
                  <input type="number" name="maximum_cart_quantity" id="cart_quantity" class="np-input" placeholder="10"
                    min="0" value="{{ old('maximum_cart_quantity', $product?->maximum_cart_quantity ?? '') }}">
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
                      placeholder="0.0" step="0.1" value="{{ old('meta_data.length', data_get($npMeta,'length','')) }}"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Width</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[width]" class="np-input" placeholder="0.0"
                      step="0.1" value="{{ old('meta_data.width', data_get($npMeta,'width','')) }}"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Height</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[height]" class="np-input"
                      placeholder="0.0" step="0.1" value="{{ old('meta_data.height', data_get($npMeta,'height','')) }}"><span class="np-isfx">cm</span></div>
                </div>
              </div>
              <div class="np-sec-head">Weight</div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Net Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[net_weight]" class="np-input"
                      placeholder="0.00" step="0.01" value="{{ old('meta_data.net_weight', data_get($npMeta,'net_weight','')) }}"><span class="np-isfx">g</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Gross Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[gross_weight]" class="np-input"
                      placeholder="0.00" step="0.01" value="{{ old('meta_data.gross_weight', data_get($npMeta,'gross_weight','')) }}"><span class="np-isfx">g</span></div>
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
                    name="meta_data[ean]" class="np-input np-mono" placeholder="e.g. 3073781039180" value="{{ old('meta_data.ean', data_get($npMeta,'ean','')) }}"></div>
                <div class="np-form-group"><label class="np-label">Internal SKU</label><input type="text"
                    name="meta_data[internal_sku]" class="np-input np-mono" placeholder="e.g. PRD-001" value="{{ old('meta_data.internal_sku', data_get($npMeta,'internal_sku','')) }}"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Manufacturer Part No.</label><input type="text"
                    name="meta_data[mpn]" class="np-input np-mono" placeholder="e.g. MPN-001" value="{{ old('meta_data.mpn', data_get($npMeta,'mpn','')) }}"></div>
                <div class="np-form-group"><label class="np-label">Model / Item Number</label><input type="text"
                    name="meta_data[model_no]" class="np-input" placeholder="e.g. MDL-2024-V1" value="{{ old('meta_data.model_no', data_get($npMeta,'model_no','')) }}"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">HS Code
                    (Customs)</label><input type="text" name="meta_data[hs_code]" class="np-input np-mono"
                    placeholder="e.g. 0406.10" value="{{ old('meta_data.hs_code', data_get($npMeta,'hs_code','')) }}"></div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Country of
                    Manufacture</label>
                  <div class="np-inline-add">
                    <select name="meta_data[country_of_manufacture]" id="npCountryOfManufacture" class="np-select js-select2-custom">
                      <option value="">Select…</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                        @foreach(\App\Models\ProductSelectOption::where('type','country_of_manufacture')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}" @selected(old('meta_data.country_of_manufacture', data_get($npMeta,'country_of_manufacture','')) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('country_of_manufacture')">+</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📦</span><span class="np-card-title">Packaging
                Specifications</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Packaging Type</label>
                  <div class="np-inline-add">
                    <select name="meta_data[packaging_type]" id="npPackagingType" class="np-select js-select2-custom">
                      <option value="">Select…</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                        @foreach(\App\Models\ProductSelectOption::where('type','packaging_type')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}" @selected(old('meta_data.packaging_type', data_get($npMeta,'packaging_type','')) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('packaging_type')">+</button>
                  </div>
                </div>
                <div class="np-form-group"><label class="np-label">Units per Pack</label><input type="number"
                    name="meta_data[units_per_pack]" class="np-input" placeholder="e.g. 24" value="{{ old('meta_data.units_per_pack', data_get($npMeta,'units_per_pack','')) }}"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Packs per Carton</label><input type="number"
                    name="meta_data[packs_per_carton]" class="np-input" placeholder="e.g. 12" value="{{ old('meta_data.packs_per_carton', data_get($npMeta,'packs_per_carton','')) }}"></div>
                <div class="np-form-group">
                  <label class="np-label">Recyclable Packaging</label>
                  <div class="np-inline-add">
                    <select name="meta_data[recyclable]" id="npRecyclable" class="np-select js-select2-custom">
                      <option value="">Select…</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                        @foreach(\App\Models\ProductSelectOption::where('type','recyclable')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}" @selected(old('meta_data.recyclable', data_get($npMeta,'recyclable','')) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('recyclable')">+</button>
                  </div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Package Material</label>
                  <input type="text" name="meta_data[package_material]" class="np-input" placeholder="e.g. Coated paperboard, LDPE film" value="{{ old('meta_data.package_material', data_get($npMeta,'package_material','')) }}">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Package Colour</label>
                  <input type="text" name="meta_data[package_colour]" class="np-input" placeholder="e.g. White & Blue" value="{{ old('meta_data.package_colour', data_get($npMeta,'package_colour','')) }}">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🌡️</span><span class="np-card-title">Storage &amp;
                Shelf Life</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group">
                  <label class="np-label">Storage Type</label>
                  <div class="np-inline-add">
                    <select name="meta_data[storage_type]" id="npStorageType" class="np-select js-select2-custom">
                      <option value="">Select…</option>
                      @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                        @foreach(\App\Models\ProductSelectOption::where('type','storage_type')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                          <option value="{{ $opt->name }}" @selected(old('meta_data.storage_type', data_get($npMeta,'storage_type','')) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('storage_type')">+</button>
                  </div>
                </div>
                <div class="np-form-group"><label class="np-label">Temperature Range</label>
                  <div class="np-iw sfx"><input type="text" name="meta_data[temp_range]" class="np-input"
                      placeholder="2 – 8" value="{{ old('meta_data.temp_range', data_get($npMeta,'temp_range','')) }}"><span class="np-isfx">°C</span></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Shelf Life from Production</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[shelf_life_days]" class="np-input"
                      placeholder="e.g. 180" value="{{ old('meta_data.shelf_life_days', data_get($npMeta,'shelf_life_days','')) }}"><span class="np-isfx">days</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Min. Days on Delivery</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[min_days_delivery]" class="np-input"
                      placeholder="e.g. 30" value="{{ old('meta_data.min_days_delivery', data_get($npMeta,'min_days_delivery','')) }}"><span class="np-isfx">days</span></div>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Storage
                  Instructions</label><textarea name="meta_data[storage_instructions]" class="np-textarea" rows="2"
                  placeholder="e.g. Once opened, keep refrigerated and consume within 3 days.">{{ old('meta_data.storage_instructions', data_get($npMeta,'storage_instructions','')) }}</textarea></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">✏️</span><span class="np-card-title">Custom
                Attributes</span><span class="np-card-subtitle">Key–Value pairs</span></div>
            <div class="np-card-body" style="padding-bottom:12px">
              <div class="np-info-box">💡 Add product-specific attributes shown in the specifications table on the
                product page.</div>
              <div id="npCustomAttrs">
                @php
                  $npAttrNames = old('meta_data.custom_attr_name', data_get($npMeta,'custom_attr_name', []));
                  $npAttrVals = old('meta_data.custom_attr_val', data_get($npMeta,'custom_attr_val', []));
                  if (!is_array($npAttrNames)) { $npAttrNames = []; }
                  if (!is_array($npAttrVals)) { $npAttrVals = []; }
                  $npAttrRows = max(count($npAttrNames), count($npAttrVals), 2);
                @endphp
                @for($i=0;$i<$npAttrRows;$i++)
                  <div class="np-form-row" style="margin-bottom:10px">
                    <input type="text" name="meta_data[custom_attr_name][]" class="np-input" placeholder="Attribute name (e.g. Fat Content)" value="{{ $npAttrNames[$i] ?? '' }}">
                    <input type="text" name="meta_data[custom_attr_val][]" class="np-input" placeholder="Value (e.g. 24g per 100g)" value="{{ $npAttrVals[$i] ?? '' }}">
                  </div>
                @endfor
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
              <div class="np-form-group">
                <label class="np-label">Product Type</label>
                <div class="np-inline-add">
                  <select name="meta_data[product_type]" id="npProductType" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','product_type')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected(old('meta_data.product_type', data_get($npMeta,'product_type','')) == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      <option value="Simple Product" @selected(old('meta_data.product_type', data_get($npMeta,'product_type',''))=='Simple Product')>Simple Product</option>
                      <option value="Variable Product" @selected(old('meta_data.product_type', data_get($npMeta,'product_type',''))=='Variable Product')>Variable Product</option>
                      <option value="Bundle / Multipack" @selected(old('meta_data.product_type', data_get($npMeta,'product_type',''))=='Bundle / Multipack')>Bundle / Multipack</option>
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('product_type')">+</button>
                </div>
              </div>

              <div class="np-form-group">
                <label class="np-label">Condition</label>
                <div class="np-inline-add">
                  <select name="meta_data[condition]" id="npCondition" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','condition')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected(old('meta_data.condition', data_get($npMeta,'condition','')) == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      <option value="New" @selected(old('meta_data.condition', data_get($npMeta,'condition',''))=='New')>New</option>
                      <option value="Refurbished" @selected(old('meta_data.condition', data_get($npMeta,'condition',''))=='Refurbished')>Refurbished</option>
                      <option value="Used" @selected(old('meta_data.condition', data_get($npMeta,'condition',''))=='Used')>Used</option>
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('condition')">+</button>
                </div>
              </div>

              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">Age Restriction</label>
                <div class="np-inline-add">
                  <select name="meta_data[age_restriction]" id="npAgeRestriction" class="np-select js-select2-custom">
                    <option value="">None</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','age_restriction')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected(old('meta_data.age_restriction', data_get($npMeta,'age_restriction','')) == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      <option value="18+" @selected(old('meta_data.age_restriction', data_get($npMeta,'age_restriction',''))=='18+')>18+</option>
                      <option value="21+" @selected(old('meta_data.age_restriction', data_get($npMeta,'age_restriction',''))=='21+')>21+</option>
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('age_restriction')">+</button>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📅</span><span class="np-card-title">Dates &amp;
                Lifecycle</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Product Launch Date</label><input type="date"
                  name="meta_data[launch_date]" class="np-input" value="{{ old('meta_data.launch_date', data_get($npMeta,'launch_date','')) }}"></div>
              <div class="np-form-group"><label class="np-label">End-of-Life Date</label><input type="date"
                  name="meta_data[eol_date]" class="np-input" value="{{ old('meta_data.eol_date', data_get($npMeta,'eol_date','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Warranty Period</label><select
                  name="meta_data[warranty]" id="npWarranty" class="np-select js-select2-custom">
                  <option value="">N/A</option>
                  @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                    @foreach(\App\Models\ProductSelectOption::where('type','warranty')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                      <option value="{{ $opt->name }}" @selected(old('meta_data.warranty', data_get($npMeta,'warranty','')) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                  @else
                    @foreach(['1 Month','3 Months','6 Months','1 Year','2 Years','3 Years'] as $w)
                      <option value="{{ $w }}" @selected(old('meta_data.warranty', data_get($npMeta,'warranty','')) == $w)>{{ $w }}</option>
                    @endforeach
                  @endif
                </select>
                <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('warranty')" style="margin-top:8px">+</button>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">⚖️</span><span
                class="np-card-title">Compliance</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Approval / Permit No.</label><input type="text"
                  name="meta_data[approval_no]" class="np-input np-mono" placeholder="e.g. QFSSA-2024-XXXX" value="{{ old('meta_data.approval_no', data_get($npMeta,'approval_no','')) }}"></div>
              <div class="np-form-group"><label class="np-label">Import Permit No.</label><input type="text"
                  name="meta_data[import_permit]" class="np-input np-mono" placeholder="e.g. IMP-XXXX" value="{{ old('meta_data.import_permit', data_get($npMeta,'import_permit','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">CE / Safety
                  Marking</label><input type="text" name="meta_data[safety_marking]" class="np-input"
                  placeholder="e.g. CE, FCC, ROHS" value="{{ old('meta_data.safety_marking', data_get($npMeta,'safety_marking','')) }}"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🏅</span><span
                class="np-card-title">Certifications</span></div>
            <div class="np-card-body">
              <div class="np-chk-grid">
                <label class="np-chk-item {{ in_array('halal',$npCert) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="halal" @checked(in_array('halal',$npCert))> 🟢 Halal</label>
                <label class="np-chk-item {{ in_array('organic',$npCert) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="organic" @checked(in_array('organic',$npCert))> 🌱 Organic</label>
                <label class="np-chk-item {{ in_array('free_range',$npCert) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[cert][]"
                    value="free_range" @checked(in_array('free_range',$npCert))> 🐄 Free Range</label>
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
              <div class="np-form-group">
                <label class="np-label">Returnable</label>
                <div class="np-inline-add">
                  <select name="meta_data[return_policy]" id="npReturnPolicy" class="np-select js-select2-custom">
                    <option value="">Select…</option>
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','return_policy')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected(old('meta_data.return_policy', data_get($npMeta,'return_policy','')) == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      @foreach(['Yes — Within 7 days','Yes — Within 14 days','Yes — Within 30 days','No — Non-returnable (perishable)'] as $rp)
                        <option value="{{ $rp }}" @selected(old('meta_data.return_policy', data_get($npMeta,'return_policy','')) == $rp)>{{ $rp }}</option>
                      @endforeach
                    @endif
                  </select>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('return_policy')">+</button>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Return
                  Conditions</label><textarea name="meta_data[return_conditions]" class="np-textarea" rows="2"
                  placeholder="e.g. Original packaging, unopened, within expiry date.">{{ old('meta_data.return_conditions', data_get($npMeta,'return_conditions','')) }}</textarea></div>
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
                  rows="4" placeholder="e.g. Pasteurised cow's milk, cream, salt, lactic acid bacteria…">{{ old('meta_data.ingredients_en', data_get($npMeta,'ingredients_en','')) }}</textarea>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Ingredients List
                  (Arabic)</label><textarea name="meta_data[ingredients_ar]" class="np-textarea" rows="3"
                  placeholder="قائمة المكونات بالعربي…" style="direction:rtl;text-align:right">{{ old('meta_data.ingredients_ar', data_get($npMeta,'ingredients_ar','')) }}</textarea></div>
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
                    <td><input type="text" name="meta_data[nutr_energy_100g]" class="np-input" placeholder="263" value="{{ old('meta_data.nutr_energy_100g', data_get($npMeta,'nutr_energy_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_energy_srv]" class="np-input" placeholder="47" value="{{ old('meta_data.nutr_energy_srv', data_get($npMeta,'nutr_energy_srv','')) }}"></td>
                    <td>kcal</td>
                  </tr>
                  <tr>
                    <td>Energy (kJ)</td>
                    <td><input type="text" name="meta_data[nutr_energy_kj_100g]" class="np-input" placeholder="1099" value="{{ old('meta_data.nutr_energy_kj_100g', data_get($npMeta,'nutr_energy_kj_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_energy_kj_srv]" class="np-input" placeholder="198" value="{{ old('meta_data.nutr_energy_kj_srv', data_get($npMeta,'nutr_energy_kj_srv','')) }}"></td>
                    <td>kJ</td>
                  </tr>
                  <tr>
                    <td>Total Fat</td>
                    <td><input type="text" name="meta_data[nutr_fat_100g]" class="np-input" placeholder="24" value="{{ old('meta_data.nutr_fat_100g', data_get($npMeta,'nutr_fat_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_fat_srv]" class="np-input" placeholder="4.3" value="{{ old('meta_data.nutr_fat_srv', data_get($npMeta,'nutr_fat_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Saturated Fat</td>
                    <td><input type="text" name="meta_data[nutr_satfat_100g]" class="np-input" placeholder="15" value="{{ old('meta_data.nutr_satfat_100g', data_get($npMeta,'nutr_satfat_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_satfat_srv]" class="np-input" placeholder="2.7" value="{{ old('meta_data.nutr_satfat_srv', data_get($npMeta,'nutr_satfat_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Trans Fat</td>
                    <td><input type="text" name="meta_data[nutr_transfat_100g]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_transfat_100g', data_get($npMeta,'nutr_transfat_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_transfat_srv]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_transfat_srv', data_get($npMeta,'nutr_transfat_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Monounsaturated Fat</td>
                    <td><input type="text" name="meta_data[nutr_monofat_100g]" class="np-input" placeholder="6.5" value="{{ old('meta_data.nutr_monofat_100g', data_get($npMeta,'nutr_monofat_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_monofat_srv]" class="np-input" placeholder="1.2" value="{{ old('meta_data.nutr_monofat_srv', data_get($npMeta,'nutr_monofat_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Polyunsaturated Fat</td>
                    <td><input type="text" name="meta_data[nutr_polyfat_100g]" class="np-input" placeholder="0.8" value="{{ old('meta_data.nutr_polyfat_100g', data_get($npMeta,'nutr_polyfat_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_polyfat_srv]" class="np-input" placeholder="0.1" value="{{ old('meta_data.nutr_polyfat_srv', data_get($npMeta,'nutr_polyfat_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Total Carbohydrates</td>
                    <td><input type="text" name="meta_data[nutr_carbs_100g]" class="np-input" placeholder="4.5" value="{{ old('meta_data.nutr_carbs_100g', data_get($npMeta,'nutr_carbs_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_carbs_srv]" class="np-input" placeholder="0.8" value="{{ old('meta_data.nutr_carbs_srv', data_get($npMeta,'nutr_carbs_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Total Sugars</td>
                    <td><input type="text" name="meta_data[nutr_sugars_100g]" class="np-input" placeholder="2.2" value="{{ old('meta_data.nutr_sugars_100g', data_get($npMeta,'nutr_sugars_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_sugars_srv]" class="np-input" placeholder="0.4" value="{{ old('meta_data.nutr_sugars_srv', data_get($npMeta,'nutr_sugars_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Added Sugars</td>
                    <td><input type="text" name="meta_data[nutr_added_sugars_100g]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_added_sugars_100g', data_get($npMeta,'nutr_added_sugars_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_added_sugars_srv]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_added_sugars_srv', data_get($npMeta,'nutr_added_sugars_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Dietary Fibre</td>
                    <td><input type="text" name="meta_data[nutr_fibre_100g]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_fibre_100g', data_get($npMeta,'nutr_fibre_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_fibre_srv]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_fibre_srv', data_get($npMeta,'nutr_fibre_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Protein</td>
                    <td><input type="text" name="meta_data[nutr_protein_100g]" class="np-input" placeholder="7.8" value="{{ old('meta_data.nutr_protein_100g', data_get($npMeta,'nutr_protein_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_protein_srv]" class="np-input" placeholder="1.4" value="{{ old('meta_data.nutr_protein_srv', data_get($npMeta,'nutr_protein_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Salt</td>
                    <td><input type="text" name="meta_data[nutr_salt_100g]" class="np-input" placeholder="1.2" value="{{ old('meta_data.nutr_salt_100g', data_get($npMeta,'nutr_salt_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_salt_srv]" class="np-input" placeholder="0.2" value="{{ old('meta_data.nutr_salt_srv', data_get($npMeta,'nutr_salt_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td class="np-ind">Sodium</td>
                    <td><input type="text" name="meta_data[nutr_sodium_100g]" class="np-input" placeholder="0.47" value="{{ old('meta_data.nutr_sodium_100g', data_get($npMeta,'nutr_sodium_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_sodium_srv]" class="np-input" placeholder="0.08" value="{{ old('meta_data.nutr_sodium_srv', data_get($npMeta,'nutr_sodium_srv','')) }}"></td>
                    <td>g</td>
                  </tr>
                  <tr>
                    <td>Calcium</td>
                    <td><input type="text" name="meta_data[nutr_calcium_100g]" class="np-input" placeholder="100" value="{{ old('meta_data.nutr_calcium_100g', data_get($npMeta,'nutr_calcium_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_calcium_srv]" class="np-input" placeholder="18" value="{{ old('meta_data.nutr_calcium_srv', data_get($npMeta,'nutr_calcium_srv','')) }}"></td>
                    <td>mg</td>
                  </tr>
                  <tr>
                    <td>Vitamin A</td>
                    <td><input type="text" name="meta_data[nutr_vita_100g]" class="np-input" placeholder="180" value="{{ old('meta_data.nutr_vita_100g', data_get($npMeta,'nutr_vita_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_vita_srv]" class="np-input" placeholder="32" value="{{ old('meta_data.nutr_vita_srv', data_get($npMeta,'nutr_vita_srv','')) }}"></td>
                    <td>µg</td>
                  </tr>
                  <tr>
                    <td>Vitamin D</td>
                    <td><input type="text" name="meta_data[nutr_vitd_100g]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_vitd_100g', data_get($npMeta,'nutr_vitd_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_vitd_srv]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_vitd_srv', data_get($npMeta,'nutr_vitd_srv','')) }}"></td>
                    <td>µg</td>
                  </tr>
                  <tr>
                    <td>Cholesterol</td>
                    <td><input type="text" name="meta_data[nutr_chol_100g]" class="np-input" placeholder="70" value="{{ old('meta_data.nutr_chol_100g', data_get($npMeta,'nutr_chol_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_chol_srv]" class="np-input" placeholder="13" value="{{ old('meta_data.nutr_chol_srv', data_get($npMeta,'nutr_chol_srv','')) }}"></td>
                    <td>mg</td>
                  </tr>
                  <tr>
                    <td>Iron</td>
                    <td><input type="text" name="meta_data[nutr_iron_100g]" class="np-input" placeholder="0.1" value="{{ old('meta_data.nutr_iron_100g', data_get($npMeta,'nutr_iron_100g','')) }}"></td>
                    <td><input type="text" name="meta_data[nutr_iron_srv]" class="np-input" placeholder="0" value="{{ old('meta_data.nutr_iron_srv', data_get($npMeta,'nutr_iron_srv','')) }}"></td>
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
                      placeholder="18" step="0.5" value="{{ old('meta_data.serving_size', data_get($npMeta,'serving_size','')) }}"><span class="np-isfx">g</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Servings per
                    Container</label><input type="number" name="meta_data[servings_per_container]" class="np-input"
                    placeholder="48" value="{{ old('meta_data.servings_per_container', data_get($npMeta,'servings_per_container','')) }}"></div>
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
                <label class="np-chk-item {{ in_array('milk',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="milk" @checked(in_array('milk',$npAllergen))> 🥛 Milk</label>
                <label class="np-chk-item {{ in_array('gluten',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="gluten" @checked(in_array('gluten',$npAllergen))> 🌾 Gluten/Wheat</label>
                <label class="np-chk-item {{ in_array('eggs',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="eggs" @checked(in_array('eggs',$npAllergen))> 🥚 Eggs</label>
                <label class="np-chk-item {{ in_array('fish',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="fish" @checked(in_array('fish',$npAllergen))> 🐟 Fish</label>
                <label class="np-chk-item {{ in_array('crustaceans',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="crustaceans" @checked(in_array('crustaceans',$npAllergen))> 🦐 Crustaceans</label>
                <label class="np-chk-item {{ in_array('peanuts',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="peanuts" @checked(in_array('peanuts',$npAllergen))> 🥜 Peanuts</label>
                <label class="np-chk-item {{ in_array('tree_nuts',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="tree_nuts" @checked(in_array('tree_nuts',$npAllergen))> 🌰 Tree Nuts</label>
                <label class="np-chk-item {{ in_array('soybeans',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="soybeans" @checked(in_array('soybeans',$npAllergen))> 🫘 Soybeans</label>
                <label class="np-chk-item {{ in_array('sesame',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="sesame" @checked(in_array('sesame',$npAllergen))> 🌱 Sesame</label>
                <label class="np-chk-item {{ in_array('mustard',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="mustard" @checked(in_array('mustard',$npAllergen))> 🌻 Mustard</label>
                <label class="np-chk-item {{ in_array('celery',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="celery" @checked(in_array('celery',$npAllergen))> 🥬 Celery</label>
                <label class="np-chk-item {{ in_array('sulphites',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="sulphites" @checked(in_array('sulphites',$npAllergen))> 🍇 Sulphites</label>
                <label class="np-chk-item {{ in_array('molluscs',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="molluscs" @checked(in_array('molluscs',$npAllergen))> 🦑 Molluscs</label>
                <label class="np-chk-item {{ in_array('lupin',$npAllergen) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[allergen][]"
                    value="lupin" @checked(in_array('lupin',$npAllergen))> 🫘 Lupin</label>
              </div>
              <div class="np-sec-head" style="margin-top:14px">May Contain (Cross-contamination)</div>
              <div class="np-tag-wrap" id="npMcWrap" onclick="this.querySelector('input').focus()">
                @foreach($npMayContain as $t)
                  @php($t = is_string($t) ? trim($t) : '')
                  @if(filled($t))
                    <span class="np-tag t-orange">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[may_contain][]" value="{{ $t }}"></span>
                  @endif
                @endforeach
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
                <label class="np-chk-item {{ in_array('halal',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="halal" @checked(in_array('halal',$npDietary))> ✅ Halal</label>
                <label class="np-chk-item {{ in_array('kosher',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="kosher" @checked(in_array('kosher',$npDietary))> ✡️ Kosher</label>
                <label class="np-chk-item {{ in_array('vegetarian',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="vegetarian" @checked(in_array('vegetarian',$npDietary))> 🌱 Vegetarian</label>
                <label class="np-chk-item {{ in_array('vegan',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="vegan" @checked(in_array('vegan',$npDietary))> 🌿 Vegan</label>
                <label class="np-chk-item {{ in_array('gluten_free',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="gluten_free" @checked(in_array('gluten_free',$npDietary))> 🚫🌾 Gluten-Free</label>
                <label class="np-chk-item {{ in_array('dairy_free',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="dairy_free" @checked(in_array('dairy_free',$npDietary))> 🚫🥛 Dairy-Free</label>
                <label class="np-chk-item {{ in_array('low_sugar',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="low_sugar" @checked(in_array('low_sugar',$npDietary))> 🍬 Low Sugar</label>
                <label class="np-chk-item {{ in_array('no_preservatives',$npDietary) ? 'on' : '' }}" onclick="npTogChk(this)"><input type="checkbox" name="meta_data[dietary][]"
                    value="no_preservatives" @checked(in_array('no_preservatives',$npDietary))> 🫀 No Preservatives</label>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔬</span><span class="np-card-title">Additives &amp;
                E-Numbers</span></div>
            <div class="np-card-body">
              <div class="np-tag-wrap" id="npENumWrap" onclick="this.querySelector('input').focus()">
                @foreach($npENumbers as $t)
                  @php($t = is_string($t) ? trim($t) : '')
                  @if(filled($t))
                    <span class="np-tag t-blue">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[e_numbers][]" value="{{ $t }}"></span>
                  @endif
                @endforeach
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
            {{-- Moved into Colour/Flavour section below --}}
          @endif
          <div class="np-card" style="margin-top:18px">
            <div class="np-card-header"><span class="np-card-icon">🎨</span><span class="np-card-title">Colour / Flavour
                Variants</span></div>
            <div class="np-card-body">
              <div class="np-form-row" style="align-items:flex-end">
                <div class="np-form-group" style="margin-bottom:0;min-width:220px">
                  <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <span>Variant Type</span>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('variant_type')">+</button>
                  </label>
                  <select name="meta_data[variant_type]" id="npVariantType" class="np-select js-select2-custom">
                    @php($savedVt = old('meta_data.variant_type', data_get($npMeta,'variant_type','Flavour')))
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','variant_type')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected($savedVt == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      @foreach(['Flavour','Colour','Scent','Style'] as $vt)
                        <option value="{{ $vt }}" @selected($savedVt==$vt)>{{ $vt }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="np-form-group" style="margin-bottom:0;flex:1">
                  <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <span>Colour Palette</span>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('variant_color')">+</button>
                  </label>
                  <div class="np-swatches" id="npSwatchesWrap">
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options') && \Illuminate\Support\Facades\Schema::hasColumn('product_select_options','value'))
                      @foreach(\App\Models\ProductSelectOption::where('type','variant_color')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name','value']) as $opt)
                        @php($hex = is_string($opt->value) ? trim($opt->value) : '')
                        @if(filled($hex))
                          <div class="np-swatch {{ in_array($hex,$npSwatches) ? 'sel' : '' }}"
                            data-hex="{{ $hex }}"
                            style="background:{{ $hex }}{{ strtolower($hex) === '#f9fafb' ? ';border:1.5px solid #d1d5db' : '' }}"
                            title="{{ $opt->name }}"
                            onclick="npToggleSwatch(this)"></div>
                        @endif
                      @endforeach
                    @else
                      @foreach(['#006161' => 'Teal', '#2563eb' => 'Blue', '#16a34a' => 'Green', '#f59e0b' => 'Amber', '#7c3aed' => 'Purple', '#0d1b2a' => 'Black', '#f9fafb' => 'White', '#d97706' => 'Orange', '#ec4899' => 'Pink', '#9ca3af' => 'Grey'] as $hex => $name)
                        <div class="np-swatch {{ in_array($hex,$npSwatches) ? 'sel' : '' }}"
                          data-hex="{{ $hex }}"
                          style="background:{{ $hex }}{{ $hex === '#f9fafb' ? ';border:1.5px solid #d1d5db' : '' }}"
                          title="{{ $name }}"
                          onclick="npToggleSwatch(this)"></div>
                      @endforeach
                    @endif
                  </div>
                </div>
              </div>

              <div class="np-form-group" style="margin-bottom:0;margin-top:12px">
                <label class="np-label">Custom Flavour / Colour Names</label>
                <div class="np-tag-wrap" id="npFlavWrap" onclick="this.querySelector('input').focus()">
                  @foreach($npFlavorNames as $t)
                    @php($t = is_string($t) ? trim($t) : '')
                    @if(filled($t))
                      <span class="np-tag t-green">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[flavor_names][]" value="{{ $t }}"></span>
                    @endif
                  @endforeach
                  <input type="text" id="npFlavInput" class="np-input" placeholder="Add variant, press Enter…"
                    onkeydown="npAddTag(event,'npFlavWrap','npFlavInput','t-green','meta_data[flavor_names][]')">
                </div>
              </div>

              @if (Config::get('module.current_module_type') != 'food')
                <div style="margin-top:16px">
                  <div class="np-sec-head" style="margin:0 0 10px;display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <span>Attribute</span>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenAttributeQuickAdd()">+</button>
                  </div>
                  <div id="npAttrEmbed">
                    @includeif('admin-views.product.partials._other_variations')
                  </div>
                </div>
              @endif
            </div>
          </div>

          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📏</span><span class="np-card-title">Size / Weight
                Variants</span></div>
            <div class="np-card-body">
              <div class="np-tag-wrap" id="npSizeWrap" onclick="this.querySelector('input').focus()">
                @foreach($npSizeVariants as $t)
                  @php($t = is_string($t) ? trim($t) : '')
                  @if(filled($t))
                    <span class="np-tag t-purple">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[size_variants][]" value="{{ $t }}"></span>
                  @endif
                @endforeach
                <input type="text" id="npSizeInput" class="np-input" placeholder="Add size/weight, press Enter…"
                  onkeydown="npAddTag(event,'npSizeWrap','npSizeInput','t-purple','meta_data[size_variants][]')">
              </div>
            </div>
          </div>

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
                  @php($npVarName = old('meta_data.var_name', data_get($npMeta,'var_name', [])))
                  @php($npVarSku = old('meta_data.var_sku', data_get($npMeta,'var_sku', [])))
                  @php($npVarPrice = old('meta_data.var_price', data_get($npMeta,'var_price', [])))
                  @php($npVarStock = old('meta_data.var_stock', data_get($npMeta,'var_stock', [])))
                  @php($npVarStatus = old('meta_data.var_status', data_get($npMeta,'var_status', [])))
                  @php($npVarName = is_array($npVarName) ? $npVarName : [])
                  @php($npVarSku = is_array($npVarSku) ? $npVarSku : [])
                  @php($npVarPrice = is_array($npVarPrice) ? $npVarPrice : [])
                  @php($npVarStock = is_array($npVarStock) ? $npVarStock : [])
                  @php($npVarStatus = is_array($npVarStatus) ? $npVarStatus : [])
                  @php($npVarRows = max(count($npVarName), count($npVarSku), count($npVarPrice), count($npVarStock), count($npVarStatus), 1))
                  @for($i=0;$i<$npVarRows;$i++)
                    <tr>
                      <td><input type="text" name="meta_data[var_name][]" class="np-input"
                          placeholder="e.g. Original / 864g" value="{{ $npVarName[$i] ?? '' }}"></td>
                      <td><input type="text" name="meta_data[var_sku][]" class="np-input np-mono" placeholder="SKU-001"
                          value="{{ $npVarSku[$i] ?? '' }}"></td>
                      <td><input type="number" name="meta_data[var_price][]" class="np-input" placeholder="0.00"
                          step="0.01" value="{{ $npVarPrice[$i] ?? '' }}"></td>
                      <td><input type="number" name="meta_data[var_stock][]" class="np-input" placeholder="0"
                          value="{{ $npVarStock[$i] ?? '' }}"></td>
                      <td>
                        @php($st = $npVarStatus[$i] ?? 'Active')
                        <select name="meta_data[var_status][]" class="np-select" style="font-size:11.5px;padding:5px 28px 5px 8px">
                          <option value="Active" @selected($st==='Active')>Active</option>
                          <option value="Out of Stock" @selected($st==='Out of Stock')>Out of Stock</option>
                          <option value="Disabled" @selected($st==='Disabled')>Disabled</option>
                        </select>
                      </td>
                      <td><button type="button" class="np-btn-tiny del" onclick="this.closest('tr').remove()">✕</button></td>
                    </tr>
                  @endfor
                </tbody>
              </table>
              <div style="padding:12px 16px"><button type="button" class="np-btn-add" onclick="npAddVariantRow()">+ Add
                  Variant Row</button></div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔗</span><span class="np-card-title">Related
                Products</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Upsell Products</label><input type="text"
                  name="meta_data[upsell]" class="np-input" placeholder="Search by name or SKU…" value="{{ old('meta_data.upsell', data_get($npMeta,'upsell','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Cross-Sell
                  Products</label><input type="text" name="meta_data[crosssell]" class="np-input"
                  placeholder="Search by name or SKU…" value="{{ old('meta_data.crosssell', data_get($npMeta,'crosssell','')) }}"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🎁</span><span class="np-card-title">Bundle /
                Multipack</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              @php($npIsBundle = (string) old('meta_data.is_bundle', data_get($npMeta,'is_bundle','0')) === '1')
              <div class="np-trow" style="padding-top:0">
                <div>
                  <div class="np-tlbl">Is Bundle / Multipack</div>
                  <div class="np-tdsc">Contains multiple units</div>
                </div>
                <label class="np-tog">
                  <input type="checkbox" id="npBundleToggle" name="meta_data[is_bundle]" value="1"
                    @checked($npIsBundle)>
                  <span class="np-tog-track"></span>
                </label>
              </div>
              <div id="npBundleFields" style="display:{{ $npIsBundle ? 'block' : 'none' }};margin-top:14px">
                <div class="np-form-group">
                  <label class="np-label">Bundle Components</label>
                  <textarea rows="2" name="meta_data[bundle_components]" class="np-textarea"
                    placeholder="e.g. 2× Kiri Cheese 24 portions pack">{{ old('meta_data.bundle_components', data_get($npMeta,'bundle_components','')) }}</textarea>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Bundle Discount %</label>
                  <div class="np-iw sfx">
                    <input type="number" name="meta_data[bundle_discount]" class="np-input" placeholder="10" min="0"
                      max="100" value="{{ old('meta_data.bundle_discount', data_get($npMeta,'bundle_discount','')) }}">
                    <span class="np-isfx">%</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🔢</span><span class="np-card-title">Purchase
                Limits</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Min. Order Quantity</label><input type="number"
                  name="meta_data[min_order_qty]" class="np-input" placeholder="1" min="1" value="{{ old('meta_data.min_order_qty', data_get($npMeta,'min_order_qty','1')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Max. Order Quantity per
                  Customer</label><input type="number" name="meta_data[max_order_qty]" class="np-input" placeholder="10"
                  min="1" value="{{ old('meta_data.max_order_qty', data_get($npMeta,'max_order_qty','')) }}"></div>
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
                  placeholder="e.g. Cream Cheese 864g — Buy Online | {{ config('app.name') }}" oninput="npUpdateSEO()"
                  value="{{ $npSeoTitle }}">
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="np-hint">Recommended: 50–60 characters</span>
                  <span id="npSeoTitleCC" class="np-cc np-cc-ok">0 chars</span>
                </div>
              </div>
              <div class="np-form-group">
                <label class="np-label">Meta Description <span class="np-req">*</span></label>
                <textarea name="meta_description" id="npSeoDescInp" class="np-textarea" rows="3"
                  placeholder="Concise product description for search engines…" oninput="npUpdateSEO()">{{ $npSeoDesc }}</textarea>
                <div style="display:flex;justify-content:space-between;margin-top:4px">
                  <span class="np-hint">Recommended: 150–160 characters</span>
                  <span id="npSeoDescCC" class="np-cc np-cc-ok">0 chars</span>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0">
                <label class="np-label">URL Slug</label>
                <div class="np-iw">
                  <span class="np-ipfx" style="font-size:11px">/p/</span>
                  <input type="text" name="slug" id="npSeoSlug" class="np-input np-mono" placeholder="product-name-slug"
                    oninput="npUpdateSEO()" value="{{ $npSeoSlug }}">
                </div>
                <div class="np-hint">Auto-generated from product name. Lowercase, hyphens only.</div>
              </div>
            </div>
          </div>

          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🗃️</span><span class="np-card-title">Structured Data
                / Schema</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">GTIN (for Google Shopping)</label>
                  <input type="text" name="meta_data[gtin]" class="np-input np-mono" placeholder="e.g. 3073781039180"
                    value="{{ old('meta_data.gtin', data_get($npMeta,'gtin','')) }}">
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label">Google Product Category</label>
                  <input type="text" name="meta_data[google_category]" class="np-input"
                    placeholder="e.g. Food &amp; Drink > Dairy > Cheese"
                    value="{{ old('meta_data.google_category', data_get($npMeta,'google_category','')) }}">
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0;margin-top:12px">
                <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                  <span>Schema Type</span>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('schema_type')">+</button>
                </label>
                <select name="meta_data[schema_type]" id="npSchemaType" class="np-select js-select2-custom">
                  @php($savedSchema = old('meta_data.schema_type', data_get($npMeta,'schema_type','Product')))
                  @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                    @foreach(\App\Models\ProductSelectOption::where('type','schema_type')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                      <option value="{{ $opt->name }}" @selected($savedSchema == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                  @else
                    @foreach(['Product','FoodProduct','IndividualProduct'] as $st)
                      <option value="{{ $st }}" @selected($savedSchema == $st)>{{ $st }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📣</span><span class="np-card-title">Social Meta (OG
                Tags)</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">OG Title</label><input type="text"
                  name="meta_data[og_title]" class="np-input"
                  placeholder="e.g. Great Product — {{ config('app.name') }}"
                  value="{{ old('meta_data.og_title', data_get($npMeta,'og_title','')) }}"></div>
              <div class="np-form-group"><label class="np-label">OG Description</label><textarea
                  name="meta_data[og_desc]" class="np-textarea" rows="2"
                  placeholder="Description for social media sharing…">{{ old('meta_data.og_desc', data_get($npMeta,'og_desc','')) }}</textarea></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">OG Image URL</label><input
                  type="url" name="meta_data[og_image]" class="np-input"
                  placeholder="https://cdn.example.com/product-og.jpg"
                  value="{{ old('meta_data.og_image', data_get($npMeta,'og_image','')) }}"></div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🏷️</span><span class="np-card-title">SEO
                Keywords</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Focus Keyword</label><input type="text"
                  name="meta_data[focus_keyword]" class="np-input" placeholder="e.g. cream cheese online"
                  value="{{ old('meta_data.focus_keyword', data_get($npMeta,'focus_keyword','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Additional Keywords</label>
                <div class="np-tag-wrap" id="npSeoKWrap" onclick="this.querySelector('input').focus()">
                  @foreach($npSeoKeywords as $t)
                    @php($t = is_string($t) ? trim($t) : '')
                    @if(filled($t))
                      <span class="np-tag t-blue">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[seo_keywords][]" value="{{ $t }}"></span>
                    @endif
                  @endforeach
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
              @php($seoIndex = (string) old('meta_data.seo_index', data_get($npMeta,'seo_index','1')) === '1')
              @php($seoFollow = (string) old('meta_data.seo_follow', data_get($npMeta,'seo_follow','1')) === '1')
              @php($seoCanonical = (string) old('meta_data.seo_canonical', data_get($npMeta,'seo_canonical','1')) === '1')
              <div class="np-trow" style="padding-top:0">
                <div>
                  <div class="np-tlbl">Index this page</div>
                  <div class="np-tdsc">Allow search engines to index</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_index]" value="1" @checked($seoIndex)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Follow Links</div>
                  <div class="np-tdsc">Allow crawlers to follow</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_follow]" value="1" @checked($seoFollow)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Canonical URL</div>
                  <div class="np-tdsc">Set as canonical version</div>
                </div>
                <label class="np-tog"><input type="checkbox" name="meta_data[seo_canonical]" value="1" @checked($seoCanonical)><span
                    class="np-tog-track"></span></label>
              </div>
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
                @php($npThumb = $isEdit && filled($product?->image) ? ($product?->image_full_url ?? \App\CentralLogics\Helpers::get_full_url('product', $product->image, 'public')) : asset('assets/admin/img/upload-img.png'))
                <img class="img--176 border" id="viewer" src="{{ $npThumb }}"
                  alt="thumbnail">
                <div class="icon-file-group">
                  <div class="icon-file">
                    <input type="file" name="image" id="customFileEg1" class="custom-file-input d-none"
                      accept="{{ IMAGE_EXTENSION }}" {{ $isEdit && filled($product?->image) ? '' : 'required' }}>
                    <i class="tio-edit"></i>
                  </div>
                </div>
              </label>
              <div class="np-hint mt-2">JPG, PNG, WEBP, GIF — max 2MB — 1:1 ratio</div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">🖼️</span><span class="np-card-title">Product Gallery
                Images</span><span class="np-card-subtitle">Max 8 · JPG/PNG/WEBP · 2MB each</span></div>
            <div class="np-card-body">
              <input type="hidden" name="removedImageKeys" id="removedImageKeys" value="">
              <div class="np-upload-zone" id="npUploadZone" onclick="document.getElementById('npFileInput').click()"
                ondragover="event.preventDefault();this.classList.add('drag')"
                ondragleave="this.classList.remove('drag')" ondrop="npHandleDrop(event)">
                <div class="np-upload-icon">📤</div>
                <div class="np-upload-title">Drag &amp; drop images here</div>
                <div class="np-upload-sub">or <span class="np-upload-link">browse from your computer</span></div>
                <div class="np-upload-sub" style="margin-top:5px;font-size:11px;color:#b0bec5">First image = main
                  gallery image</div>
                <input type="file" id="npFileInput" name="images[]" multiple accept="{{ IMAGE_EXTENSION }}" style="display:none"
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
                  name="meta_data[video_url]" class="np-input" placeholder="https://www.youtube.com/watch?v=…"
                  value="{{ old('meta_data.video_url', data_get($npMeta,'video_url','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Video Title</label><input
                  type="text" name="meta_data[video_title]" class="np-input" placeholder="e.g. Product Demo"
                  value="{{ old('meta_data.video_title', data_get($npMeta,'video_title','')) }}"></div>
            </div>
          </div>
          <div class="np-card">
            <div class="np-card-header"><span class="np-card-icon">📄</span><span class="np-card-title">Documents</span>
            </div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Product Datasheet URL</label><input type="url"
                  name="meta_data[datasheet_url]" class="np-input" placeholder="https://…/datasheet.pdf"
                  value="{{ old('meta_data.datasheet_url', data_get($npMeta,'datasheet_url','')) }}"></div>
              <div class="np-form-group"><label class="np-label">Certificate of Analysis</label><input type="url"
                  name="meta_data[cert_url]" class="np-input" placeholder="https://…/cert.pdf"
                  value="{{ old('meta_data.cert_url', data_get($npMeta,'cert_url','')) }}"></div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">User Manual / Recipe
                  Guide</label><input type="url" name="meta_data[manual_url]" class="np-input"
                  placeholder="https://…/manual.pdf"
                  value="{{ old('meta_data.manual_url', data_get($npMeta,'manual_url','')) }}"></div>
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
              @php($np360 = (string) old('meta_data.enable_360', data_get($npMeta,'enable_360','0')) === '1')
              @php($npAR = (string) old('meta_data.enable_ar', data_get($npMeta,'enable_ar','0')) === '1')
              <div class="np-trow" style="padding-top:0">
                <div>
                  <div class="np-tlbl">Enable 360° View</div>
                  <div class="np-tdsc">Upload a sequence of images</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[enable_360]" value="1" @checked($np360)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Enable AR</div>
                  <div class="np-tdsc">Requires USDZ / GLB 3D model</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[enable_ar]" value="1" @checked($npAR)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">3D Model File
                  URL (.glb / .usdz)</label><input type="url" name="meta_data[model_3d_url]" class="np-input"
                  placeholder="https://cdn.example.com/product.glb"
                  value="{{ old('meta_data.model_3d_url', data_get($npMeta,'model_3d_url','')) }}"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 7: LOGISTICS & SHIPPING ══ --}}
    <div class="np-tab-panel" id="np-tab-logistics">
      <div class="np-grid">
        <div>
          <div class="np-card" data-np-section="np-tab-logistics.incoterms">
            <div class="np-card-header"><span class="np-card-icon">⚖️</span><span class="np-card-title">Incoterms
                (International Trade Terms)</span></div>
            <div class="np-card-body">
              <div class="np-info-box">📌 Incoterms define who is responsible for shipping, insurance, and import duties
                between seller and buyer.</div>
              <div class="np-inco-grid">
                @foreach(['EXW' => 'Ex Works', 'FCA' => 'Free Carrier', 'CPT' => 'Carriage Paid To', 'CIP' => 'Carriage & Ins. Paid', 'FOB' => 'Free On Board', 'CFR' => 'Cost & Freight', 'CIF' => 'Cost, Ins. & Freight', 'DPU' => 'Delivered at Place Unloaded', 'DAP' => 'Delivered at Place', 'DDP' => 'Delivered Duty Paid', 'FAS' => 'Free Alongside Ship', 'DAT' => 'Delivered at Terminal'] as $code => $label)
                  @php($k = 'incoterm_' . strtolower($code))
                  @php($vLegacy = (string) old('meta_data.' . $k, data_get($npMeta, $k, '0')) === '1')
                  @php($v = $vLegacy || in_array($code, $npIncoterms))
                  <div class="np-inco-pill {{ $v ? 'sel' : '' }}" onclick="npToggleIncoterm(this)">{{ $code }}<span
                      class="np-inco-sub">{{ $label }}</span>
                    <input type="hidden" name="meta_data[{{ $k }}]" value="{{ $vLegacy ? '1' : '0' }}">
                    <input type="checkbox" class="d-none" name="meta_data[incoterms][]" value="{{ $code }}" @checked($v)>
                  </div>
                @endforeach
                @foreach($npIncotermsCustom as $code)
                  @php($code = is_string($code) ? strtoupper(trim($code)) : '')
                  @if(filled($code))
                    @php($v = in_array($code, $npIncoterms))
                    <div class="np-inco-pill {{ $v ? 'sel' : '' }}" onclick="npToggleIncoterm(this)">{{ $code }}
                      <input type="hidden" name="meta_data[incoterms_custom][]" value="{{ $code }}">
                      <input type="checkbox" class="d-none" name="meta_data[incoterms][]" value="{{ $code }}" @checked($v)>
                    </div>
                  @endif
                @endforeach
              </div>
              <div class="np-form-row" style="margin-top:12px;align-items:center">
                <div class="np-form-group" style="margin:0;flex:1">
                  <label class="np-label">Add custom Incoterm code</label>
                  <input type="text" id="npIncoCustomInput" class="np-input" placeholder="e.g. XYZ">
                </div>
                <button type="button" class="np-btn-add" style="margin-top:22px" onclick="npAddCustomIncoterm()">+ Add</button>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.shipping_methods">
            <div class="np-card-header"><span class="np-card-icon">✈️</span><span class="np-card-title">Shipping
                Methods</span></div>
            <div class="np-card-body">
              <div class="np-chk-grid">
                @foreach([
                  'sea_fcl' => '🚢 Sea Freight (FCL)',
                  'sea_lcl' => '📦 Sea Freight (LCL)',
                  'air' => '✈️ Air Freight',
                  'rail' => '🚂 Rail Freight',
                  'road' => '🚚 Road / Truck',
                  'express' => '📮 Express (DHL/FedEx)',
                  'same_day' => '🛵 Same-Day Local',
                  'drone' => '🚁 Drone Delivery',
                ] as $key => $label)
                  <label class="np-chk-item {{ in_array($key,$npShip) ? 'on' : '' }}"><input type="checkbox" name="meta_data[ship][]"
                      value="{{ $key }}" @checked(in_array($key,$npShip))> {{ $label }}</label>
                @endforeach
                @foreach($npShipCustom as $nm)
                  @php($nm = is_string($nm) ? trim($nm) : '')
                  @if(filled($nm))
                    @php($val = 'custom:' . $nm)
                    <label class="np-chk-item {{ in_array($val,$npShip) ? 'on' : '' }}"><input type="checkbox" name="meta_data[ship][]"
                        value="{{ $val }}" @checked(in_array($val,$npShip))> {{ $nm }}</label>
                    <input type="hidden" name="meta_data[ship_custom][]" value="{{ $nm }}">
                  @endif
                @endforeach
              </div>
              <div class="np-form-row" style="margin-top:12px;align-items:center">
                <div class="np-form-group" style="margin:0;flex:1">
                  <label class="np-label">Add custom shipping method</label>
                  <input type="text" id="npShipCustomInput" class="np-input" placeholder="e.g. Courier Bike">
                </div>
                <button type="button" class="np-btn-add" style="margin-top:22px" onclick="npAddCustomShipMethod()">+ Add</button>
              </div>
              <div class="np-divider"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Preferred
                    Carrier</label><input type="text" name="meta_data[preferred_carrier]" class="np-input"
                    placeholder="e.g. DHL, FedEx, Aramex" value="{{ old('meta_data.preferred_carrier', data_get($npMeta,'preferred_carrier','')) }}"></div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Port of Loading</label><input
                    type="text" name="meta_data[port_of_loading]" class="np-input" placeholder="e.g. Hamad Port, Doha"
                    value="{{ old('meta_data.port_of_loading', data_get($npMeta,'port_of_loading','')) }}">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.shipping_rates">
            <div class="np-card-header"><span class="np-card-icon">💰</span><span class="np-card-title">Shipping
                Rates</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Free Shipping Threshold</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[free_ship_threshold]" class="np-input" placeholder="150" step="0.01"
                      value="{{ old('meta_data.free_ship_threshold', data_get($npMeta,'free_ship_threshold','')) }}"></div>
                </div>
                <div class="np-form-group"><label class="np-label">Standard Shipping Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[std_ship_fee]" class="np-input" placeholder="15.00" step="0.01"
                      value="{{ old('meta_data.std_ship_fee', data_get($npMeta,'std_ship_fee','')) }}"></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Express Shipping Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[exp_ship_fee]" class="np-input" placeholder="30.00" step="0.01"
                      value="{{ old('meta_data.exp_ship_fee', data_get($npMeta,'exp_ship_fee','')) }}"></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">International Shipping
                    Fee</label>
                  <div class="np-iw"><span
                      class="np-ipfx">{{ \App\CentralLogics\Helpers::currency_symbol() }}</span><input type="number"
                      name="meta_data[intl_ship_fee]" class="np-input" placeholder="60.00" step="0.01"
                      value="{{ old('meta_data.intl_ship_fee', data_get($npMeta,'intl_ship_fee','')) }}"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.multi_warehouse_inventory">
            <div class="np-card-header"><span class="np-card-icon">🏭</span><span class="np-card-title">Multi-Warehouse
                Inventory</span></div>
            <div class="np-card-body">
              <div class="np-info-box">🏬 Manage stock levels across multiple fulfilment centres and dark stores.</div>
              @php($whName = old('meta_data.wh_name', data_get($npMeta,'wh_name', [])))
              @php($whQty = old('meta_data.wh_qty', data_get($npMeta,'wh_qty', [])))
              @php($whAlert = old('meta_data.wh_alert', data_get($npMeta,'wh_alert', [])))
              @php($whStatus = old('meta_data.wh_status', data_get($npMeta,'wh_status', [])))
              @php($whName = is_array($whName) ? $whName : [])
              @php($whQty = is_array($whQty) ? $whQty : [])
              @php($whAlert = is_array($whAlert) ? $whAlert : [])
              @php($whStatus = is_array($whStatus) ? $whStatus : [])
              @php($whRows = max(count($whName), count($whQty), count($whAlert), count($whStatus), 1))

              <div id="npWhRows">
                @for($i=0;$i<$whRows;$i++)
                  <div class="np-form-row" style="align-items:center;margin-bottom:10px">
                    <input type="text" name="meta_data[wh_name][]" class="np-input"
                      placeholder="🏬 Warehouse name" value="{{ $whName[$i] ?? '' }}">
                    <input type="number" name="meta_data[wh_qty][]" class="np-input" placeholder="Qty"
                      style="max-width:110px;text-align:center" value="{{ $whQty[$i] ?? '' }}">
                    <input type="number" name="meta_data[wh_alert][]" class="np-input" placeholder="Alert"
                      style="max-width:110px;text-align:center" value="{{ $whAlert[$i] ?? '' }}">
                    @php($st = $whStatus[$i] ?? 'Active')
                    <select name="meta_data[wh_status][]" class="np-select"
                      style="max-width:140px;font-size:12px;padding:7px 10px">
                      <option value="Active" @selected($st==='Active')>Active</option>
                      <option value="Inactive" @selected($st==='Inactive')>Inactive</option>
                    </select>
                    <button type="button" class="np-btn-tiny del" onclick="this.closest('.np-form-row').remove()">✕</button>
                  </div>
                @endfor
              </div>
              <button type="button" class="np-btn-add" onclick="npAddWarehouseRow()">+ Add Warehouse</button>
            </div>
          </div>

          <div class="np-card" data-np-section="np-tab-logistics.dangerous_goods">
            <div class="np-card-header"><span class="np-card-icon">⚠️</span><span class="np-card-title">Dangerous Goods
                &amp; Restrictions</span></div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                  <span>Dangerous Goods Classification</span>
                  <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('dangerous_goods')">+</button>
                </label>
                <select name="meta_data[dangerous_goods]" id="npDangerousGoods" class="np-select js-select2-custom">
                  @php($savedDG = old('meta_data.dangerous_goods', data_get($npMeta,'dangerous_goods','Not Dangerous')))
                  @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                    @foreach(\App\Models\ProductSelectOption::where('type','dangerous_goods')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                      <option value="{{ $opt->name }}" @selected($savedDG == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                  @else
                    @foreach(['Not Dangerous','Class 1 — Explosives','Class 2 — Gases','Class 3 — Flammable Liquids','Class 4 — Flammable Solids','Class 8 — Corrosives','Class 9 — Miscellaneous'] as $v)
                      <option value="{{ $v }}" @selected($savedDG == $v)>{{ $v }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <span>Air Freight Restriction</span>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('air_restriction')">+</button>
                  </label>
                  <select name="meta_data[air_restriction]" id="npAirRestriction" class="np-select js-select2-custom">
                    @php($savedAir = old('meta_data.air_restriction', data_get($npMeta,'air_restriction','No restriction')))
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','air_restriction')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected($savedAir == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      @foreach(['No restriction','Cargo aircraft only','Prohibited'] as $v)
                        <option value="{{ $v }}" @selected($savedAir == $v)>{{ $v }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="np-form-group" style="margin-bottom:0">
                  <label class="np-label" style="display:flex;align-items:center;justify-content:space-between;gap:10px">
                    <span>Temperature Controlled Shipping</span>
                    <button type="button" class="np-btn-add np-btn-add-quick" onclick="npOpenProductSelectOptionModal('temp_controlled')">+</button>
                  </label>
                  <select name="meta_data[temp_controlled]" id="npTempControlled" class="np-select js-select2-custom">
                    @php($savedTemp = old('meta_data.temp_controlled', data_get($npMeta,'temp_controlled','Not required')))
                    @if(\Illuminate\Support\Facades\Schema::hasTable('product_select_options'))
                      @foreach(\App\Models\ProductSelectOption::where('type','temp_controlled')->where(function($q){ $q->whereNull('module_id')->orWhere('module_id', \Illuminate\Support\Facades\Config::get('module.current_module_id')); })->orderBy('name')->get(['id','name']) as $opt)
                        <option value="{{ $opt->name }}" @selected($savedTemp == $opt->name)>{{ $opt->name }}</option>
                      @endforeach
                    @else
                      @foreach(['Not required','Chilled (2–8°C)','Frozen (−18°C)'] as $v)
                        <option value="{{ $v }}" @selected($savedTemp == $v)>{{ $v }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div>
          <div class="np-card" data-np-section="np-tab-logistics.export_markets">
            <div class="np-card-header"><span class="np-card-icon">🌐</span><span class="np-card-title">Export
                Markets</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">Primary Export Regions</label>
                <div class="np-chk-grid">
                  @foreach([
                    'gcc' => '🌍 GCC',
                    'me' => '🌍 Middle East',
                    'africa' => '🌍 Africa',
                    'europe' => '🌍 Europe',
                    'na' => '🌎 North America',
                    'apac' => '🌏 Asia Pacific',
                  ] as $key => $label)
                    <label class="np-chk-item {{ in_array($key,$npExport) ? 'on' : '' }}"><input type="checkbox" name="meta_data[export][]"
                        value="{{ $key }}" @checked(in_array($key,$npExport))> {{ $label }}</label>
                  @endforeach
                  @foreach($npExportCustom as $nm)
                    @php($nm = is_string($nm) ? trim($nm) : '')
                    @if(filled($nm))
                      @php($val = 'custom:' . $nm)
                      <label class="np-chk-item {{ in_array($val,$npExport) ? 'on' : '' }}"><input type="checkbox" name="meta_data[export][]"
                          value="{{ $val }}" @checked(in_array($val,$npExport))> {{ $nm }}</label>
                      <input type="hidden" name="meta_data[export_custom][]" value="{{ $nm }}">
                    @endif
                  @endforeach
                </div>
                <div class="np-form-row" style="margin-top:12px;align-items:center">
                  <div class="np-form-group" style="margin:0;flex:1">
                    <label class="np-label">Add custom export region</label>
                    <input type="text" id="npExportCustomInput" class="np-input" placeholder="e.g. South America">
                  </div>
                  <button type="button" class="np-btn-add" style="margin-top:22px" onclick="npAddCustomExportRegion()">+ Add</button>
                </div>
              </div>
              <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Restricted Countries</label>
                <div class="np-tag-wrap" id="npRestrictWrap" onclick="this.querySelector('input').focus()">
                  @foreach($npRestricted as $t)
                    @php($t = is_string($t) ? trim($t) : '')
                    @if(filled($t))
                      <span class="np-tag t-orange">{{ $t }} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span><input type="hidden" name="meta_data[restricted_countries][]" value="{{ $t }}"></span>
                    @endif
                  @endforeach
                  <input type="text" id="npRestrictInput" class="np-input" placeholder="Add country, press Enter…"
                    onkeydown="npAddTag(event,'npRestrictWrap','npRestrictInput','t-orange','meta_data[restricted_countries][]')">
                </div>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.customs_import">
            <div class="np-card-header"><span class="np-card-icon">🛂</span><span class="np-card-title">Customs &amp;
                Import Details</span></div>
            <div class="np-card-body">
              <div class="np-form-group"><label class="np-label">HS Tariff Code</label><input type="text"
                  name="meta_data[hs_tariff]" class="np-input np-mono" placeholder="e.g. 0406.10.00"
                  value="{{ old('meta_data.hs_tariff', data_get($npMeta,'hs_tariff','')) }}"></div>
              <div class="np-form-group"><label class="np-label">Customs Description</label><input type="text"
                  name="meta_data[customs_desc]" class="np-input" placeholder="e.g. Fresh cream cheese"
                  value="{{ old('meta_data.customs_desc', data_get($npMeta,'customs_desc','')) }}"></div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Import Duty Rate</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[import_duty]" class="np-input"
                      placeholder="5" step="0.1" value="{{ old('meta_data.import_duty', data_get($npMeta,'import_duty','')) }}"><span class="np-isfx">%</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">VAT / GST Rate</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[vat_rate]" class="np-input"
                      placeholder="5" step="0.1" value="{{ old('meta_data.vat_rate', data_get($npMeta,'vat_rate','')) }}"><span class="np-isfx">%</span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.pallet_carton">
            <div class="np-card-header"><span class="np-card-icon">📦</span><span class="np-card-title">Pallet &amp;
                Carton Details</span></div>
            <div class="np-card-body">
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Units per Carton</label><input type="number"
                    name="meta_data[units_per_carton]" class="np-input" placeholder="12"
                    value="{{ old('meta_data.units_per_carton', data_get($npMeta,'units_per_carton','')) }}"></div>
                <div class="np-form-group"><label class="np-label">Cartons per Pallet</label><input type="number"
                    name="meta_data[cartons_per_pallet]" class="np-input" placeholder="48"
                    value="{{ old('meta_data.cartons_per_pallet', data_get($npMeta,'cartons_per_pallet','')) }}"></div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group"><label class="np-label">Carton Length</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_l]" class="np-input"
                      placeholder="0.0" value="{{ old('meta_data.carton_l', data_get($npMeta,'carton_l','')) }}"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group"><label class="np-label">Carton Width</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_w]" class="np-input"
                      placeholder="0.0" value="{{ old('meta_data.carton_w', data_get($npMeta,'carton_w','')) }}"><span class="np-isfx">cm</span></div>
                </div>
              </div>
              <div class="np-form-row">
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Carton Height</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_h]" class="np-input"
                      placeholder="0.0" value="{{ old('meta_data.carton_h', data_get($npMeta,'carton_h','')) }}"><span class="np-isfx">cm</span></div>
                </div>
                <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Carton Gross Weight</label>
                  <div class="np-iw sfx"><input type="number" name="meta_data[carton_kg]" class="np-input"
                      placeholder="0.0" value="{{ old('meta_data.carton_kg', data_get($npMeta,'carton_kg','')) }}"><span class="np-isfx">kg</span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-logistics.quality_inspection">
            <div class="np-card-header"><span class="np-card-icon">🔍</span><span class="np-card-title">Quality
                Inspection</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              @php($preShip = (string) old('meta_data.pre_ship_inspection', data_get($npMeta,'pre_ship_inspection','1')) === '1')
              @php($labTest = (string) old('meta_data.lab_testing', data_get($npMeta,'lab_testing','0')) === '1')
              @php($factoryAudit = (string) old('meta_data.factory_audit', data_get($npMeta,'factory_audit','1')) === '1')
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Pre-shipment Inspection</div>
                  <div class="np-tdsc">Third-party QC before dispatch</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[pre_ship_inspection]" value="1"
                    @checked($preShip)><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Lab Testing Available</div>
                  <div class="np-tdsc">SGS / Intertek / Bureau Veritas</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[lab_testing]" value="1" @checked($labTest)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Factory Audit Report</div>
                  <div class="np-tdsc">Available on request</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[factory_audit]" value="1"
                    @checked($factoryAudit)><span class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">Inspection
                  Agency</label><input type="text" name="meta_data[inspection_agency]" class="np-input"
                  placeholder="e.g. SGS, Intertek, QIMA"
                  value="{{ old('meta_data.inspection_agency', data_get($npMeta,'inspection_agency','')) }}"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ══ TAB 8: REVIEWS & ANALYTICS ══ --}}
    <div class="np-tab-panel" id="np-tab-reviews">
      <?php
        $npAcceptInq = (string) old('meta_data.accept_inquiries', data_get($npMeta,'accept_inquiries','1')) === '1';
        $npAutoReply = (string) old('meta_data.auto_reply', data_get($npMeta,'auto_reply','1')) === '1';
        $npBulkInq = (string) old('meta_data.bulk_inquiry', data_get($npMeta,'bulk_inquiry','0')) === '1';
        $npAutoReplyMsg = old('meta_data.auto_reply_msg', data_get($npMeta,'auto_reply_msg',''));
        $npAllowReviews = (string) old('meta_data.allow_reviews', data_get($npMeta,'allow_reviews','1')) === '1';
        $npVerifiedOnly = (string) old('meta_data.verified_only', data_get($npMeta,'verified_only','0')) === '1';
        $npAutoPublishReviews = (string) old('meta_data.auto_publish_reviews', data_get($npMeta,'auto_publish_reviews','1')) === '1';

        // Optional: seed reviews (stored in item meta_data) – internal admin-only data.
        $npSeedName = old('meta_data.seed_review_name', data_get($npMeta,'seed_review_name', []));
        $npSeedTitle = old('meta_data.seed_review_title', data_get($npMeta,'seed_review_title', []));
        $npSeedBody = old('meta_data.seed_review_body', data_get($npMeta,'seed_review_body', []));
        $npSeedDate = old('meta_data.seed_review_date', data_get($npMeta,'seed_review_date', []));
        $npSeedRating = old('meta_data.seed_review_rating', data_get($npMeta,'seed_review_rating', []));
        $npSeedVerified = old('meta_data.seed_review_verified', data_get($npMeta,'seed_review_verified', []));
        foreach (['npSeedName','npSeedTitle','npSeedBody','npSeedDate','npSeedRating','npSeedVerified'] as $__k) {
          if (!is_array($$__k)) { $$__k = []; }
        }
        $npSeedRows = max(count($npSeedName), count($npSeedTitle), count($npSeedBody), count($npSeedDate), count($npSeedRating), count($npSeedVerified), 1);

        // Live reviews (from DB) – shown in edit mode only.
        $npReviewStats = ['avg' => null, 'count' => 0, 'by' => [1=>0,2=>0,3=>0,4=>0,5=>0], 'latest' => collect()];
        if ($isEdit && filled($product?->id)) {
          try {
            $q = \App\Models\Review::with('customer')->where('item_id', $product->id);
            $npReviewStats['count'] = (clone $q)->count();
            $npReviewStats['avg'] = $npReviewStats['count'] ? round((clone $q)->avg('rating') ?: 0, 1) : null;
            for ($r=1;$r<=5;$r++) { $npReviewStats['by'][$r] = (clone $q)->where('rating', $r)->count(); }
            $npReviewStats['latest'] = (clone $q)->latest()->limit(3)->get();
          } catch (\Throwable $e) { /* ignore */ }
        }

        // Analytics inputs (stored in meta_data for now; project doesn't expose tracking sources here)
        $npPis = (float) old('meta_data.pis_score', data_get($npMeta,'pis_score','0'));
        $npViews30 = (int) old('meta_data.page_views_30d', data_get($npMeta,'page_views_30d','0'));
        $npATC30 = (int) old('meta_data.add_to_cart_30d', data_get($npMeta,'add_to_cart_30d','0'));
        $npOrders30 = (int) old('meta_data.orders_30d', data_get($npMeta,'orders_30d','0'));
        $npRevenue30 = (string) old('meta_data.revenue_30d', data_get($npMeta,'revenue_30d',''));
        $npConv = $npViews30 > 0 ? round(($npOrders30 / max($npViews30,1))*100, 1) : null;
      ?>

      <div class="np-stat-grid">
        <div class="np-stat-card">
          <div class="np-stat-val">{{ $npReviewStats['avg'] !== null ? number_format($npReviewStats['avg'], 1) : '—' }}</div>
          @php($avgInt = $npReviewStats['avg'] !== null ? (int) round((float) $npReviewStats['avg']) : 0)
          <div class="np-star-row" style="justify-content:center;margin-bottom:4px">
            @for($i=1;$i<=5;$i++)
              <span class="np-star {{ $i <= $avgInt ? 'lit' : '' }}">★</span>
            @endfor
          </div>
          <div class="np-stat-lbl">Avg. Rating</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:var(--np-success)">{{ (int) $npReviewStats['count'] }}</div>
          <div class="np-stat-lbl">Total Reviews</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:#0ea5e9">{{ number_format($npViews30) }}</div>
          <div class="np-stat-lbl">Product Views</div>
        </div>
        <div class="np-stat-card">
          <div class="np-stat-val" style="color:var(--np-warn)">{{ $npConv !== null ? ($npConv . '%') : '—' }}</div>
          <div class="np-stat-lbl">Conversion Rate</div>
        </div>
      </div>
      <div class="np-grid np-grid--reviews-balanced">
        <div class="np-reviews-col np-reviews-col--main">
          <div class="np-card" data-np-section="np-tab-reviews.rating_breakdown">
            <div class="np-card-header"><span class="np-card-icon">📊</span><span class="np-card-title">Rating
                Breakdown</span></div>
            <div class="np-card-body">
              @php($rc = (int) $npReviewStats['count'])
              @foreach([5=>'#00a550',4=>'#34d399',3=>'#f59e0b',2=>'#f97316',1=>'#e2001a'] as $r => $clr)
                @php($cnt = (int) data_get($npReviewStats['by'], $r, 0))
                @php($pct = $rc > 0 ? round(($cnt / $rc) * 100) : 0)
                <div class="np-chart-row">
                  <span class="np-chart-lbl">{{ $r }} ★</span>
                  <div class="np-chart-bar-wrap">
                    <div class="np-chart-bar-fill" style="width:{{ $pct }}%;background:{{ $clr }}"></div>
                  </div>
                  <span class="np-chart-count">{{ $cnt }}</span>
                </div>
              @endforeach
              @if(!$rc)
                <div class="np-hint mt-2">Reviews will appear here after the product is published.</div>
              @endif
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-reviews.customer_reviews">
            <div class="np-card-header"><span class="np-card-icon">💬</span><span class="np-card-title">Customer
                Reviews</span><span class="np-card-subtitle">Most recent first</span></div>
            <div class="np-card-body">
              <?php if(($npReviewStats['latest'] ?? collect())->count()): ?>
                <?php foreach(($npReviewStats['latest'] ?? collect()) as $rev): ?>
                  <?php
                    $nm = trim(($rev?->customer?->f_name ?? '') . ' ' . ($rev?->customer?->l_name ?? ''));
                    $nm = $nm ?: ('User #' . ((int) ($rev?->user_id ?? 0)));
                    $av = strtoupper(substr(trim($rev?->customer?->f_name ?? 'U'),0,1) . substr(trim($rev?->customer?->l_name ?? 'S'),0,1));
                    $stars = (int) ($rev?->rating ?? 0);
                  ?>
                  <div class="np-review-card">
                    <div class="np-review-meta">
                      <div class="np-reviewer-avatar">{{ $av }}</div>
                      <div>
                        <div class="np-reviewer-name">{{ $nm }}</div>
                        <div class="np-review-stars">
                          @for($i=1;$i<=5;$i++)
                            <span style="color:{{ $i <= $stars ? '#f59e0b' : '#d1d5db' }}">★</span>
                          @endfor
                        </div>
                      </div>
                      <div class="np-reviewer-date">{{ optional($rev?->created_at)->format('M d, Y') }}</div>
                    </div>
                    <div class="np-review-body">{{ $rev?->comment ?? '' }}</div>
                    <div class="np-review-actions">
                      <span class="np-review-badge {{ ((int) ($rev?->status ?? 1)) === 1 ? 'rb-pub' : 'rb-flag' }}">
                        {{ ((int) ($rev?->status ?? 1)) === 1 ? '✅ Published' : '🙈 Hidden' }}
                      </span>
                      <a class="np-btn-tiny" href="{{ route('admin.item.reviews') }}" target="_blank" rel="noopener">Manage</a>
                    </div>
                  </div>
                <?php endforeach; ?>
                <a class="np-btn-add" style="margin-top:10px;display:inline-flex" href="{{ route('admin.item.reviews') }}" target="_blank"
                  rel="noopener">Load More Reviews…</a>
              <?php else: ?>
                <div class="np-hint">No reviews yet. Reviews will appear here after the product is published and customers
                  leave feedback.</div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="np-reviews-col np-reviews-col--side">
          <div class="np-card" data-np-section="np-tab-reviews.add_seed_review">
            <div class="np-card-header"><span class="np-card-icon">✍️</span><span class="np-card-title">Add / Seed
                Review</span></div>
            <div class="np-card-body">
              <div class="np-hint" style="margin-bottom:10px">Optional admin-only seed reviews (stored in product meta).
                Use for demos; real customer reviews appear automatically after publish.</div>

              <div id="npSeedReviewRows">
                @for($i=0;$i<$npSeedRows;$i++)
                  @php($rt = (int) ($npSeedRating[$i] ?? 5))
                  <div class="np-card" style="margin:0 0 12px;border-style:dashed">
                    <div class="np-card-body">
                      <div class="np-form-group"><label class="np-label">Reviewer Name</label>
                        <input type="text" name="meta_data[seed_review_name][]" class="np-input"
                          placeholder="e.g. Mohammed Al-Farsi" value="{{ $npSeedName[$i] ?? '' }}">
                      </div>
                      <div class="np-form-group">
                        <label class="np-label">Star Rating</label>
                        <div class="np-star-row" data-np-stars="seed">
                          <input type="hidden" name="meta_data[seed_review_rating][]" value="{{ $rt }}">
                          @for($s=1;$s<=5;$s++)
                            <span class="np-star {{ $s <= $rt ? 'lit' : '' }}" onclick="npSetSeedStars(this, {{ $s }})">★</span>
                          @endfor
                          <span style="font-size:12px;color:var(--np-muted);margin-left:6px" class="np-star-label">{{ $rt }}
                            / 5</span>
                        </div>
                      </div>
                      <div class="np-form-group"><label class="np-label">Review Title</label>
                        <input type="text" name="meta_data[seed_review_title][]" class="np-input"
                          placeholder="e.g. Great product!" value="{{ $npSeedTitle[$i] ?? '' }}">
                      </div>
                      <div class="np-form-group"><label class="np-label">Review Body</label>
                        <textarea name="meta_data[seed_review_body][]" class="np-textarea" rows="3"
                          placeholder="Write review text…">{{ $npSeedBody[$i] ?? '' }}</textarea>
                      </div>
                      <div class="np-form-row" style="align-items:flex-end">
                        <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Review Date</label>
                          <input type="date" name="meta_data[seed_review_date][]" class="np-input"
                            value="{{ $npSeedDate[$i] ?? '' }}">
                        </div>
                        @php($vv = (string) ($npSeedVerified[$i] ?? '1') === '1')
                        <div class="np-form-group" style="margin-bottom:0">
                          <label class="np-label">Verified Purchase?</label>
                          <label class="np-tog" style="margin-top:6px"><input type="checkbox"
                              onchange="this.closest('.np-form-group').querySelector('input[type=hidden]').value = this.checked ? '1':'0'"
                              @checked($vv)><span class="np-tog-track"></span></label>
                          <input type="hidden" name="meta_data[seed_review_verified][]" value="{{ $vv ? '1' : '0' }}">
                        </div>
                      </div>
                      <div style="display:flex;justify-content:flex-end;margin-top:10px">
                        <button type="button" class="np-btn-tiny del" onclick="this.closest('.np-card').remove()">Remove</button>
                      </div>
                    </div>
                  </div>
                @endfor
              </div>

              <button type="button" class="np-btn np-btn-outline" style="width:100%;justify-content:center"
                onclick="npAddSeedReviewRow()">+ Add Review</button>
            </div>
          </div>

          <div class="np-card" data-np-section="np-tab-reviews.product_analytics">
            <div class="np-card-header"><span class="np-card-icon">📈</span><span class="np-card-title">Product
                Analytics</span></div>
            <div class="np-card-body">
              <div class="np-form-group">
                <label class="np-label">Product Information Score (PIS)</label>
                <div class="np-pis-row">
                  <div class="np-mini-bar"><div class="np-mini-fill"
                      style="width:{{ max(0, min(100, round(($npPis/5)*100))) }}%;background:var(--np-success)"></div></div>
                  <span style="font-size:13px;font-weight:700;color:var(--np-success);white-space:nowrap">{{ number_format($npPis, 1) }} / 5.0</span>
                </div>
                <div class="np-pis-input-row">
                  <label class="np-label" for="npPisScoreInput">Score</label>
                  <input id="npPisScoreInput" type="number" step="0.1" min="0" max="5" name="meta_data[pis_score]" class="np-input"
                    value="{{ old('meta_data.pis_score', data_get($npMeta,'pis_score','0')) }}">
                </div>
                <div class="np-hint">Premium product threshold: 4.0+</div>
              </div>
              <div class="np-divider"></div>
              <div class="np-analytics-metric-grid">
                <div class="np-analytics-metric">
                  <div class="np-analytics-metric-label">Page Views (30d)</div>
                  <div class="np-analytics-metric-row">
                    <span class="np-analytics-metric-display">{{ number_format($npViews30) }}</span>
                    <input type="number" min="0" name="meta_data[page_views_30d]" class="np-input"
                      value="{{ old('meta_data.page_views_30d', data_get($npMeta,'page_views_30d','0')) }}">
                  </div>
                </div>
                <div class="np-analytics-metric">
                  <div class="np-analytics-metric-label">Add to Cart (30d)</div>
                  <div class="np-analytics-metric-row">
                    <span class="np-analytics-metric-display info">{{ number_format($npATC30) }}</span>
                    <input type="number" min="0" name="meta_data[add_to_cart_30d]" class="np-input"
                      value="{{ old('meta_data.add_to_cart_30d', data_get($npMeta,'add_to_cart_30d','0')) }}">
                  </div>
                </div>
                <div class="np-analytics-metric">
                  <div class="np-analytics-metric-label">Orders (30d)</div>
                  <div class="np-analytics-metric-row">
                    <span class="np-analytics-metric-display success">{{ number_format($npOrders30) }}</span>
                    <input type="number" min="0" name="meta_data[orders_30d]" class="np-input"
                      value="{{ old('meta_data.orders_30d', data_get($npMeta,'orders_30d','0')) }}">
                  </div>
                </div>
                <div class="np-analytics-metric">
                  <div class="np-analytics-metric-label">Revenue (30d)</div>
                  <div class="np-analytics-metric-row">
                    <span class="np-analytics-metric-display warn">{{ filled($npRevenue30) ? $npRevenue30 : '—' }}</span>
                    <input type="text" name="meta_data[revenue_30d]" class="np-input"
                      value="{{ old('meta_data.revenue_30d', data_get($npMeta,'revenue_30d','')) }}" placeholder="e.g. QAR 7,980">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="np-card" data-np-section="np-tab-reviews.inquiry_rfq_settings">
            <div class="np-card-header"><span class="np-card-icon">📩</span><span class="np-card-title">Inquiry / RFQ
                Settings</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Accept Inquiries</div>
                  <div class="np-tdsc">Allow buyers to send RFQs</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[accept_inquiries]" value="1"
                    @checked($npAcceptInq)><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Auto-Reply Enabled</div>
                  <div class="np-tdsc">Respond instantly to new inquiries</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[auto_reply]" value="1" @checked($npAutoReply)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Bulk Inquiry Form</div>
                  <div class="np-tdsc">Show bulk order form on page</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[bulk_inquiry]" value="1" @checked($npBulkInq)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-form-group" style="margin-top:14px;margin-bottom:0"><label class="np-label">Auto-Reply
                  Message</label><textarea name="meta_data[auto_reply_msg]" class="np-textarea" rows="2"
                  placeholder="Thank you for your inquiry! We will get back to you within 24 hours…">{{ $npAutoReplyMsg }}</textarea></div>
            </div>
          </div>
          <div class="np-card" data-np-section="np-tab-reviews.review_settings">
            <div class="np-card-header"><span class="np-card-icon">🧩</span><span class="np-card-title">Review
                Settings</span></div>
            <div class="np-card-body" style="padding:6px 20px 12px">
              <div class="np-trow" style="padding-top:8px">
                <div>
                  <div class="np-tlbl">Allow Reviews</div>
                  <div class="np-tdsc">Customers can leave reviews</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[allow_reviews]" value="1"
                    @checked($npAllowReviews)><span class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Verified Purchase Only</div>
                  <div class="np-tdsc">Only buyers can review</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[verified_only]" value="1" @checked($npVerifiedOnly)><span
                    class="np-tog-track"></span></label>
              </div>
              <div class="np-trow">
                <div>
                  <div class="np-tlbl">Auto-publish Reviews</div>
                  <div class="np-tdsc">No manual moderation needed</div>
                </div><label class="np-tog"><input type="checkbox" name="meta_data[auto_publish_reviews]" value="1"
                    @checked($npAutoPublishReviews)><span class="np-tog-track"></span></label>
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
            <div class="form-group mt-3 mb-0" id="npProductSelectOptionValueWrap" style="display:none">
              <label class="input-label">Colour (Hex) <small class="text-danger">*</small></label>
              <div class="d-flex align-items-center" style="gap:10px">
                <input type="color" id="npProductSelectOptionColorPicker" class="form-control" style="max-width:72px;padding:3px" value="#2563eb">
                <input type="text" name="value" id="npProductSelectOptionValue" class="form-control" placeholder="#2563eb">
              </div>
              <small class="text-muted d-block mt-2">Pick a colour or type a hex value like #ff0000.</small>
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

  {{-- Attribute quick add modal --}}
  <div class="modal fade" id="npAttributeModal" tabindex="-1" role="dialog" aria-labelledby="npAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="npAttributeForm">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="npAttributeModalLabel">Add Attribute</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-0">
              <label class="input-label">{{ translate('messages.name') }} <small class="text-danger">*</small></label>
              <input type="text" name="name" id="npAttributeName" class="form-control" placeholder="e.g. Size, Colour, Material" required>
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

  {{-- Manage tabs modal --}}
  <div class="modal fade" id="npManageTabsModal" tabindex="-1" role="dialog" aria-labelledby="npManageTabsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="npManageTabsModalLabel">Manage Tabs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="np-info-box">Use this to rename/hide built-in tabs and rename/delete custom tabs. (Built-in tabs can be hidden, not deleted.)</div>
          <div class="table-responsive">
            <table class="table table-bordered mb-0">
              <thead>
                <tr>
                  <th style="width:220px">Tab</th>
                  <th>Title</th>
                  <th style="width:110px">Visible</th>
                  <th style="width:170px">Actions</th>
                </tr>
              </thead>
              <tbody id="npManageTabsTbody"></tbody>
            </table>
          </div>
          <small class="text-muted d-block mt-2">Custom tab values/fields can be edited inside the tab. To remove a value, clear the field.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
          <button type="button" class="btn btn-primary" onclick="npSaveManageTabsAndClose()">{{ translate('messages.save') }}</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Manage sections modal (built-in tabs) --}}
  <div class="modal fade" id="npManageSectionsModal" tabindex="-1" role="dialog" aria-labelledby="npManageSectionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="npManageSectionsModalLabel">Manage Sections</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="np-info-box">Hide/show built-in sections. Hidden sections are removed from the UI.</div>
          <div id="npManageSectionsBody"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ translate('messages.cancel') }}</button>
          <button type="button" class="btn btn-primary" onclick="npSaveManageSectionsAndClose()">{{ translate('messages.save') }}</button>
        </div>
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

    function npIsTabHidden(tabId) {
      try {
        let mem = null;
        try {
          mem = npTabsUi?.built_in?.[tabId];
        } catch (e) {
          mem = null;
        }
        if (mem && Object.prototype.hasOwnProperty.call(mem, 'hidden')) {
          return !!mem.hidden;
        }
        const raw = document.getElementById('custom_tabs_ui_json')?.value || '';
        if (!raw) return false;
        const ui = JSON.parse(raw);
        return !!ui?.built_in?.[tabId]?.hidden;
      } catch {
        return false;
      }
    }

    function npVisibleTabIds() {
      return npTabIds.filter(id => !npIsTabHidden(id));
    }

    function npCurrentTabIndex() {
      const activePanel = document.querySelector('.np-tab-panel.active');
      const ids = npVisibleTabIds();
      return Math.max(ids.indexOf(activePanel?.id), 0);
    }

    function npSetActionButtons() {
      const activeTabId = document.querySelector('.np-tab-panel.active')?.id || 'np-tab-general';
      const idx = Math.max(npTabIds.indexOf(activeTabId), 0);
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
      const ids = npVisibleTabIds();
      const targetIndex = Math.max(0, Math.min(index, ids.length - 1));
      const id = ids[targetIndex];
      if (!id) return;
      const btn = document.querySelector(`.np-tab-btn[data-tab="${id}"]`)
        || Array.from(document.querySelectorAll('.np-tab-btn')).find(b => (b.getAttribute('onclick') || '').includes(`'${id}'`));

      // Hard fallback: activate by id even if button lookup fails
      if (!btn) {
        document.querySelectorAll('.np-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.np-tab-panel').forEach(p => p.classList.remove('active'));
        const panel = document.getElementById(id);
        if (panel) panel.classList.add('active');
        const btn2 = document.querySelector(`.np-tab-btn[data-tab="${id}"]`);
        if (btn2) btn2.classList.add('active');
        npSetActionButtons();
        return;
      }

      npSwitchTab(btn, id);
    }

    function npPrevTab() {
      const activeTabId = document.querySelector('.np-tab-panel.active')?.id || 'np-tab-general';
      const idx = Math.max(npTabIds.indexOf(activeTabId), 0);
      const prevId = npTabIds[idx - 1] || '';
      if (!prevId) return;

      // Force-activate previous tab by id (robust even if tabs are "hidden" in UI overrides)
      document.querySelectorAll('.np-tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.np-tab-panel').forEach(p => p.classList.remove('active'));
      const panel = document.getElementById(prevId);
      if (panel) panel.classList.add('active');
      const btn = document.querySelector(`.np-tab-btn[data-tab="${prevId}"]`);
      if (btn) btn.classList.add('active');
      npSetActionButtons();
    }

    function npNextTab() {
      // Next should behave like a step wizard: validate current tab first
      if (!npValidateCurrentTab()) return;
      if (window.__npWizardSaving) return;
      npPersistWizardStep({ publish: false, moveNext: true });
    }

    function npValidateCurrentTab() {
      const ids = npVisibleTabIds();
      const currentTabId = ids[npCurrentTabIndex()];
      const errors = [];
      npClearFieldErrors();

      function invalid(sel, msg) {
        const el = document.querySelector(sel);
        if (el) npMarkFieldInvalid(el, msg);
        errors.push(msg);
      }

      if (currentTabId === 'np-tab-general') {
        if (!$('#productNameEn').val()?.trim()) invalid('#productNameEn', 'Product name is required.');
        if (!$('#npShortDesc').val()?.trim()) invalid('#npShortDesc', 'Short description is required.');
        if (!$('#store_id').val()) invalid('#store_id', 'Store is required.');
        if (!$('#category_id').val()) invalid('#category_id', 'Category is required.');
        if (!(parseFloat($('#npRegPrice').val()) > 0)) invalid('#npRegPrice', 'Price must be greater than 0.');
      }

      if (currentTabId === 'np-tab-media') {
        const hasThumbnail = $('#item_id').val() || ($('#customFileEg1')[0] && $('#customFileEg1')[0].files.length > 0);
        if (!hasThumbnail) invalid('#customFileEg1', 'Thumbnail image is required.');
      }

      if (errors.length) {
        // One toast is enough because field-level hints are now visible
        toastr.error(errors[0], { CloseButton: true, ProgressBar: true });
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

    // ═══ INCOTERM PILL TOGGLE (hidden input 1/0) ═══
    function npToggleIncoterm(el) {
      if (!el) return;
      el.classList.toggle('sel');
      const inp = el.querySelector('input[type="hidden"]');
      if (inp) inp.value = el.classList.contains('sel') ? '1' : '0';
      const cb = el.querySelector('input[type="checkbox"]');
      if (cb) cb.checked = el.classList.contains('sel');
    }

    // Keep checkbox-card visuals in sync with actual inputs (so saving works reliably)
    document.addEventListener('change', function (e) {
      const t = e.target;
      if (!t || t.tagName !== 'INPUT' || t.type !== 'checkbox') return;
      const lab = t.closest('.np-chk-item');
      if (!lab) return;
      lab.classList.toggle('on', !!t.checked);
    });

    function npAddWarehouseRow() {
      const wrap = document.getElementById('npWhRows');
      if (!wrap) return;
      const row = document.createElement('div');
      row.className = 'np-form-row';
      row.style.alignItems = 'center';
      row.style.marginBottom = '10px';
      row.innerHTML = `
        <input type="text" name="meta_data[wh_name][]" class="np-input" placeholder="🏬 Warehouse name" value="">
        <input type="number" name="meta_data[wh_qty][]" class="np-input" placeholder="Qty" style="max-width:110px;text-align:center" value="">
        <input type="number" name="meta_data[wh_alert][]" class="np-input" placeholder="Alert" style="max-width:110px;text-align:center" value="">
        <select name="meta_data[wh_status][]" class="np-select" style="max-width:140px;font-size:12px;padding:7px 10px">
          <option value="Active" selected>Active</option>
          <option value="Inactive">Inactive</option>
        </select>
        <button type="button" class="np-btn-tiny del" onclick="this.closest('.np-form-row').remove()">✕</button>
      `;
      wrap.appendChild(row);
    }

    function npAddCustomIncoterm() {
      const input = document.getElementById('npIncoCustomInput');
      const grid = document.querySelector('#np-tab-logistics .np-inco-grid');
      if (!input || !grid) return;
      const code = String(input.value || '').trim().toUpperCase();
      if (!code) return;
      input.value = '';

      // Prevent duplicates
      const exists = Array.from(grid.querySelectorAll('.np-inco-pill')).some(p => String(p.childNodes?.[0]?.textContent || p.textContent || '').trim().startsWith(code));
      if (exists) return;

      const pill = document.createElement('div');
      pill.className = 'np-inco-pill sel';
      pill.setAttribute('onclick', 'npToggleIncoterm(this)');
      pill.innerHTML = `
        ${code}
        <input type="hidden" name="meta_data[incoterms_custom][]" value="${code.replace(/"/g, '&quot;')}">
        <input type="checkbox" class="d-none" name="meta_data[incoterms][]" value="${code.replace(/"/g, '&quot;')}" checked>
      `;
      grid.appendChild(pill);
    }

    function npAddCustomShipMethod() {
      const input = document.getElementById('npShipCustomInput');
      const grid = document.querySelector('#np-tab-logistics [data-np-section="np-tab-logistics.shipping_methods"] .np-chk-grid');
      if (!input || !grid) return;
      const name = String(input.value || '').trim();
      if (!name) return;
      input.value = '';

      const val = 'custom:' + name;
      const exists = Array.from(grid.querySelectorAll('input[type="checkbox"][name="meta_data[ship][]"]')).some(i => String(i.value) === val);
      if (exists) return;

      const label = document.createElement('label');
      label.className = 'np-chk-item on';
      label.innerHTML = `<input type="checkbox" name="meta_data[ship][]" value="${val.replace(/"/g, '&quot;')}" checked> ${name}`;
      grid.appendChild(label);

      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'meta_data[ship_custom][]';
      hidden.value = name;
      grid.appendChild(hidden);
    }

    function npAddCustomExportRegion() {
      const input = document.getElementById('npExportCustomInput');
      const grid = document.querySelector('#np-tab-logistics [data-np-section="np-tab-logistics.export_markets"] .np-chk-grid');
      if (!input || !grid) return;
      const name = String(input.value || '').trim();
      if (!name) return;
      input.value = '';

      const val = 'custom:' + name;
      const exists = Array.from(grid.querySelectorAll('input[type="checkbox"][name="meta_data[export][]"]')).some(i => String(i.value) === val);
      if (exists) return;

      const label = document.createElement('label');
      label.className = 'np-chk-item on';
      label.innerHTML = `<input type="checkbox" name="meta_data[export][]" value="${val.replace(/"/g, '&quot;')}" checked> ${name}`;
      grid.appendChild(label);

      const hidden = document.createElement('input');
      hidden.type = 'hidden';
      hidden.name = 'meta_data[export_custom][]';
      hidden.value = name;
      grid.appendChild(hidden);
    }

    // ═══ REVIEWS & ANALYTICS: Seed reviews UI helpers (meta_data only) ═══
    function npSetSeedStars(el, stars) {
      const row = el?.closest?.('.np-star-row');
      if (!row) return;
      const hidden = row.querySelector('input[type="hidden"][name="meta_data[seed_review_rating][]"]');
      if (hidden) hidden.value = String(stars);
      row.querySelectorAll('.np-star').forEach((s, idx) => s.classList.toggle('lit', (idx + 1) <= stars));
      const label = row.querySelector('.np-star-label');
      if (label) label.textContent = `${stars} / 5`;
    }

    function npAddSeedReviewRow() {
      const wrap = document.getElementById('npSeedReviewRows');
      if (!wrap) return;
      const card = document.createElement('div');
      card.className = 'np-card';
      card.style.margin = '0 0 12px';
      card.style.borderStyle = 'dashed';
      card.innerHTML = `
        <div class="np-card-body">
          <div class="np-form-group"><label class="np-label">Reviewer Name</label>
            <input type="text" name="meta_data[seed_review_name][]" class="np-input" placeholder="e.g. Mohammed Al-Farsi" value="">
          </div>
          <div class="np-form-group">
            <label class="np-label">Star Rating</label>
            <div class="np-star-row" data-np-stars="seed">
              <input type="hidden" name="meta_data[seed_review_rating][]" value="5">
              <span class="np-star lit" onclick="npSetSeedStars(this, 1)">★</span>
              <span class="np-star lit" onclick="npSetSeedStars(this, 2)">★</span>
              <span class="np-star lit" onclick="npSetSeedStars(this, 3)">★</span>
              <span class="np-star lit" onclick="npSetSeedStars(this, 4)">★</span>
              <span class="np-star lit" onclick="npSetSeedStars(this, 5)">★</span>
              <span style="font-size:12px;color:var(--np-muted);margin-left:6px" class="np-star-label">5 / 5</span>
            </div>
          </div>
          <div class="np-form-group"><label class="np-label">Review Title</label>
            <input type="text" name="meta_data[seed_review_title][]" class="np-input" placeholder="e.g. Great product!" value="">
          </div>
          <div class="np-form-group"><label class="np-label">Review Body</label>
            <textarea name="meta_data[seed_review_body][]" class="np-textarea" rows="3" placeholder="Write review text…"></textarea>
          </div>
          <div class="np-form-row" style="align-items:flex-end">
            <div class="np-form-group" style="margin-bottom:0"><label class="np-label">Review Date</label>
              <input type="date" name="meta_data[seed_review_date][]" class="np-input" value="">
            </div>
            <div class="np-form-group" style="margin-bottom:0">
              <label class="np-label">Verified Purchase?</label>
              <label class="np-tog" style="margin-top:6px"><input type="checkbox"
                onchange="this.closest('.np-form-group').querySelector('input[type=hidden]').value = this.checked ? '1':'0'" checked><span class="np-tog-track"></span></label>
              <input type="hidden" name="meta_data[seed_review_verified][]" value="1">
            </div>
          </div>
          <div style="display:flex;justify-content:flex-end;margin-top:10px">
            <button type="button" class="np-btn-tiny del" onclick="this.closest('.np-card').remove()">Remove</button>
          </div>
        </div>
      `;
      wrap.appendChild(card);
    }

    function npToggleDeliveryCard(cardEl) {
      if (!cardEl) return;
      cardEl.classList.toggle('sel');
      const cb = cardEl.querySelector('input[type="checkbox"]');
      if (cb) cb.checked = cardEl.classList.contains('sel');
    }

    function npSyncDeliveryCardsFromInputs() {
      document.querySelectorAll('.np-dlv-card').forEach(card => {
        const cb = card.querySelector('input[type="checkbox"]');
        if (!cb) return;
        card.classList.toggle('sel', !!cb.checked);
      });
    }

    function npSyncStatusPillsFromInputs() {
      const checked = document.querySelector('input[name="status"]:checked');
      if (!checked) return;
      const val = String(checked.value);
      document.querySelectorAll('.np-pill').forEach(p => p.classList.remove('sel', 'out', 'draft'));
      const pill = Array.from(document.querySelectorAll('.np-pill')).find(p => p.querySelector('input[name="status"]')?.value === val);
      if (!pill) return;
      pill.classList.add('sel');
      if (val === '0') pill.classList.add('out');
      if (val === '2') pill.classList.add('draft');
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
    let npRemovedImageKeys = [];
    function npHandleFiles(e) { Array.from(e.target.files).forEach(npAddImg); }
    function npHandleDrop(e) { e.preventDefault(); document.getElementById('npUploadZone').classList.remove('drag'); Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/')).forEach(npAddImg); }
    function npAddImg(file) {
      if (npImgs.length >= 8) return;
      const r = new FileReader();
      r.onload = ev => { npImgs.push({ src: ev.target.result, alt: file.name.replace(/\.[^.]+$/, ''), key: '' }); npRenderImgs(); npRenderAlt(); npUpdateQuality(); };
      r.readAsDataURL(file);
    }
    function npSyncRemovedImageKeys() {
      try {
        const el = document.getElementById('removedImageKeys');
        if (el) el.value = (npRemovedImageKeys || []).join(',');
      } catch (e) { }
    }
    function npRenderImgs() {
      document.getElementById('npImagePreviews').innerHTML = npImgs.map((img, i) => `
              <div class="np-img-thumb"><img src="${img.src}">
              <span class="np-th-rm" onclick="npRmImg(${i})">×</span>
              ${i === 0 ? '<span class="np-th-main">MAIN</span>' : ''}</div>`).join('');
      npSyncRemovedImageKeys();
    }
    function npRmImg(i) {
      try {
        const img = npImgs[i];
        if (img && img.key) {
          npRemovedImageKeys = npRemovedImageKeys || [];
          if (!npRemovedImageKeys.includes(img.key)) npRemovedImageKeys.push(img.key);
        }
      } catch (e) { }
      npImgs.splice(i, 1);
      npRenderImgs();
      npRenderAlt();
      npUpdateQuality();
    }
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

    function npSyncSwatchHiddenInputs() {
      const wrap = document.getElementById('npSwatchesWrap');
      if (!wrap) return;
      wrap.querySelectorAll('input[type="hidden"][name="meta_data[variant_swatches][]"]').forEach(n => n.remove());
      wrap.querySelectorAll('.np-swatch.sel').forEach(sw => {
        const hex = sw.getAttribute('data-hex') || '';
        if (!hex) return;
        const hi = document.createElement('input');
        hi.type = 'hidden';
        hi.name = 'meta_data[variant_swatches][]';
        hi.value = hex;
        wrap.appendChild(hi);
      });
    }

    // Swatch selection needs hidden inputs to submit
    function npToggleSwatch(el) {
      if (!el) return;
      el.classList.toggle('sel');
      npSyncSwatchHiddenInputs();
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

    const npFormDraftKeyBase = 'np_item_form_draft_v1';
    const npFormDraftLastKey = 'np_item_form_draft_last_item_id_v1';
    function npFormDraftKey() {
      const id = ($('#item_id').val() || '').toString().trim();
      return `${npFormDraftKeyBase}:${id ? id : 'new'}`;
    }

    function npIsSkippableDraftField(el) {
      if (!el || !el.name) return true;
      if (el.disabled) return true;
      if (el.type === 'file' || el.type === 'password') return true;
      // Do not persist CSRF, buttons
      if (el.name === '_token') return true;
      return false;
    }

    function npSaveFormDraft() {
      const form = document.getElementById('item_form');
      if (!form) return;

      const data = {};
      const els = Array.from(form.elements || []);

      // group by name for checkbox[] and radio
      const byName = {};
      els.forEach(el => {
        if (npIsSkippableDraftField(el)) return;
        byName[el.name] = byName[el.name] || [];
        byName[el.name].push(el);
      });

      Object.keys(byName).forEach(name => {
        const group = byName[name];
        const first = group[0];

        if (first.type === 'radio') {
          const checked = group.find(x => x.checked);
          data[name] = checked ? checked.value : null;
          return;
        }

        if (first.type === 'checkbox') {
          // array checkbox e.g. meta_data[cert][]
          if (name.endsWith('[]')) {
            data[name] = group.filter(x => x.checked).map(x => x.value);
          } else {
            data[name] = group.some(x => x.checked) ? 1 : 0;
          }
          return;
        }

        if (first.tagName === 'SELECT' && first.multiple) {
          data[name] = Array.from(first.options).filter(o => o.selected).map(o => o.value);
          return;
        }

        // Array inputs like name[] / selling_point[] should persist all values
        if (name.endsWith('[]') && first.type !== 'checkbox') {
          data[name] = group.map(x => x.value);
          return;
        }

        data[name] = first.value;
      });

      // Track which tab was active
      data.__active_tab_id = document.querySelector('.np-tab-panel.active')?.id || 'np-tab-general';

      try {
        const key = npFormDraftKey();
        localStorage.setItem(key, JSON.stringify(data));
        // Remember latest item id draft so refresh can restore even when item_id input is empty initially
        const currentId = ($('#item_id').val() || '').toString().trim();
        if (currentId) localStorage.setItem(npFormDraftLastKey, currentId);
      } catch (e) { /* ignore */ }
    }

    function npApplyFieldValue(el, value) {
      if (!el) return;
      const $el = $(el);

      if (el.type === 'checkbox') {
        el.checked = (String(value) === '1' || value === 1 || value === true);
        $el.trigger('change');
        return;
      }

      if (el.type === 'radio') {
        // handled in group loader
        return;
      }

      if (el.tagName === 'SELECT' && el.multiple) {
        const arr = Array.isArray(value) ? value : [];
        Array.from(el.options).forEach(o => { o.selected = arr.includes(o.value); });
        $el.trigger('change');
        if ($el.hasClass('js-select2-custom')) $el.trigger('change.select2');
        return;
      }

      el.value = (value ?? '');
      $el.trigger('input').trigger('change');
      if ($el.hasClass('js-select2-custom')) $el.trigger('change.select2');
    }

    function npLoadFormDraft() {
      // Only for add-new experience; edit page should rely on DB prefill
      const isEdit = {{ $isEdit ? 'true' : 'false' }};
      if (isEdit) return;

      let raw = null;
      try {
        // 1) Try "new" draft
        raw = localStorage.getItem(`${npFormDraftKeyBase}:new`);
        // 2) If not found, try last saved item draft key
        if (!raw) {
          const lastId = (localStorage.getItem(npFormDraftLastKey) || '').toString().trim();
          if (lastId) raw = localStorage.getItem(`${npFormDraftKeyBase}:${lastId}`);
        }
        // 3) Finally, try whatever current key resolves to
        if (!raw) raw = localStorage.getItem(npFormDraftKey());
      } catch (e) { raw = null; }
      if (!raw) return;

      let data = null;
      try { data = JSON.parse(raw); } catch (e) { data = null; }
      if (!data || typeof data !== 'object') return;

      // Rebuild dynamic UI pieces BEFORE applying field values
      function npRebuildTagWrap(wrapId, inputId, cls, fname, values) {
        try {
          const wrap = document.getElementById(wrapId);
          const inp = document.getElementById(inputId);
          if (!wrap || !inp) return;
          // remove existing tags (keep the input)
          Array.from(wrap.querySelectorAll('.np-tag')).forEach(n => n.remove());
          const arr = Array.isArray(values) ? values : [];
          arr.map(v => (typeof v === 'string' ? v.trim() : String(v ?? '').trim()))
            .filter(v => !!v)
            .forEach(v => {
              const t = document.createElement('span');
              t.className = 'np-tag ' + cls;
              t.innerHTML = `${v} <span class="np-tag-rm" onclick="this.parentElement.remove()">×</span>`;
              const hi = document.createElement('input');
              hi.type = 'hidden';
              hi.name = fname;
              hi.value = v;
              t.appendChild(hi);
              wrap.insertBefore(t, inp);
            });
        } catch (e) { }
      }

      function npEnsureVariantMatrixRows(rowCount) {
        try {
          const body = document.getElementById('npVariantBody');
          if (!body) return;
          const existing = body.querySelectorAll('tr').length;
          const need = Math.max(Number(rowCount) || 0, 1);
          for (let i = existing; i < need; i++) {
            if (typeof npAddVariantRow === 'function') npAddVariantRow();
          }
        } catch (e) { }
      }

      try {
        // Tags
        npRebuildTagWrap('npFlavWrap', 'npFlavInput', 't-green', 'meta_data[flavor_names][]', data['meta_data[flavor_names][]']);
        npRebuildTagWrap('npSizeWrap', 'npSizeInput', 't-purple', 'meta_data[size_variants][]', data['meta_data[size_variants][]']);

        // Variant SKU matrix (ensure enough rows exist)
        const vn = data['meta_data[var_name][]'];
        const vs = data['meta_data[var_sku][]'];
        const vp = data['meta_data[var_price][]'];
        const vq = data['meta_data[var_stock][]'];
        const vt = data['meta_data[var_status][]'];
        const rows = Math.max(
          Array.isArray(vn) ? vn.length : 0,
          Array.isArray(vs) ? vs.length : 0,
          Array.isArray(vp) ? vp.length : 0,
          Array.isArray(vq) ? vq.length : 0,
          Array.isArray(vt) ? vt.length : 0,
          1
        );
        npEnsureVariantMatrixRows(rows);

        // Swatches (selection + hidden inputs)
        const savedSwatches = Array.isArray(data['meta_data[variant_swatches][]']) ? data['meta_data[variant_swatches][]'].map(s => String(s).toLowerCase()) : [];
        if (savedSwatches.length && document.getElementById('npSwatchesWrap')) {
          document.querySelectorAll('#npSwatchesWrap .np-swatch').forEach(sw => {
            const hex = String(sw.getAttribute('data-hex') || '').toLowerCase();
            if (!hex) return;
            sw.classList.toggle('sel', savedSwatches.includes(hex));
          });
          if (typeof npSyncSwatchHiddenInputs === 'function') npSyncSwatchHiddenInputs();
        }
      } catch (e) { }

      // restore item_id first (if any)
      if (data.item_id && !$('#item_id').val()) {
        $('#item_id').val(data.item_id);
      }

      Object.keys(data).forEach(name => {
        if (name === '__active_tab_id') return;
        const els = document.querySelectorAll(`#item_form [name="${name.replace(/"/g, '\\"')}"]`);
        if (!els || !els.length) return;

        const first = els[0];
        const val = data[name];

        // Restore multi-select values (e.g. attribute_id[])
        if (first.tagName === 'SELECT' && first.multiple) {
          npApplyFieldValue(first, val);
          return;
        }

        if (first.type === 'radio') {
          Array.from(els).forEach(r => { r.checked = (r.value == val); });
          $(first).trigger('change');
          return;
        }

        if (first.type === 'checkbox' && name.endsWith('[]')) {
          const arr = Array.isArray(val) ? val.map(String) : [];
          Array.from(els).forEach(c => { c.checked = arr.includes(String(c.value)); });
          $(first).trigger('change');
          return;
        }

        // Restore array text inputs like name[] / selling_point[] in order
        if (name.endsWith('[]') && Array.isArray(val) && first.type !== 'checkbox' && first.type !== 'radio') {
          Array.from(els).forEach((el, idx) => {
            npApplyFieldValue(el, val[idx] ?? '');
          });
          return;
        }

        npApplyFieldValue(first, val);
      });

      // Persist restored category ids for cascade init (selects may not have options yet)
      try {
        const l2 = (data['sub_category_id'] ?? '').toString();
        const l3 = (data['sub_sub_category_id'] ?? '').toString();
        const l4 = (data['sub_sub_sub_category_id'] ?? '').toString();
        if (l2) $('#sub_category_id').attr('data-selected', l2);
        if (l3) $('#sub_sub_category_id').attr('data-selected', l3);
        if (l4) $('#sub_sub_sub_category_id').attr('data-selected', l4);
      } catch (e) { }

      // restore active tab if it exists
      if (data.__active_tab_id) {
        const idx = npVisibleTabIds().indexOf(data.__active_tab_id);
        if (idx >= 0) npOpenTabByIndex(idx);
      }
    }

    function npClearFieldErrors() {
      $('#item_form .np-invalid').removeClass('np-invalid');
      $('#item_form .np-field-error').remove();
      $('#item_form .js-select2-custom').each(function () {
        const $sel = $(this);
        const $container = $sel.next('.select2-container');
        $container.find('.select2-selection').removeClass('np-invalid');
      });
    }

    function npNormalizeErrorCode(code) {
      if (!code) return '';
      return String(code).trim();
    }

    function npFindFieldByCode(code) {
      const c = npNormalizeErrorCode(code);
      if (!c) return null;

      if (c.startsWith('meta_data.')) {
        const key = c.replace(/^meta_data\./, '');
        const name = `meta_data[${key}]`;
        try {
          return document.querySelector(`#item_form [name="${CSS.escape(name)}"]`);
        } catch (e) {
          return document.querySelector(`#item_form [name="${name.replace(/"/g, '\\"')}"]`);
        }
      }

      if (c.startsWith('selling_point.')) {
        const idx = parseInt(c.replace(/^selling_point\./, ''), 10);
        const all = document.querySelectorAll('#item_form [name="selling_point[]"]');
        return Number.isFinite(idx) ? (all[idx] || all[0] || null) : (all[0] || null);
      }

      if (c.startsWith('name.') || c.startsWith('description.')) {
        const parts = c.split('.');
        const base = parts[0];
        const idx = parseInt(parts[1] || '0', 10);
        const all = document.querySelectorAll(`#item_form [name="${base}[]"]`);
        return Number.isFinite(idx) ? (all[idx] || all[0] || null) : (all[0] || null);
      }

      try {
        const direct = document.querySelector(`#item_form [name="${CSS.escape(c)}"]`);
        if (direct) return direct;
      } catch (e) {
        const direct = document.querySelector(`#item_form [name="${c.replace(/"/g, '\\"')}"]`);
        if (direct) return direct;
      }

      return null;
    }

    function npMarkFieldInvalid(el, message) {
      if (!el) return;
      const $el = $(el);

      if ($el.is('select') && ($el.hasClass('js-select2-custom') || $el.data('select2'))) {
        const $container = $el.next('.select2-container');
        $container.find('.select2-selection').addClass('np-invalid');
      } else {
        $el.addClass('np-invalid');
      }

      const $group = $el.closest('.np-form-group');
      if ($group.length && $group.find('.np-field-error').length) return;

      const $msg = $('<div class="np-field-error"></div>').text(message || 'This field is required.');
      if ($group.length) $group.append($msg);
      else $el.after($msg);
    }

    function npShowFieldErrors(errors) {
      npClearFieldErrors();
      if (!Array.isArray(errors)) return;

      errors.forEach(function (e) {
        const code = npNormalizeErrorCode(e?.code);
        const msg = e?.message || 'Invalid value';
        const el = npFindFieldByCode(code);
        if (el) npMarkFieldInvalid(el, msg);
      });

      const first = document.querySelector('#item_form .np-invalid, #item_form .select2-selection.np-invalid');
      if (first) {
        if (first.classList.contains('select2-selection')) {
          const $sel = $(first).closest('.select2-container').prev('select');
          if ($sel.length) { try { $sel.select2('open'); } catch (e) { } }
        }
        try { first.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) { }
        try { first.focus({ preventScroll: true }); } catch (e) { }
      }
    }

    function npPersistWizardStep({ publish = false, moveNext = false } = {}) {
      const form = document.getElementById('item_form');
      if (!form) return;

      npClearFieldErrors();
      // Ensure Logistics dynamic controls are represented as real inputs (FormData)
      try {
        if (typeof npAddCustomIncoterm === 'function') { /* noop - only to ensure function exists */ }
      } catch (e) { }
      const activeTabIdAtStart = document.querySelector('.np-tab-panel.active')?.id || 'np-tab-general';
      const startTabIndex = Math.max(npTabIds.indexOf(activeTabIdAtStart), 0);
      const nextTabIdAtStart = moveNext ? (npTabIds[startTabIndex + 1] || '') : '';
      window.__npWizardSaving = true;
      try { document.querySelector('.np-btn-row button[onclick="npNextTab()"]')?.setAttribute('disabled', 'disabled'); } catch (e) { }

      // Ensure custom tabs JSON is up-to-date before submit
      if (typeof npSaveCustomTabsToInput === 'function') {
        npSaveCustomTabsToInput();
      }
      if (typeof npSaveTabsUi === 'function') {
        npSaveTabsUi();
      }

      $('#draft_mode').val(publish ? '0' : '1');

      if (publish) {
        let $form = $('#item_form');
        // Don't let jQuery Validate block publish due to hidden/other-tab required fields.
        // Server-side validation + field highlighting will handle any missing required inputs.

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
          window.__npWizardSaving = false;
          try { document.querySelector('.np-btn-row button[onclick="npNextTab()"]')?.removeAttribute('disabled'); } catch (e) { }
          if (data.errors) {
            npShowFieldErrors(data.errors);
            for (let i = 0; i < data.errors.length; i++) {
              toastr.error(data.errors[i].message, { CloseButton: true, ProgressBar: true });
            }
            return;
          }

          if (data.item_id) {
            const wasNew = !String(currentItemId || '').trim();
            $('#item_id').val(data.item_id);
            // Switch to edit URL so refresh loads from DB (not localStorage-only draft)
            if (wasNew) {
              try {
                const base = "{{ route('admin.item.edit', [0]) }}";
                const editUrl = base.replace(/\/0$/, '/' + data.item_id);
                window.history.replaceState({}, '', editUrl);
              } catch (e) { }
            }
          }

          document.getElementById('npLastSaved').textContent = new Date().toLocaleTimeString();

          if (publish) {
            toastr.success(data.success || "{{ translate('messages.product_added_successfully') }}", { CloseButton: true, ProgressBar: true });
            try { localStorage.removeItem('np_custom_tabs_draft'); } catch (e) { }
            try { localStorage.removeItem('np_tabs_ui_draft'); } catch (e) { }
            try { localStorage.removeItem(npFormDraftKey()); } catch (e) { }
            try { localStorage.removeItem(`${npFormDraftKeyBase}:new`); } catch (e) { }
            try { localStorage.removeItem(npFormDraftLastKey); } catch (e) { }
            setTimeout(function () {
              location.href = "{{ route('admin.item.list') }}";
            }, 1000);
            return;
          }

          toastr.success('Draft saved', { CloseButton: true, ProgressBar: true });
          try { npSaveFormDraft(); } catch (e) { }
          if (moveNext) {
            setTimeout(function () {
              if (nextTabIdAtStart) {
                // Force-activate by id (no index recompute)
                document.querySelectorAll('.np-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.np-tab-panel').forEach(p => p.classList.remove('active'));
                const panel = document.getElementById(nextTabIdAtStart);
                if (panel) panel.classList.add('active');
                const btn = document.querySelector(`.np-tab-btn[data-tab="${nextTabIdAtStart}"]`);
                if (btn) btn.classList.add('active');
                npSetActionButtons();
              } else {
                npOpenTabByIndex(startTabIndex + 1);
              }
            }, 0);
          }
        },
        error: function (xhr) {
          $('#loading').hide();
          $('#submitButton').attr('disabled', false);
          window.__npWizardSaving = false;
          try { document.querySelector('.np-btn-row button[onclick="npNextTab()"]')?.removeAttribute('disabled'); } catch (e) { }
          if (xhr?.responseJSON?.errors) {
            npShowFieldErrors(xhr.responseJSON.errors);
          }
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
      // Restore saved draft (add-new only) before select2 init
      try { npLoadFormDraft(); } catch (e) { }

      // If we already have an item_id but are still on add-new URL, jump to edit so DB-prefill works (Media needs this)
      try {
        const id = ($('#item_id').val() || '').toString().trim();
        const isAddNew = /\/admin\/item\/add-new$/.test(window.location.pathname || '');
        if (id && isAddNew) {
          const base = "{{ route('admin.item.edit', [0]) }}";
          const editUrl = base.replace(/\/0$/, '/' + id);
          window.location.replace(editUrl);
          return;
        }
      } catch (e) { }
      $('.js-select2-custom').each(function () { if ($.HSCore && $.HSCore.components && $.HSCore.components.HSSelect2) $.HSCore.components.HSSelect2.init($(this)); });

      // Prefill Media tab previews for edit mode (existing images)
      try {
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        if (isEdit) {
          const existing = {!! json_encode(is_array($product?->images) ? $product->images : []) !!};
          const existingUrls = {!! json_encode(is_array($product?->images_full_url) ? $product->images_full_url : []) !!};
          const alts = {!! json_encode(is_array(old('alt_text', data_get($npMeta,'alt_text', []))) ? old('alt_text', data_get($npMeta,'alt_text', [])) : []) !!};
          if (Array.isArray(existing) && existing.length) {
            npImgs = existing.map((v, idx) => {
              const img = (v && typeof v === 'object') ? (v.img || '') : (v || '');
              const url = (Array.isArray(existingUrls) && existingUrls[idx]) ? String(existingUrls[idx]) : ("{{ \App\CentralLogics\Helpers::get_full_url('product', '___IMG___', 'public') }}".replace('___IMG___', img));
              const alt = (Array.isArray(alts) && typeof alts[idx] === 'string' && alts[idx].trim()) ? alts[idx].trim() : (img ? String(img).replace(/\.[^.]+$/, '') : '');
              return { src: url, alt: alt, key: img };
            }).filter(x => !!x.key);
            npRenderImgs();
            npRenderAlt();
          }
        }
      } catch (e) { }

      // Prefill item type select from hidden meta_data[item_type_id]
      try {
        const savedTypeId = (document.getElementById('npItemTypeId')?.value || '').toString().trim();
        if (savedTypeId) {
          $('#npItemTypeSelect').val(savedTypeId).trigger('change.select2').trigger('change');
        }
      } catch (e) { }

      // Ensure delivery cards reflect saved checkbox values after any restore
      try { npSyncDeliveryCardsFromInputs(); } catch (e) { }
      try { npSyncStatusPillsFromInputs(); } catch (e) { }
      try {
        // Ensure hidden inputs exist for already-selected swatches
        npSyncSwatchHiddenInputs();
      } catch (e) { }

      // Bundle / Multipack toggle
      try {
        const $b = $('#npBundleToggle');
        const $f = $('#npBundleFields');
        function syncBundleUI() { $f.toggle(!!$b.prop('checked')); }
        $b.off('change.npBundle').on('change.npBundle', syncBundleUI);
        syncBundleUI();
      } catch (e) { }

      // If user typed tag but didn't press Enter, persist it
      try {
        $('#npFlavInput').on('blur', function () {
          const v = String(this.value || '').trim();
          if (!v) return;
          npAddTag({ key: 'Enter', preventDefault: function () { } }, 'npFlavWrap', 'npFlavInput', 't-green', 'meta_data[flavor_names][]');
        });
        $('#npSizeInput').on('blur', function () {
          const v = String(this.value || '').trim();
          if (!v) return;
          npAddTag({ key: 'Enter', preventDefault: function () { } }, 'npSizeWrap', 'npSizeInput', 't-purple', 'meta_data[size_variants][]');
        });
      } catch (e) { }

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

      function npInitCategoryCascade(mainId, l2Id, l3Id, l4Id) {
        if (!mainId) return;
        // Main -> L2
        $('#category_id').val(String(mainId)).trigger('change.select2').trigger('change');
        npFetchChildCategories(mainId).done(function (data) {
          let opts = '<option value="">Select sub-category…</option>';
          $.each(data || [], function (i, v) { opts += `<option value="${v.id}">${v.text}</option>`; });
          npReplaceCategorySelectOptions($('#sub_category_id'), opts);
          if (l2Id) $('#sub_category_id').val(String(l2Id)).trigger('change.select2').trigger('change');

          if (!l2Id) return;
          // L2 -> L3
          npFetchChildCategories(l2Id).done(function (data2) {
            let opts2 = '<option value="">Select sub-sub-category…</option>';
            $.each(data2 || [], function (i, v) { opts2 += `<option value="${v.id}">${v.text}</option>`; });
            npReplaceCategorySelectOptions($('#sub_sub_category_id'), opts2);
            if (l3Id) $('#sub_sub_category_id').val(String(l3Id)).trigger('change.select2').trigger('change');

            if (!l3Id) return;
            // L3 -> L4
            npFetchChildCategories(l3Id).done(function (data3) {
              let opts3 = '<option value="">Select level 4…</option>';
              $.each(data3 || [], function (i, v) { opts3 += `<option value="${v.id}">${v.text}</option>`; });
              npReplaceCategorySelectOptions($('#sub_sub_sub_category_id'), opts3);
              if (l4Id) $('#sub_sub_sub_category_id').val(String(l4Id)).trigger('change.select2').trigger('change');
            });
          });
        });
      }

      // Prefill cascade (edit + restored draft)
      const npInitMain = ($('#category_id').val() || '').toString();
      const npInitL2 = ($('#sub_category_id').attr('data-selected') || '').toString();
      const npInitL3 = ($('#sub_sub_category_id').attr('data-selected') || '').toString();
      const npInitL4 = ($('#sub_sub_sub_category_id').attr('data-selected') || '').toString();
      if (npInitMain) npInitCategoryCascade(npInitMain, npInitL2, npInitL3, npInitL4);
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
        const isColor = String(type || '') === 'variant_color';
        try {
          const wrap = document.getElementById('npProductSelectOptionValueWrap');
          if (wrap) wrap.style.display = isColor ? '' : 'none';
          if (isColor) {
            const picker = document.getElementById('npProductSelectOptionColorPicker');
            const valueInp = document.getElementById('npProductSelectOptionValue');
            if (picker && valueInp) {
              const start = valueInp.value || picker.value || '#2563eb';
              picker.value = start;
              valueInp.value = start;
            }
          }
        } catch (e) { }
        const titleMap = {
          origin_country: 'Add Country of Origin',
          seller: 'Add Seller',
          country_of_manufacture: 'Add Country of Manufacture',
          packaging_type: 'Add Packaging Type',
          recyclable: 'Add Recyclable Packaging Option',
          storage_type: 'Add Storage Type',
          condition: 'Add Condition',
          age_restriction: 'Add Age Restriction',
          warranty: 'Add Warranty Period',
          return_policy: 'Add Return Policy',
          product_type: 'Add Product Type',
          variant_color: 'Add Colour',
          variant_type: 'Add Variant Type',
          schema_type: 'Add Schema Type',
          dangerous_goods: 'Add Dangerous Goods Option',
          air_restriction: 'Add Air Restriction Option',
          temp_controlled: 'Add Temperature Shipping Option',
        };
        document.getElementById('npProductSelectOptionModalLabel').textContent = titleMap[type] || 'Add Option';
        modal.modal('show');
      }
      window.npOpenProductSelectOptionModal = npOpenProductSelectOptionModal;

      function npOpenAttributeQuickAdd() {
        const modal = $('#npAttributeModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        const form = document.getElementById('npAttributeForm');
        if (form) form.reset();
        modal.modal('show');
        try { setTimeout(() => document.getElementById('npAttributeName')?.focus(), 150); } catch (e) { }
      }
      window.npOpenAttributeQuickAdd = npOpenAttributeQuickAdd;

      // Keep colour picker and hex input in sync
      try {
        $('#npProductSelectOptionColorPicker').on('input', function () {
          $('#npProductSelectOptionValue').val(String(this.value || '').trim());
        });
        $('#npProductSelectOptionValue').on('input', function () {
          const v = String(this.value || '').trim();
          if (/^#(?:[0-9a-fA-F]{3}){1,2}$/.test(v)) $('#npProductSelectOptionColorPicker').val(v);
        });
      } catch (e) { }

      $('#npAttributeForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        if (typeof form.reportValidity === 'function' && !form.reportValidity()) return;
        $.ajax({
          url: "{{ route('admin.attribute.ajax-store') }}",
          method: "POST",
          dataType: "json",
          data: $(form).serialize(),
          success: function (res) {
            if (!res || !res.id || !res.name) {
              toastr.error('Failed to add attribute', { CloseButton: true, ProgressBar: true });
              return;
            }

            const $sel = $('#choice_attributes');
            if ($sel.length) {
              const opt = new Option(res.name, String(res.id), true, true);
              $sel.append(opt);
              if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
              else $sel.trigger('change');
            }

            toastr.success(res.message || 'Attribute added successfully', { CloseButton: true, ProgressBar: true });
            $('#npAttributeModal').modal('hide');
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
            toastr.error(data.message || 'Failed to add attribute', { CloseButton: true, ProgressBar: true });
          }
        });
      });

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
              country_of_manufacture: '#npCountryOfManufacture',
              packaging_type: '#npPackagingType',
              recyclable: '#npRecyclable',
              storage_type: '#npStorageType',
              condition: '#npCondition',
              age_restriction: '#npAgeRestriction',
              warranty: '#npWarranty',
              return_policy: '#npReturnPolicy',
              product_type: '#npProductType',
              variant_type: '#npVariantType',
              schema_type: '#npSchemaType',
              dangerous_goods: '#npDangerousGoods',
              air_restriction: '#npAirRestriction',
              temp_controlled: '#npTempControlled',
            };
            const sel = map[res.type];
            const $sel = sel ? $(sel) : $();
            if ($sel.length) {
              $sel.append(new Option(res.name, res.name, true, true));
              $sel.val(String(res.name));
              if ($sel.hasClass('select2-hidden-accessible')) $sel.trigger('change.select2');
              else $sel.trigger('change');
            }

            // Variant colour palette: add new swatch and select it
            if (res.type === 'variant_color' && res.value) {
              const hex = String(res.value).trim();
              if (hex && document.getElementById('npSwatchesWrap')) {
                const wrap = document.getElementById('npSwatchesWrap');
                const sw = document.createElement('div');
                sw.className = 'np-swatch sel';
                sw.setAttribute('data-hex', hex);
                sw.setAttribute('title', res.name || hex);
                sw.setAttribute('style', `background:${hex}${hex.toLowerCase() === '#f9fafb' ? ';border:1.5px solid #d1d5db' : ''}`);
                sw.onclick = function () { npToggleSwatch(this); };
                wrap.appendChild(sw);
                // Ensure hidden input exists (don't toggle selection off)
                try { npSyncSwatchHiddenInputs(); } catch (e) { }
              }
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
      const npTabsUiDraftKey = 'np_tabs_ui_draft';
      let npTabsUi = { built_in: {}, custom: {} };

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

      function npLoadTabsUi() {
        const raw = document.getElementById('custom_tabs_ui_json')?.value || '';
        if (raw) {
          try { return JSON.parse(raw) || { built_in: {}, custom: {} }; } catch { /* fallthrough */ }
        }
        const currentItemId = document.getElementById('item_id')?.value || '';
        if (!currentItemId) {
          try {
            const draft = localStorage.getItem(npTabsUiDraftKey);
            return draft ? (JSON.parse(draft) || { built_in: {}, custom: {} }) : { built_in: {}, custom: {} };
          } catch { return { built_in: {}, custom: {} }; }
        }
        return { built_in: {}, custom: {} };
      }
      function npSaveTabsUi() {
        const el = document.getElementById('custom_tabs_ui_json');
        if (el) el.value = JSON.stringify(npTabsUi || { built_in: {}, custom: {} });
        const currentItemId = document.getElementById('item_id')?.value || '';
        if (!currentItemId) {
          try { localStorage.setItem(npTabsUiDraftKey, el?.value || '{}'); } catch { /* ignore */ }
        }
      }

      function npApplyTabsUi() {
        // Built-in tab buttons: match by onclick panel id string
        const map = {
          'np-tab-general': { defaultTitle: '📝 General' },
          'np-tab-attributes': { defaultTitle: '🔧 Attributes' },
          'np-tab-nutrition': { defaultTitle: '🧪 Nutrition & Allergens' },
          'np-tab-variants': { defaultTitle: '🎨 Variants' },
          'np-tab-seo': { defaultTitle: '🔍 SEO & Meta' },
          'np-tab-media': { defaultTitle: '🖼️ Media' },
          'np-tab-logistics': { defaultTitle: '✈️ Logistics & Shipping' },
          'np-tab-reviews': { defaultTitle: '⭐ Reviews & Analytics' },
        };
        document.querySelectorAll('.np-tab-nav .np-tab-btn').forEach(btn => {
          const on = btn.getAttribute('onclick') || '';
          const m = on.match(/npSwitchTab\(this,'([^']+)'\)/);
          if (!m) return;
          const panelId = m[1];
          const ov = npTabsUi?.built_in?.[panelId];
          if (ov?.hidden) {
            btn.style.display = 'none';
          } else {
            btn.style.display = '';
          }
          if (ov?.title) {
            btn.textContent = ov.title;
          } else if (map[panelId]?.defaultTitle) {
            btn.textContent = map[panelId].defaultTitle;
          }
        });
        // Built-in panels: keep hidden tabs out of the layout and inactive (matches Manage Tabs → Visible)
        Object.keys(map).forEach(panelId => {
          const panel = document.getElementById(panelId);
          if (!panel) return;
          const hide = !!npTabsUi?.built_in?.[panelId]?.hidden;
          if (hide) {
            panel.classList.remove('active');
            panel.setAttribute('hidden', 'hidden');
            panel.setAttribute('aria-hidden', 'true');
          } else {
            panel.removeAttribute('hidden');
            panel.setAttribute('aria-hidden', 'false');
          }
        });
        const active = document.querySelector('.np-tab-panel.active');
        const aid = active?.id;
        if (!aid || (map[aid] && npTabsUi?.built_in?.[aid]?.hidden)) {
          const firstId = Object.keys(map).find(id => !npTabsUi?.built_in?.[id]?.hidden);
          if (firstId) {
            document.querySelectorAll('.np-tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.np-tab-nav .np-tab-btn').forEach(b => b.classList.remove('active'));
            const p = document.getElementById(firstId);
            const b = document.querySelector(`.np-tab-btn[data-tab="${firstId}"]`);
            if (p) p.classList.add('active');
            if (b) b.classList.add('active');
          }
        }
        if (typeof npSetActionButtons === 'function') npSetActionButtons();
        // Custom tabs are rendered by npRenderCustomTabs() using tab.title/icon
      }

      let npManageSectionsContext = { tabKey: null };
      let npBuiltInSectionsRendered = false;

      function npSlugify(s) {
        return String(s || '')
          .toLowerCase()
          .replace(/&amp;/g, 'and')
          .replace(/[^a-z0-9]+/g, '_')
          .replace(/^_+|_+$/g, '')
          .slice(0, 60) || 'section';
      }

      function npEnsureSectionKeys() {
        // Assign stable data-np-section keys to every card under each built-in tab panel
        document.querySelectorAll('.np-tab-panel[id^="np-tab-"]').forEach(panel => {
          const tabId = panel.id;
          panel.querySelectorAll('.np-card').forEach((card, idx) => {
            if (card.getAttribute('data-np-section')) return;
            const titleEl = card.querySelector('.np-card-title');
            const title = titleEl ? titleEl.textContent.trim() : `card_${idx + 1}`;
            const key = `${tabId}.${npSlugify(title)}_${idx + 1}`;
            card.setAttribute('data-np-section', key);
          });
        });
      }

      function npApplySectionsUi() {
        const sectionsHidden = npTabsUi?.sections_hidden || {};
        document.querySelectorAll('[data-np-section]').forEach(el => {
          const key = el.getAttribute('data-np-section');
          const hidden = !!sectionsHidden[key];
          el.style.display = hidden ? 'none' : '';
        });
      }

      function npGetBuiltInAddedSections() {
        npTabsUi.added_sections = npTabsUi.added_sections || [];
        return npTabsUi.added_sections;
      }

      function npRenderBuiltInAddedSections() {
        if (npBuiltInSectionsRendered) return;
        npBuiltInSectionsRendered = true;
        npEnsureSectionKeys();
        const added = npGetBuiltInAddedSections();
        added.forEach(sec => {
          const panel = document.getElementById(sec.tabId);
          if (!panel) return;
          const col = panel.querySelector('.np-grid > div:first-child') || panel;
          const existing = col.querySelector(`[data-np-added-section-id="${sec.id}"]`);
          if (existing) return;

          const card = document.createElement('div');
          card.className = 'np-card';
          card.setAttribute('data-np-added-section-id', sec.id);
          card.setAttribute('data-np-section', `${sec.tabId}.custom_${sec.id}`);
          const fields = (sec.fields || []);

          const fieldsHtml = fields.map(f => {
            const safeLabel = String(f.label || '').replace(/</g, '&lt;');
            let input = '';
            if (f.type === 'textarea') {
              input = `<textarea class="np-textarea" rows="3" oninput="npSetBuiltInFieldValue('${sec.id}','${f.id}', this.value)">${String(f.value ?? '').replace(/</g,'&lt;')}</textarea>`;
            } else if (f.type === 'number') {
              input = `<input type="number" class="np-input" value="${String(f.value ?? '').replace(/"/g,'&quot;')}" oninput="npSetBuiltInFieldValue('${sec.id}','${f.id}', this.value)">`;
            } else if (f.type === 'checkbox') {
              const checked = f.value ? 'checked' : '';
              input = `<label class="np-tog"><input type="checkbox" ${checked} onchange="npSetBuiltInFieldValue('${sec.id}','${f.id}', this.checked)"><span class="np-tog-track"></span></label>`;
            } else if (f.type === 'select') {
              const opts = (f.options || []).map(o => {
                const sel = (String(o) === String(f.value)) ? 'selected' : '';
                return `<option ${sel} value="${String(o).replace(/"/g,'&quot;')}">${String(o).replace(/</g,'&lt;')}</option>`;
              }).join('');
              input = `<select class="np-select" onchange="npSetBuiltInFieldValue('${sec.id}','${f.id}', this.value)">${opts}</select>`;
            } else {
              input = `<input type="text" class="np-input" value="${String(f.value ?? '').replace(/"/g,'&quot;')}" oninput="npSetBuiltInFieldValue('${sec.id}','${f.id}', this.value)">`;
            }

            return `
              <div class="np-form-group">
                <div style="display:flex;justify-content:space-between;gap:10px;align-items:center">
                  <label class="np-label" style="margin:0">${safeLabel}</label>
                  <button type="button" class="np-btn-tiny del" onclick="npDeleteBuiltInField('${sec.id}','${f.id}')">Delete</button>
                </div>
                ${input}
              </div>
            `;
          }).join('');

          card.innerHTML = `
            <div class="np-card-header">
              <span class="np-card-icon">${sec.icon || '🧩'}</span>
              <span class="np-card-title">${String(sec.title || 'Custom Section').replace(/</g,'&lt;')}</span>
              <span class="np-card-subtitle">Added section</span>
            </div>
            <div class="np-card-body">
              <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap">
                <button type="button" class="np-btn-tiny" onclick="npAddBuiltInField('${sec.id}')">+ Add Field</button>
                <button type="button" class="np-btn-tiny del" onclick="npDeleteBuiltInSection('${sec.id}')">Delete Section</button>
              </div>
              ${fieldsHtml || '<div class="np-hint">No fields yet. Click “+ Add Field”.</div>'}
            </div>
          `;

          col.appendChild(card);
        });

        npApplySectionsUi();
      }
      window.npRenderBuiltInAddedSections = npRenderBuiltInAddedSections;

      function npAddBuiltInSection(tabId) {
        const title = (prompt('Section title', 'New Section') || '').trim();
        if (!title) return;
        const icon = (prompt('Section icon (emoji)', '🧩') || '').trim() || '🧩';
        npTabsUi = npLoadTabsUi();
        const added = npGetBuiltInAddedSections();
        const sec = { id: npUid('bsec'), tabId, title, icon, fields: [] };
        added.push(sec);
        npSaveTabsUi();
        npBuiltInSectionsRendered = false;
        npRenderBuiltInAddedSections();
      }
      window.npAddBuiltInSection = npAddBuiltInSection;

      function npAddBuiltInField(sectionId) {
        npTabsUi = npLoadTabsUi();
        const sec = (npGetBuiltInAddedSections() || []).find(s => s.id === sectionId);
        if (!sec) return;
        const label = (prompt('Field label', 'New Field') || '').trim();
        if (!label) return;
        const type = (prompt('Field type: text / textarea / number / checkbox / select', 'text') || 'text').trim().toLowerCase();
        const allowed = ['text', 'textarea', 'number', 'checkbox', 'select'];
        if (!allowed.includes(type)) return;
        const field = { id: npUid('bfld'), label, type, value: (type === 'checkbox' ? false : '') };
        if (type === 'select') {
          const opts = (prompt('Select options (comma separated)', 'Option A, Option B') || '').split(',').map(s => s.trim()).filter(Boolean);
          field.options = opts;
          field.value = opts[0] || '';
        }
        sec.fields = sec.fields || [];
        sec.fields.push(field);
        npSaveTabsUi();
        npBuiltInSectionsRendered = false;
        npRenderBuiltInAddedSections();
      }
      window.npAddBuiltInField = npAddBuiltInField;

      function npSetBuiltInFieldValue(sectionId, fieldId, value) {
        npTabsUi = npLoadTabsUi();
        const sec = (npGetBuiltInAddedSections() || []).find(s => s.id === sectionId);
        const fld = (sec?.fields || []).find(f => f.id === fieldId);
        if (!fld) return;
        fld.value = value;
        npSaveTabsUi();
      }
      window.npSetBuiltInFieldValue = npSetBuiltInFieldValue;

      function npDeleteBuiltInField(sectionId, fieldId) {
        npTabsUi = npLoadTabsUi();
        const sec = (npGetBuiltInAddedSections() || []).find(s => s.id === sectionId);
        if (!sec) return;
        if (!confirm('Delete this field?')) return;
        sec.fields = (sec.fields || []).filter(f => f.id !== fieldId);
        npSaveTabsUi();
        npBuiltInSectionsRendered = false;
        npRenderBuiltInAddedSections();
      }
      window.npDeleteBuiltInField = npDeleteBuiltInField;

      function npDeleteBuiltInSection(sectionId) {
        npTabsUi = npLoadTabsUi();
        if (!confirm('Delete this section?')) return;
        npTabsUi.added_sections = (npTabsUi.added_sections || []).filter(s => s.id !== sectionId);
        npSaveTabsUi();
        // Remove from DOM if present
        document.querySelectorAll(`[data-np-added-section-id="${sectionId}"]`).forEach(el => el.remove());
      }
      window.npDeleteBuiltInSection = npDeleteBuiltInSection;

      function npOpenManageSectionsModal(tabId) {
        const modal = $('#npManageSectionsModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        npTabsUi = npLoadTabsUi();
        npManageSectionsContext.tabKey = tabId;

        npEnsureSectionKeys();

        const panel = document.getElementById(tabId);
        const defs = panel
          ? Array.from(panel.querySelectorAll('.np-card[data-np-section]')).map(card => {
            const key = card.getAttribute('data-np-section');
            const titleEl = card.querySelector('.np-card-title');
            const label = titleEl ? titleEl.textContent.trim() : key;
            return { key, label };
          })
          : [];
        const hidden = npTabsUi.sections_hidden || {};
        const body = document.getElementById('npManageSectionsBody');
        if (body) {
          body.innerHTML = `
            <div style="display:flex;gap:8px;justify-content:flex-end;margin-bottom:10px">
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="npAddBuiltInSection('${tabId}')">+ Add Section</button>
            </div>
          ` + defs.map(d => {
            const checked = hidden[d.key] ? '' : 'checked';
            return `
              <div class="d-flex align-items-center justify-content-between" style="padding:10px 0;border-bottom:1px solid #eef2f7">
                <div><strong>${d.label}</strong></div>
                <label class="m-0 d-flex align-items-center" style="gap:10px">
                  <span class="text-muted">Visible</span>
                  <input type="checkbox" data-np-section-visible="${d.key}" ${checked}>
                </label>
              </div>
            `;
          }).join('');
        }

        modal.modal('show');
      }
      window.npOpenManageSectionsModal = npOpenManageSectionsModal;

      function npSaveManageSectionsAndClose() {
        npTabsUi.sections_hidden = npTabsUi.sections_hidden || {};
        document.querySelectorAll('[data-np-section-visible]').forEach(chk => {
          const key = chk.getAttribute('data-np-section-visible');
          npTabsUi.sections_hidden[key] = !chk.checked;
        });
        npSaveTabsUi();
        npApplySectionsUi();
        $('#npManageSectionsModal').modal('hide');
      }
      window.npSaveManageSectionsAndClose = npSaveManageSectionsAndClose;

      function npOpenManageTabsModal() {
        const modal = $('#npManageTabsModal');
        if (!modal.length) return;
        if (!modal.parent().is('body')) modal.appendTo('body');
        npTabsUi = npLoadTabsUi();
        npRenderManageTabsTable();
        modal.modal('show');
      }
      window.npOpenManageTabsModal = npOpenManageTabsModal;

      function npRenderManageTabsTable() {
        const tbody = document.getElementById('npManageTabsTbody');
        if (!tbody) return;
        const builtIns = [
          { id: 'np-tab-general', label: 'General' },
          { id: 'np-tab-attributes', label: 'Attributes' },
          { id: 'np-tab-nutrition', label: 'Nutrition' },
          { id: 'np-tab-variants', label: 'Variants' },
          { id: 'np-tab-seo', label: 'SEO' },
          { id: 'np-tab-media', label: 'Media' },
          { id: 'np-tab-logistics', label: 'Logistics' },
          { id: 'np-tab-reviews', label: 'Reviews' },
        ];
        const rows = [];
        builtIns.forEach(t => {
          const ov = npTabsUi.built_in?.[t.id] || {};
          const title = ov.title || '';
          const visible = !ov.hidden;
          rows.push(`
            <tr>
              <td><strong>${t.label}</strong> <span class="text-muted">(built-in)</span></td>
              <td><input type="text" class="form-control" data-np-tab-title="${t.id}" value="${title.replace(/"/g,'&quot;')}" placeholder="Leave empty to keep default"></td>
              <td class="text-center"><input type="checkbox" data-np-tab-visible="${t.id}" title="Show this tab in the product form" ${visible ? 'checked' : ''}></td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="npOpenManageSectionsModal('${t.id}')">Sections</button>
              </td>
            </tr>
          `);
        });
        (npCustomTabs || []).forEach(tab => {
          rows.push(`
            <tr>
              <td><strong>${String(tab.title || 'Custom')}</strong> <span class="text-muted">(custom)</span></td>
              <td><input type="text" class="form-control" data-np-custom-tab-title="${tab.id}" value="${String(tab.title || '').replace(/"/g,'&quot;')}"></td>
              <td class="text-center"><input type="checkbox" data-np-custom-tab-visible="${tab.id}" checked disabled></td>
              <td>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="npDeleteCustomTab('${tab.id}')">Delete</button>
              </td>
            </tr>
          `);
        });
        tbody.innerHTML = rows.join('');
      }

      function npDeleteCustomTab(tabId) {
        if (!confirm('Delete this custom tab?')) return;
        npCustomTabs = (npCustomTabs || []).filter(t => t.id !== tabId);
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
        npRenderManageTabsTable();
      }
      window.npDeleteCustomTab = npDeleteCustomTab;

      function npSaveManageTabsAndClose() {
        // collect built-in overrides
        const built = {};
        document.querySelectorAll('[data-np-tab-title]').forEach(inp => {
          const id = inp.getAttribute('data-np-tab-title');
          const title = (inp.value || '').trim();
          built[id] = built[id] || {};
          if (title) built[id].title = title;
        });
        document.querySelectorAll('[data-np-tab-visible]').forEach(chk => {
          const id = chk.getAttribute('data-np-tab-visible');
          built[id] = built[id] || {};
          built[id].hidden = !chk.checked;
        });
        // collect custom tab titles
        document.querySelectorAll('[data-np-custom-tab-title]').forEach(inp => {
          const id = inp.getAttribute('data-np-custom-tab-title');
          const t = (inp.value || '').trim();
          const tab = npFindTab(id);
          if (tab && t) tab.title = t;
        });
        npTabsUi.built_in = built;
        npSaveTabsUi();
        npSaveCustomTabsToInput();
        npRenderCustomTabs();
        npApplyTabsUi();
        $('#npManageTabsModal').modal('hide');
      }
      window.npSaveManageTabsAndClose = npSaveManageTabsAndClose;

      // init UI overrides
      npTabsUi = npLoadTabsUi();
      npEnsureSectionKeys();
      npRenderBuiltInAddedSections();
      npApplyTabsUi();
      npApplySectionsUi();

    </script>
@endpush
