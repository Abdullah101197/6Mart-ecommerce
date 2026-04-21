<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManufutureProductController extends Controller
{
    public function index(Request $request)
    {
        $src = route('vendor.item.list', ['mf_embed' => 1]);

        return view('vendor-views.mf.products.index', compact('src'));
    }
}

