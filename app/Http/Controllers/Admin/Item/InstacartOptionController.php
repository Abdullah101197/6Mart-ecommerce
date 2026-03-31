<?php

namespace App\Http\Controllers\Admin\Item;

use App\Http\Controllers\Controller;
use App\Models\InstacartOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class InstacartOptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:department,promo_label,unit_pricing_display',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleId = Config::get('module.current_module_id');

        $items = InstacartOption::query()
            ->where('type', $request->type)
            ->where(function ($q) use ($moduleId) {
                $q->whereNull('module_id')->orWhere('module_id', $moduleId);
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:department,promo_label,unit_pricing_display',
            'name' => 'required|string|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $moduleId = Config::get('module.current_module_id');

        $opt = InstacartOption::firstOrCreate(
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

