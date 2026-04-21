<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $moduleId = $request->query('module_id', config('module.current_module_id'));

        $src = route('admin.order.list', [$status]);
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string)$moduleId);
        }

        return view('admin-views.mf.orders.index', compact('status', 'src', 'moduleId'));
    }
}

