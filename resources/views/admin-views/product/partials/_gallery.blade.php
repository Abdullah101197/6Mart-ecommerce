@forelse($items as $key => $item)
    <tr class="" id="row-{{ $item->id }}" data-id="{{ $item->id }}" data-cost="{{ $item->cost_price }}">
        <td><input type="checkbox" class="row-check pc-check" value="{{ $item->id }}"
                onchange="toggleRow({{ $item->id }}, this)"></td>
        <td>
            <div class="prod-cell">
                <div class="prod-img">
                    <img src="{{ $item['image_full_url'] ?? asset('public/assets/admin/img/160x160/img2.jpg') }}"
                        onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'" alt="">
                </div>
                <div>
                    <div class="prod-name">{{ $item->name }}</div>
                </div>
            </div>
        </td>
        <td>
            <div class="inline-val" onclick="editField({{ $item->id }}, 'sku', '{{ $item->sku }}', 'SKU')">
                <span class="prod-sku">{{ $item->sku ? $item->sku : translate('messages.no_sku') }}</span>
                <span class="inline-edit-icon">✏️</span>
            </div>
            <div class="inline-val" style="margin-top:2px; font-size:11px; color:var(--muted)"
                onclick="editField({{ $item->id }}, 'ean', '{{ $item->ean }}', 'EAN')">
                <span>{{ translate('EAN') }}: {{ $item->ean ? $item->ean : '—' }}</span>
                <span class="inline-edit-icon" style="font-size:9px">✏️</span>
            </div>
        </td>
        <td>
            <div class="cat-crumb">
                @if($item->category && isset($item->category->parent))
                    {{ $item->category->parent->name }}
                    <br><span>{{ $item->category->name }}</span>
                @elseif($item->category)
                    <span>{{ $item->category->name }}</span>
                @else
                    {{ translate('Uncategorized') }}
                @endif
            </div>
        </td>
        <td>
            <div class="inline-val" onclick="editField({{ $item->id }}, 'price', {{ $item->price }}, 'Price (QAR)')">
                {{ \App\CentralLogics\Helpers::format_currency($item->price) }}
                <span class="inline-edit-icon">✏️</span>
            </div>
            <div class="cat-crumb" style="margin-top:2px">{{ translate('Cost') }}:
                {{ \App\CentralLogics\Helpers::format_currency($item->cost_price) }}
            </div>
        </td>
        <td>
            @php
                $margin = $item->price > 0 ? round((($item->price - $item->cost_price) / $item->price) * 100) : 0;
                $marginClass = $margin >= 35 ? 'mp-good' : ($margin >= 20 ? 'mp-ok' : 'mp-bad');
            @endphp
            <div class="margin-pill {{ $marginClass }}"
                onclick="editField({{ $item->id }}, 'cost_price', {{ $item->cost_price }}, 'Cost Price (QAR)')">
                {{ $margin }}%
            </div>
        </td>
        <td class="stock-cell">
            <div class="inline-val" onclick="editField({{ $item->id }}, 'stock', {{ $item->stock }}, 'Stock Quantity')">
                <span class="stock-num">{{ $item->stock }}</span>
                <span class="inline-edit-icon">✏️</span>
            </div>
            @php
                $stockPercent = min(100, max(0, ($item->stock / 500) * 100)); // Adjusted for demo visual
                $stockFill = $item->stock == 0 ? 'sf-low' : ($item->stock < 20 ? 'sf-med' : 'sf-high');
            @endphp
            <div class="stock-bar-bg">
                <div class="stock-bar-fill {{ $stockFill }}" style="width: {{ $stockPercent }}%"></div>
            </div>
        </td>
        <td>
            @php
                $shelfLife = $item->expiry_days ?? 0;
                $shelfClass = 'expiry-na';
                $shelfLabel = '—';
                if ($shelfLife > 0) {
                    $shelfLabel = $shelfLife . ' ' . translate('Days');
                    $shelfClass = $shelfLife <= 14 ? 'expiry-danger' : ($shelfLife <= 30 ? 'expiry-warn' : 'expiry-ok');
                }
            @endphp
            <div class="inline-val {{ $shelfClass }}"
                onclick="editField({{ $item->id }}, 'expiry_days', {{ $shelfLife }}, 'Min. Shelf Life (Days)')">
                @if($shelfLife > 0 && $shelfLife <= 14) ⚠️ @elseif($shelfLife > 0 && $shelfLife <= 30) ⏰ @endif
                {{ $shelfLabel }}
                <span class="inline-edit-icon">✏️</span>
            </div>
        </td>
        <td><span class="{{ $item->tax > 0 ? 'vat-active' : 'vat-zero' }}">{{ (float) $item->tax }}%</span></td>
        <td>
            @if($item->status == 1 && $item->stock > 0)
                <span class="badge b-active"><span class="b-dot"></span> {{ translate('Active') }}</span>
            @elseif($item->stock == 0)
                <span class="badge b-oos"><span class="b-dot"></span> {{ translate('Out_of_Stock') }}</span>
            @else
                <span class="badge b-draft"><span class="b-dot"></span> {{ translate('Draft') }}</span>
            @endif
        </td>
        <td>
            <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
                <a title="{{translate('messages.use_this_product_info')}}"
                    href="{{route('admin.item.edit', ['id' => $item['id'], 'product_gellary' => true])}}" class="ra-btn"
                    style="background:var(--primary);color:#fff;border-color:var(--primary);font-weight:600;white-space:nowrap">
                    ✅ {{ translate('messages.use_this_product_info') }}
                </a>
                <a title="{{translate('messages.edit_product')}}" href="{{route('admin.item.edit', [$item['id']])}}"
                    class="ra-btn">✏️ Edit</a>
                <a title="{{translate('messages.view_product')}}" href="{{route('admin.item.view', [$item['id']])}}"
                    class="ra-btn text-info" style="color:var(--slate)!important">👁️</a>
                <a title="{{translate('messages.delete_product')}}" href="javascript:"
                    onclick="form_alert('product-{{$item['id']}}','{{ translate('Want_to_delete_this_item') }}')"
                    class="ra-btn del">🗑️</a>
                <form action="{{route('admin.item.delete', [$item['id']])}}" method="post" id="product-{{$item['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center p-5">
            <div class="empty-state">
                <img src="{{asset('public/assets/admin/img/900x400/img1.jpg')}}"
                    style="width:120px; opacity:0.5; margin-bottom:15px;">
                <p style="color:var(--muted)">{{translate('messages.no_data_found')}}</p>
                <a href="{{ route('admin.item.add-new', ['module_id' => Config::get('module.current_module_id')]) }}"
                    class="btn btn-sm btn-primary">
                    <i class="tio-add"></i> {{ translate('messages.add_new_product') }}
                </a>
            </div>
        </td>
    </tr>
@endforelse