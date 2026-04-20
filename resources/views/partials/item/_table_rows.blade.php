@php
    /**
     * Shared item list table rows
     *
     * Required:
     * - $items
     * - $context: 'admin'|'vendor'
     *
     * Optional:
     * - $showStoreColumn (bool) default: ($context === 'admin')
     * - $showRecommendedToggle (bool) default: ($context === 'vendor')
     */
    $context = $context ?? 'vendor';
    $showStoreColumn = $showStoreColumn ?? ($context === 'admin');
    $showRecommendedToggle = $showRecommendedToggle ?? ($context === 'vendor');
@endphp

@foreach ($items as $key => $item)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            <a class="media align-items-center"
               href="{{ $context === 'admin' ? route('admin.item.view', [$item['id']]) : route('vendor.item.view', [$item['id']]) }}">
                <img class="avatar avatar-lg mr-3 onerror-image"
                     src="{{ $item['image_full_url'] ?? asset('assets/admin/img/160x160/img2.jpg') }}"
                     data-onerror-image="{{ asset('assets/admin/img/160x160/img2.jpg') }}"
                     alt="{{ $item->name }} image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">{{ Str::limit($item['name'], 20, '...') }}</h5>
                </div>
            </a>
        </td>
        <td>
            {{ Str::limit($item->category ? $item->category->name : translate('messages.category_deleted'), 20, '...') }}
        </td>

        @if ($showStoreColumn)
            <td>
                {{ Str::limit($item->store ? $item->store->name : translate('messages.store deleted!'), 20, '...') }}
            </td>
        @endif

        <td>
            <div class="{{ $showStoreColumn ? 'text-right' : '' }} mw--85px">
                {{ \App\CentralLogics\Helpers::format_currency($item['price']) }}
            </div>
        </td>

        @if ($showRecommendedToggle)
            <td>
                <div class="d-flex">
                    <div class="mx-auto">
                        <label class="toggle-switch toggle-switch-sm mr-2" data-toggle="tooltip" data-placement="top"
                               title="{{ translate('messages.Recommend_to_customers') }}"
                               for="recCheckbox{{ $item->id }}">
                            <input type="checkbox"
                                   data-url="{{ route('vendor.item.recommended', [$item['id'], $item->recommended ? 0 : 1]) }}"
                                   class="toggle-switch-input redirect-url"
                                   id="recCheckbox{{ $item->id }}"
                                   {{ $item->recommended ? 'checked' : '' }}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </td>
        @endif

        <td>
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{ $item->id }}">
                <input type="checkbox"
                       class="toggle-switch-input redirect-url"
                       data-url="{{ $context === 'admin'
                            ? route('admin.item.status', [$item['id'], $item->status ? 0 : 1])
                            : route('vendor.item.status', [$item['id'], $item->status ? 0 : 1]) }}"
                       id="stocksCheckbox{{ $item->id }}"
                       {{ $item->status ? 'checked' : '' }}>
                <span class="toggle-switch-label mx-auto">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>

        <td>
            <div class="btn--container justify-content-center">
                <a class="btn action-btn btn--primary btn-outline-primary {{ $context === 'vendor' ? 'btn-sm' : '' }}"
                   href="{{ $context === 'admin' ? route('admin.item.edit', [$item['id']]) : route('vendor.item.edit', [$item['id']]) }}"
                   title="{{ translate('messages.edit_item') }}">
                    <i class="tio-edit"></i>
                </a>
                <a class="btn action-btn btn--danger btn-outline-danger {{ $context === 'vendor' ? 'btn-sm' : '' }} form-alert"
                   href="javascript:"
                   data-id="food-{{ $item['id'] }}"
                   data-message="{{ $context === 'admin' ? translate('messages.Want_to_delete_this_item') : translate('Want to delete this item ?') }}"
                   title="{{ translate('messages.delete_item') }}">
                    <i class="tio-delete-outlined"></i>
                </a>
                <form action="{{ $context === 'admin' ? route('admin.item.delete', [$item['id']]) : route('vendor.item.delete', [$item['id']]) }}"
                      method="post"
                      id="food-{{ $item['id'] }}">
                    @csrf
                    @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach

