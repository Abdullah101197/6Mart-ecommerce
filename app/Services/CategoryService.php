<?php

namespace App\Services;

use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Traits\FileManagerTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class CategoryService
{
    use FileManagerTrait;

    public function getViewByPosition(int $position): string
    {
        // Unified tree-based category UI (supports up to 4 levels).
        return CategoryViewPath::INDEX['view'];
    }

    public function getAddData($request, string|null|Object $parentCategory): array
    {
        $parentIdRaw = $request->input('parent_id');
        $parentId = (filled($parentIdRaw) && (int) $parentIdRaw > 0) ? (int) $parentIdRaw : 0;

        $moduleId = Config::get('module.current_module_id');
        if ($parentId > 0 && $parentCategory) {
            $moduleId = is_object($parentCategory) ? $parentCategory->module_id : $parentCategory['module_id'];
        }

        $position = 0;
        if ($parentId > 0 && $parentCategory) {
            $parentPosition = (int) (is_object($parentCategory) ? $parentCategory->position : ($parentCategory['position'] ?? 0));
            $position = $parentPosition + 1;
        }

        return [
            'name' => $request->name[array_search('default', $request->lang)],
            'image' => $request->hasFile('image') ? $this->upload('category/', 'png', $request->file('image')) : 'def.png',
            'parent_id' => $parentId,
            'position' => $position,
            'priority' => $request->priority??0,
            'module_id' => $moduleId,
        ];
    }

    public function getUpdateData(CategoryUpdateRequest $request, object $object): array
    {
        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        $parentIdRaw = $request->input('parent_id');
        $parentId = (filled($parentIdRaw) && (int) $parentIdRaw > 0) ? (int) $parentIdRaw : 0;
        $position = 0;
        if ($parentId > 0) {
            $parent = \App\Models\Category::withoutGlobalScope('translate')->find($parentId);
            $position = $parent ? ((int) $parent->position + 1) : 0;
        }
        return [
            'slug' => $object->slug ?? "{$slug}{$object->id}",
            'name' => $request->name[array_search('default', $request->lang)],
            'priority' => $request->priority??0,
            'status' => $request->status ?? 0,
            'parent_id' => $parentId,
            'position' => $position,
            'image' => $request->has('image') ? $this->updateAndUpload('category/', $object->image, 'png', $request->file('image')) : $object->image,
        ];
    }

    public function getImportData(Request $request, bool $toAdd = true): array
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (Exception) {
            return ['flag' => 'wrong_format'];
        }
        $moduleId = Config::get('module.current_module_id');

        $data = [];
        foreach ($collections as $collection) {
            if ($collection['Name'] === "") {
                return ['flag' => 'required_fields'];
            }
            $parentId = is_numeric($collection['ParentId']) ? $collection['ParentId'] : 0;
            $array = [
                'name' => $collection['Name'],
                'image' => $collection['Image'],
                'parent_id' => $parentId,
                'module_id' => $moduleId,
                'position' => $collection['Position'],
                'priority' => is_numeric($collection['Priority']) ? $collection['Priority'] : 0,
                'status' => $collection['Status'] == 'active' ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            if(!$toAdd){
                $array['id'] = $collection['Id'];
            }

            $data[] = $array;
        }

        return $data;
    }

    public function getExportData(object $collection): array
    {
        $data = [];
        foreach($collection as $item){
            $data[] = [
                'Id'=>$item->id,
                'Name'=>$item->name,
                'Image'=>$item->image,
                'ParentId'=>$item->parent_id,
                'Position'=>$item->position,
                'Priority'=>$item->priority,
                'Status'=>$item->status == 1 ? 'active' : 'inactive',
            ];
        }
        return $data;
    }
}
