<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureFeatureController extends Controller
{
    public function comingSoon(Request $request, string $feature)
    {
        if ($feature === 'pos') {
            $src = route('vendor.pos.index');
            return view('vendor-views.mf.pos.index', compact('src'));
        }
        return view('vendor-views.mf.coming-soon', compact('feature'));
    }
}

