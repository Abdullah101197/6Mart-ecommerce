<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureProductController extends Controller
{
    public function index(Request $request)
    {
        $moduleId = $request->query('module_id', config('module.current_module_id'));

        $src = route('admin.item.list');
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string) $moduleId);
        }

        return view('admin-views.mf.products.index', compact('src', 'moduleId'));
    }
}

