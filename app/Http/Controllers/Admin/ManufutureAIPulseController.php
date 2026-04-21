<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ManufutureAIPulseController extends Controller
{
    public function index(Request $request)
    {
        $module_type = Config::get('module.current_module_type');
        return view('admin-views.mf.ai-pulse.index', compact('module_type'));
    }
}

