<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function comingSoon(Request $request, string $feature)
    {
        return view('vendor-views.feature-coming-soon', compact('feature'));
    }
}

