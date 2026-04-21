<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        // Legacy vendor orders list expects {status} param.
        $src = route('vendor.order.list', [$status]);

        return view('vendor-views.mf.orders.index', compact('status', 'src'));
    }
}

