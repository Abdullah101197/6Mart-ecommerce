@php($store = \App\CentralLogics\Helpers::get_store_data())
@php($sub = $store?->store_sub ?? $store?->store_sub_update_application)
@php($allowAll = ($store?->store_business_model ?? null) === 'commission')
@php($canAiPulse = $allowAll || ((int) data_get($sub, 'ai_pulse', 0) === 1))
@php($itemTotal = (int) ($item_total ?? 0))
@php($lowStock = (int) ($out_of_stock_count ?? 0))
@php($inStock = max(0, $itemTotal - $lowStock))
@php($stockHealth = $itemTotal > 0 ? round(($inStock / $itemTotal) * 100) : 0)

<div class="row g-2 mb-3">
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('messages.total_earning') }}</div>
                        <div class="h3 mb-0 text--primary">{{ \App\CentralLogics\Helpers::format_currency(array_sum($earning ?? [])) }}</div>
                        <div class="text-muted fz-12">{{ translate('messages.yearly_statistics') }}</div>
                    </div>
                    <div class="rounded bg-soft-primary d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-money-vs text--primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('messages.orders') }}</div>
                        <div class="h3 mb-0" style="color:#2563eb">{{ (int) data_get($data, 'all', 0) }}</div>
                        <div class="text-muted fz-12">{{ translate('messages.dashboard_order_statistics') }}</div>
                    </div>
                    <div class="rounded bg-soft-info d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-shopping-cart text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('Stock Health') }}</div>
                        <div class="h3 mb-0" style="color:#16a34a">{{ $stockHealth }}%</div>
                        <div class="text-muted fz-12">{{ $lowStock }} {{ translate('messages.low_stock') }}</div>
                    </div>
                    <div class="rounded bg-soft-success d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-box text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('messages.items') }}</div>
                        <div class="h3 mb-0" style="color:#6366f1">{{ $itemTotal }}</div>
                        <div class="text-muted fz-12">{{ translate('Total SKUs') }}</div>
                    </div>
                    <div class="rounded bg-soft-secondary d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-apps text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('messages.pending') }}</div>
                        <div class="h3 mb-0" style="color:#d97706">{{ (int) data_get($data, 'confirmed', 0) }}</div>
                        <div class="text-muted fz-12">{{ translate('messages.confirmed') }}</div>
                    </div>
                    <div class="rounded bg-soft-warning d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-timer text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase text-muted fz-10">{{ translate('AI Pulse') }}</div>
                        <div class="h3 mb-0" style="color:#0891b2">{{ $canAiPulse ? translate('Enabled') : translate('Disabled') }}</div>
                        <div class="text-muted fz-12">{{ translate('messages.subscription') }}</div>
                    </div>
                    <div class="rounded bg-soft-info d-flex align-items-center justify-content-center" style="width:38px;height:38px">
                        <i class="tio-bolt text-info"></i>
                    </div>
                </div>
                @if($canAiPulse)
                    <a href="{{ route('vendor.ai_pulse') }}" class="btn btn-sm btn--primary mt-2 w-100">{{ translate('View All Insights') }}</a>
                @endif
            </div>
        </div>
    </div>
</div>

