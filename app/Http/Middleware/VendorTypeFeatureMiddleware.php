<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorTypeFeatureMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!auth('vendor')->check() && !auth('vendor_employee')->check()) {
            return $next($request);
        }

        $store = Helpers::get_store_data();
        $vendorType = is_object($store) ? ((string) ($store->vendor_type ?? '')) : '';
        $vendorType = $vendorType !== '' ? $vendorType : (string) config('vendor_types.default', 'shopkeeper');

        $types = (array) config('vendor_types.types', []);
        $allowed = (array) ($types[$vendorType] ?? []);

        if (in_array($feature, $allowed, true)) {
            return $next($request);
        }

        Toastr::error(translate('messages.access_denied'));
        return back();
    }
}

