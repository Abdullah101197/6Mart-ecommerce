<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use Closure;
use Illuminate\Http\Request;

class ManufuturePortalMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $store = Helpers::get_store_data();
        $portal = is_object($store) ? ($store->portal ?? null) : null;

        if ($portal !== 'manufuture') {
            return redirect()->route('vendor.dashboard');
        }

        return $next($request);
    }
}

