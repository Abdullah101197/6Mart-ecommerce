<?php

namespace App\Models;

use App\Scopes\ZoneScope;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StoreSubscription extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected $casts = [
        // 'expiry_date'=> 'datetime',
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
        'product_rms_ui' => 'integer',
        'product_dashboard_ui' => 'integer',
        'product_inhouse_ui' => 'integer',
        'product_seller_ui' => 'integer',
        'product_digital_ui' => 'integer',
        'brand_rms_ui' => 'integer',
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
    ];

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class,'package_id');
    }
    public function transcations()
    {
        return $this->hasMany(SubscriptionTransaction::class,'store_id');
    }
    public function last_transcations()
    {
        return $this->hasOne(SubscriptionTransaction::class,'store_subscription_id')->latestOfMany();
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    protected static function booted()
    {
        static::addGlobalScope(new ZoneScope);
    }
    public function getExpiryDateParsedAttribute($value){
        return Carbon::parse($this->expiry_date) ;
    }
}
