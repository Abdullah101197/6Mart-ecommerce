<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;

class Subscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$module): Response
    {
        if (auth('vendor_employee')->check() || auth('vendor')->check()) {
            $store= Helpers::get_store_data();
            if($store->store_business_model== 'commission'){
                return $next($request);
            }


            elseif($store->store_business_model == 'unsubscribed') {
                Toastr::error(translate('messages.your_subscription_is_expired.You_can_only_process_your_on_going_orders.'));
                return back();
            }
            elseif($store->store_business_model == 'none') {
                Toastr::error(translate('Please_chose_a_business_plan_to_continue_your_services'));
                return back();
            }


            elseif($store->store_business_model == 'subscription') {
                    if($store->store_sub == null){
                        Toastr::error(translate('messages.you_are_not_subscribed_to_any_package'));
                        return back();
                    } else {
                    $store_sub=$store?->store_sub;

                    $permissionMap = [
                        // Existing (already present + used)
                        'reviews' => 'review',
                        'pos' => 'pos',
                        'deliveryman' => 'self_delivery',
                        'chat' => 'chat',

                        // Keys used in routes/vendor.php (new DB cols added)
                        'category' => 'category',
                        'role' => 'role',
                        'deliveryman_list' => 'deliveryman_list',
                        'employee' => 'employee',
                        'item' => 'item',
                        'banner' => 'banner',
                        'campaign' => 'campaign',
                        'wallet' => 'wallet',
                        'wallet_method' => 'wallet_method',
                        'coupon' => 'coupon',
                        'advertisement' => 'advertisement',
                        'advertisement_list' => 'advertisement_list',
                        'addon' => 'addon',
                        'store_setup' => 'store_setup',
                        'notification_setup' => 'notification_setup',
                        'profile' => 'profile',
                        'my_shop' => 'my_shop',
                        'expense_report' => 'expense_report',
                        'disbursement_report' => 'disbursement_report',
                        'vat_report' => 'vat_report',
                        // Unified vendor panel new modules
                        'ai_pulse' => 'ai_pulse',
                        'omnichannel' => 'omnichannel',
                        'returns' => 'returns',
                        'helpcenter' => 'helpcenter',
                    ];

                    // If an unknown key is used, deny (safer) so missing mappings don't silently bypass access control.
                    if (!array_key_exists($module, $permissionMap)) {
                        Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                        return back();
                    }

                    $field = $permissionMap[$module];
                    if ((int) data_get($store_sub, $field, 0) === 1) {
                        return $next($request);
                    }

                    Toastr::error(translate('messages.your_package_does_not_include_this_section'));
                    return back();
                }
            }


        }
        return $next($request);
    }
}
