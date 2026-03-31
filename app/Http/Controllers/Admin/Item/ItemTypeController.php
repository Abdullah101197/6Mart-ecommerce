<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\ItemType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class ItemTypeController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'is_veg' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleId = Config::get('module.current_module_id');
        $type = ItemType::firstOrCreate(
            [
                'name' => trim($request->name),
                'module_id' => $moduleId,
            ],
            [
                'is_veg' => (bool) $request->boolean('is_veg'),
            ]
        );

        // If it existed but is_veg differs, update it
        if ($type->exists && $type->is_veg !== (bool) $request->boolean('is_veg')) {
            $type->is_veg = (bool) $request->boolean('is_veg');
            $type->save();
        }

        return response()->json([
            'id' => $type->id,
            'name' => $type->name,
            'is_veg' => (int) $type->is_veg,
            'message' => 'Item type added successfully',
        ]);
    }
}

