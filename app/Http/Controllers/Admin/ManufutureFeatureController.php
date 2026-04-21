<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureFeatureController extends Controller
{
    public function comingSoon(Request $request, string $feature)
    {
        return view('admin-views.mf.coming-soon', compact('feature'));
    }
}

