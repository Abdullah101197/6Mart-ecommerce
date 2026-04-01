<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\ProductSelectOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ProductSelectOptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:origin_country,seller,country_of_manufacture,packaging_type,recyclable,storage_type,condition,age_restriction,warranty,return_policy,product_type',
            'name' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleId = Config::get('module.current_module_id');
        $opt = ProductSelectOption::firstOrCreate(
            [
                'type' => $request->type,
                'name' => trim($request->name),
                'module_id' => $moduleId,
            ],
            []
        );

        return response()->json([
            'id' => $opt->id,
            'name' => $opt->name,
            'type' => $opt->type,
            'message' => 'Added successfully',
        ]);
    }
}

