<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ManufutureController extends Controller
{
    public function dashboard(Request $request)
    {
        $module_type = Config::get('module.current_module_type');
        if ($module_type === 'settings') {
            return redirect()->route('admin.business-settings.business-setup');
        }
        if ($module_type === 'rental' && addon_published_status('Rental') == 1) {
            return redirect()->route('admin.rental.dashboard');
        }
        if ($module_type === 'rental' && addon_published_status('Rental') == 0) {
            return view('errors.404');
        }

        $dash = app(DashboardController::class);
        $params = [
            'zone_id' => $request['zone_id'] ?? 'all',
            'module_id' => Config::get('module.current_module_id'),
            'statistics_type' => $request['statistics_type'] ?? 'overall',
            'user_overview' => $request['user_overview'] ?? 'overall',
            'commission_overview' => $request['commission_overview'] ?? 'this_year',
            'business_overview' => $request['business_overview'] ?? 'overall',
        ];
        session()->put('dash_params', $params);

        $data = $dash->dashboard_data($request);
        $total_sell = $data['total_sell'];
        $commission = $data['commission'];
        $delivery_commission = $data['delivery_commission'];
        $label = $data['label'];

        $view = null;
        if (view()->exists("admin-views.mf.dashboard-{$module_type}")) {
            $view = "admin-views.mf.dashboard-{$module_type}";
        } elseif (view()->exists("admin-views.dashboard-{$module_type}_v2")) {
            // Use prepared v2 templates for module dashboards during rollout
            $view = "admin-views.dashboard-{$module_type}_v2";
        } else {
            $view = "admin-views.mf.dashboard";
        }

        return view($view, compact('data', 'total_sell', 'commission', 'delivery_commission', 'label', 'params', 'module_type'));
    }
}

