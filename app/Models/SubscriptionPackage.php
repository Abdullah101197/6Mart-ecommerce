<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class SubscriptionPackage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'price'=>'float',
        'validity'=>'integer',
        'chat'=>'integer',
        'review'=>'integer',
        'package_id'=>'integer',
        'status'=>'integer',
        'pos'=>'integer',
        'default'=>'integer',
        'mobile_app'=>'integer',
        'total_package_renewed'=>'integer',
        'self_delivery'=>'integer',
        'store_id'=>'integer',
        'is_trial'=>'integer',
        'max_order'=>'string',
        'max_product'=>'string',
        'category' => 'integer',
        'role' => 'integer',
        'deliveryman_list' => 'integer',
        'employee' => 'integer',
        'item' => 'integer',
        'banner' => 'integer',
        'campaign' => 'integer',
        'wallet' => 'integer',
        'wallet_method' => 'integer',
        'coupon' => 'integer',
        'advertisement' => 'integer',
        'advertisement_list' => 'integer',
        'addon' => 'integer',
        'store_setup' => 'integer',
        'notification_setup' => 'integer',
        'profile' => 'integer',
        'my_shop' => 'integer',
        'expense_report' => 'integer',
        'disbursement_report' => 'integer',
        'vat_report' => 'integer',
        'ai_pulse' => 'integer',
        'omnichannel' => 'integer',
        'returns' => 'integer',
        'helpcenter' => 'integer',
        'order_rms_ui' => 'integer',
        'dash_kpi_gmv' => 'integer',
        'dash_kpi_orders' => 'integer',
        'dash_kpi_stock_health' => 'integer',
        'dash_kpi_items' => 'integer',
        'dash_kpi_tasks_due' => 'integer',
        'dash_kpi_ai_pulse_status' => 'integer',
        'dash_kpi_nps' => 'integer',
        'dash_revenue_trend_channels' => 'integer',
        'dash_channel_mix' => 'integer',
        'dash_ai_pulse_live' => 'integer',
        'dash_recent_orders' => 'integer',
        'dash_inventory_alerts' => 'integer',
        'dash_today_tasks' => 'integer',
        'dash_store_activity' => 'integer',
        'dash_top_loyal_buyers' => 'integer',
        'dash_omnichannel_summary' => 'integer',
        'is_manufuture' => 'boolean',
        'vendor_types' => 'array',
    ];

    /**
     * @param $query
     * @param $status
     * @return void
     */
    public function scopeOfStatus($query, $status): void
    {
        $query->where('status', '=', $status);
    }

    public function transactions()
    {
        return $this->hasMany(SubscriptionTransaction::class, 'package_id');
    }
    public function currentSubscribers()
    {
        return $this->hasMany(StoreSubscription::class, 'package_id')->where('status' ,1);
    }
    public function Subscribers()
    {
        return $this->hasMany(StoreSubscription::class, 'package_id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }
    public function getPackageNameAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'package_name') {
                    return $translation['value'];
                }
            }
        }
        return $value;
    }
    public function getTextAttribute($value){
        if (count($this->translations) > 0) {
            foreach ($this->translations as $translation) {
                if ($translation['key'] == 'text') {
                    return $translation['value'];
                }
            }
        }
        return $value;
    }

    protected static function booted()
    {
        static::addGlobalScope('translate', function (Builder $builder) {
            $builder->with(['translations' => function ($query) {
                return $query->where('locale', app()->getLocale());
            }]);
        });
    }
}
