<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufuturePosController extends Controller
{
    public function index(Request $request)
    {
        $moduleId = $request->query('module_id', config('module.current_module_id'));

        $src = route('admin.pos.index');
        if ($moduleId !== null) {
            $src .= '?module_id=' . urlencode((string) $moduleId);
        }

        return view('admin-views.mf.pos.index', compact('src', 'moduleId'));
    }
}

