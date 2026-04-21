<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\DashboardController;
use Illuminate\Http\Request;

class ManufutureController extends Controller
{
    public function dashboard(Request $request)
    {
        $dash = app(DashboardController::class);
        $params = [
            'statistics_type' => $request['statistics_type'] ?? 'overall'
        ];
        session()->put('dash_params', $params);

        $data = $dash->dashboard_order_stats_data();

        return view('vendor-views.mf.dashboard', compact('data', 'params'));
    }
}

