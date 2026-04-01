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
            'type' => 'required|in:origin_country,seller,country_of_manufacture,packaging_type,recyclable,storage_type,condition,age_restriction,warranty,return_policy,product_type,variant_color,variant_type,schema_type',
            'name' => 'required|string|max:191',
            'value' => 'nullable|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleId = Config::get('module.current_module_id');

        $type = (string) $request->type;
        $name = trim((string) $request->name);
        $value = $request->has('value') ? trim((string) $request->value) : null;

        if ($type === 'variant_color') {
            // Accept #RGB or #RRGGBB
            if (!is_string($value) || !preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $value)) {
                return response()->json(['errors' => ['value' => ['Please provide a valid hex color like #ff0000']]], 422);
            }
            $value = strtolower($value);
        } elseif ($type === 'variant_type') {
            $value = null;
        } elseif ($type === 'schema_type') {
            $value = null;
        } else {
            $value = null;
        }

        $opt = ProductSelectOption::firstOrCreate(
            [
                'type' => $type,
                'name' => $name,
                'module_id' => $moduleId,
            ],
            ['value' => $value]
        );

        // If existing record was found and we now have value (e.g. seeded without value), update it.
        if ($type === 'variant_color' && filled($value) && blank($opt->value)) {
            $opt->value = $value;
            $opt->save();
        }

        return response()->json([
            'id' => $opt->id,
            'name' => $opt->name,
            'type' => $opt->type,
            'value' => $opt->value,
            'message' => 'Added successfully',
        ]);
    }
}

