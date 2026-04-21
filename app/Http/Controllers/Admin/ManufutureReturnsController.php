<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureReturnsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        $moduleId = $request->query('module_id', config('module.current_module_id'));

        // Legacy refunds/returns live under admin.refund.* routes.
        $src = route('admin.refund.refund_attr', [$status]);
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string) $moduleId);
        }

        return view('admin-views.mf.returns.index', compact('src', 'status', 'moduleId'));
    }
}

