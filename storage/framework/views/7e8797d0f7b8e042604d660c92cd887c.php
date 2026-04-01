<div class="tab-panel active" id="tab-general">
  <div class="grid">
    <div>

      <div class="card">
        <div class="card-header"><span class="card-icon">📝</span><span class="card-title">Basic Information</span></div>
        <div class="card-body">
          <div class="form-group">
            <label>Product Name (English) <span class="req">*</span></label>
            <input type="text" id="productName" placeholder="e.g. Kiri Spreadable Cream Cheese Squares 48 Portions 864g" oninput="updateQuality();updateSEO()">
          </div>
          <div class="form-group">
            <label>Product Name (Arabic) <span class="opt">(optional)</span></label>
            <input type="text" placeholder="اسم المنتج بالعربي" style="direction:rtl;text-align:right">
          </div>
          <div class="form-group">
            <label>Short Description <span class="req">*</span></label>
            <textarea id="shortDesc" rows="3" placeholder="A brief description shown on listing cards…" oninput="updateDescCount();updateQuality();updateSEO()"></textarea>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px">
              <span class="hint">Shown on product listing cards</span>
              <span id="descCount" class="cc cc-ok">0/160</span>
            </div>
          </div>
          <div class="form-group">
            <label>Full Description</label>
            <textarea rows="5" placeholder="Detailed product description including features, usage, storage instructions…"></textarea>
          </div>
          <div class="form-row">
            <div class="form-group" style="margin-bottom:0">
              <label>Brand <span class="req">*</span></label>
              <select id="brand" onchange="updateQuality()">
                <option value="">Select brand…</option>
                <option>BEL: Kiri</option><option>Président</option><option>Philadelphia</option>
                <option>Lurpak</option><option>Arla</option><option>Almarai</option><option>Other</option>
              </select>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Pack / Weight <span class="req">*</span></label>
              <input type="text" id="weight" placeholder="e.g. 864g, 2×24 portions" oninput="updateQuality()">
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-icon">💰</span><span class="card-title">Pricing</span></div>
        <div class="card-body">
          <div class="form-row-3">
            <div class="form-group" style="margin-bottom:0">
              <label>Regular Price <span class="req">*</span></label>
              <div class="iw"><span class="ipfx">QAR</span><input type="number" id="regPrice" placeholder="0.00" step="0.01" oninput="updateDiscount();updateQuality()"></div>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Sale Price</label>
              <div class="iw"><span class="ipfx">QAR</span><input type="number" id="salePrice" placeholder="0.00" step="0.01" oninput="updateDiscount()"></div>
            </div>
            <div class="form-group" style="margin-bottom:0">
              <label>Discount %</label>
              <input type="text" id="discountPct" readonly placeholder="—" class="mono" style="background:var(--bg);color:var(--red);font-weight:700">
            </div>
          </div>
          <div class="divider"></div>
          <div class="form-row">
            <div class="form-group" style="margin-bottom:0"><label>Promo Start Date</label><input type="date"></div>
            <div class="form-group" style="margin-bottom:0"><label>Promo End Date</label><input type="date"></div>
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
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. Made from 100% natural pasteurised cow's milk — no artificial preservatives or colours"><div class="hint">Lead with the strongest keyword. Focus on the benefit.</div></div></div>
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. 48 individually wrapped cream cheese portions — perfect for lunchboxes, snacks &amp; entertaining"></div></div>
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. Halal certified — suitable for the whole family, including children"></div></div>
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. Keep refrigerated 2–8 °C; once opened consume within 3 days"></div></div>
            <div style="display:flex;gap:8px;align-items:flex-start;margin-bottom:10px"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. Rich &amp; creamy texture — ideal for spreading on bread, toast, crackers or using in recipes"></div></div>
            <div style="display:flex;gap:8px;align-items:flex-start"><span style="margin-top:10px;font-size:14px;flex-shrink:0">✅</span><div style="flex:1"><input type="text" placeholder="e.g. Country of Origin: Poland · Brand: BEL Group · Net Weight: 864g (2 × 432g)"></div></div>
          </div>
          <button class="btn-add" style="margin-top:10px" onclick="(function(){var r=document.createElement('div');r.style.cssText='display:flex;gap:8px;align-items:flex-start;margin-top:10px';r.innerHTML='<span style=\\'margin-top:10px;font-size:14px;flex-shrink:0\\'>✅</span><div style=\\'flex:1\\'><input type=\\'text\\' placeholder=\\'Add another selling point…\\' style=\\'width:100%\\'></div>';document.getElementById('aboutItemRows').appendChild(r)})()">+ Add Selling Point</button>
          <div class="divider"></div>
          <div class="form-group"><label>Product Overview (long-form) <span class="opt">Shown in "Product Description" section on Amazon A+</span></label><textarea rows="4" placeholder="Write a 2–4 sentence paragraph expanding on what makes this product special — flavour profile, story, occasions, origin…"></textarea></div>
          <div class="form-group" style="margin-bottom:0"><label>Usage Directions / How to Use</label><textarea rows="2" placeholder="e.g. Spread directly from the individual portion on bread, toast or crackers. Ideal chilled. Can also be used in dips, sauces, and cheesecakes."></textarea></div>
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
              <select>
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
              <input type="text" placeholder="e.g. Cream Cheese &amp; Soft Cheese">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Instacart Promo Label</label>
              <select>
                <option value="">None</option>
                <option>🏷️ Sale</option><option>🆕 New Arrival</option>
                <option>⭐ Staff Pick</option><option>🔥 Popular</option>
                <option>💚 Organic</option><option>🌱 Plant Based</option>
                <option>🎉 Limited Time Offer</option><option>💰 Best Value</option>
              </select>
            </div>
            <div class="form-group">
              <label>Instacart Unit Pricing Display</label>
              <select>
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
              <input type="text" id="icTagInput" placeholder="Add filter tag, press Enter…" onkeydown="addTag(event,'icTagWrap','icTagInput','t-green')">
            </div>
            <div class="hint">Tags map to Instacart dietary filters (e.g. Organic, Vegan, Kosher, Gluten-Free)</div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header"><span class="card-icon">🗂️</span><span class="card-title">Categories &amp; Classification</span><span class="card-subtitle">Amazon-style deep tree</span></div>
        <div class="card-body">

          <div class="form-row">
            <div class="form-group">
              <label>Main Category <span class="req">*</span></label>
              <select id="mainCat" onchange="updateSubCat();updateQuality()">
                <option value="">— Select Department —</option>
                <option value="appliances">Appliances</option>
                <option value="arts">Arts, Crafts &amp; Sewing</option>
                <option value="auto">Automotive &amp; Powersports</option>
                <option value="baby">Baby Products</option>
                <option value="beauty">Beauty &amp; Personal Care</option>
                <option value="books">Books</option>
                <option value="cds">CDs &amp; Vinyl</option>
                <option value="cell">Cell Phones &amp; Accessories</option>
                <option value="clothing">Clothing, Shoes &amp; Jewelry</option>
                <option value="computers">Computers</option>
                <option value="electronics">Electronics</option>
                <option value="garden">Garden &amp; Outdoor</option>
                <option value="grocery">Grocery &amp; Gourmet Food</option>
                <option value="handmade">Handmade Products</option>
                <option value="health">Health &amp; Household</option>
                <option value="home">Home &amp; Kitchen</option>
                <option value="industrial">Industrial &amp; Scientific</option>
                <option value="luggage">Luggage &amp; Travel Gear</option>
                <option value="movies">Movies &amp; TV</option>
                <option value="music">Musical Instruments</option>
                <option value="office">Office Products</option>
                <option value="pet">Pet Supplies</option>
                <option value="software">Software</option>
                <option value="sports">Sports &amp; Outdoors</option>
                <option value="tools">Tools &amp; Home Improvement</option>
                <option value="toys">Toys &amp; Games</option>
                <option value="videogames">Video Games</option>
                <option value="other">Everything Else</option>
              </select>
            </div>
            <div class="form-group">
              <label>Sub Category (Level 2)</label>
              <select id="subCat" onchange="updateSubCat2()"><option value="">Select main first…</option></select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label>Sub-Sub Category (Level 3)</label>
              <select id="subCat2" onchange="updateSubCat3()"><option value="">Select Level 2 first…</option></select>
            </div>
            <div class="form-group">
              <label>Level 4 (Leaf Node)</label>
              <select id="subCat3"><option value="">Select Level 3 first…</option></select>
            </div>
          </div>

          <!-- Browse Node -->
          <div class="form-row">
            <div class="form-group">
              <label>Browse Node ID <span class="opt">(Amazon)</span></label>
              <input type="text" id="browseNodeId" placeholder="e.g. 16310101" class="mono" readonly style="background:var(--bg)">
              <div class="hint">Auto-populated from category selection</div>
            </div>
            <div class="form-group">
              <label>Product Type Keyword <span class="opt">(Amazon backend)</span></label>
              <input type="text" id="productTypeKw" placeholder="e.g. CHEESE, CREAM_CHEESE">
            </div>
          </div>

          <!-- Category Path Breadcrumb -->
          <div id="catPathWrap" style="display:none;margin-bottom:15px">
            <label>Category Path</label>
            <div id="catPath" style="background:var(--bg);border:1.5px solid var(--border);border-radius:8px;padding:9px 14px;font-size:12.5px;color:var(--slate);font-family:'DM Mono',monospace;word-break:break-all"></div>
          </div>

          <!-- Tags -->
          <div class="form-group" style="margin-bottom:0">
            <label>Search Tags</label>
            <div class="tag-wrap" id="tagWrapper" onclick="this.querySelector('input').focus()">
              <span class="tag t-red">cheese <span class="tag-rm" onclick="rmTag(this)">×</span></span>
              <span class="tag t-red">dairy <span class="tag-rm" onclick="rmTag(this)">×</span></span>
              <span class="tag t-red">spread <span class="tag-rm" onclick="rmTag(this)">×</span></span>
              <input type="text" id="tagInput" placeholder="Add tag, press Enter…" onkeydown="addTag(event,'tagWrapper','tagInput','t-red')">
            </div>
            <div class="hint">Press Enter to add. Tags improve search visibility.</div>
          </div>
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

<?php /**PATH C:\laragon\www\6MartAdminpanel\resources\views\admin-views\product\partials\_general_task2.blade.php ENDPATH**/ ?>