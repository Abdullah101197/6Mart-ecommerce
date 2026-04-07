<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreDirectoryToCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $moduleId = (int) env('STORE_DIRECTORY_MODULE_ID', 0);
        if ($moduleId <= 0) {
            $moduleId = (int) (DB::table('modules')->where('status', 1)->orderBy('id')->value('id') ?? 1);
        }

        $departmentToCategoryId = [];
        $departments = DB::table('departments')
            ->select(['id', 'department_name', 'department_code'])
            ->orderBy('id')
            ->get();

        $departmentIdToCode = [];
        foreach ($departments as $department) {
            $departmentIdToCode[$department->id] = strtolower(trim((string) $department->department_code));
            $slug = 'store-dir-dept-' . strtolower(trim((string) $department->department_code));
            $departmentToCategoryId[$department->id] = $this->upsertCategory(
                moduleId: $moduleId,
                position: 0,
                parentId: 0,
                slug: $slug,
                name: (string) $department->department_name
            );
        }

        $subcategories = DB::table('subcategories')
            ->select(['id', 'department_id', 'subcategory_name', 'subcategory_code'])
            ->orderBy('id')
            ->get();

        foreach ($subcategories as $subcategory) {
            $parentId = $departmentToCategoryId[$subcategory->department_id] ?? null;
            if (!$parentId) {
                continue;
            }

            $departmentCode = $departmentIdToCode[$subcategory->department_id] ?? null;
            if (!$departmentCode) {
                continue;
            }

            $subcategoryCode = strtolower(trim((string) $subcategory->subcategory_code));
            $slug = 'store-dir-sub-' . $departmentCode . '-' . $subcategoryCode;

            $this->upsertCategory(
                moduleId: $moduleId,
                position: 1,
                parentId: (int) $parentId,
                slug: $slug,
                name: (string) $subcategory->subcategory_name,
                legacySlug: 'store-dir-sub-' . $subcategoryCode
            );
        }
    }

    private function upsertCategory(
        int $moduleId,
        int $position,
        int $parentId,
        string $slug,
        string $name,
        ?string $legacySlug = null
    ): int
    {
        $existing = Category::withoutGlobalScope('translate')
            ->where('module_id', $moduleId)
            ->where('slug', $slug)
            ->first();

        if ($existing) {
            DB::table('categories')->where('id', $existing->id)->update([
                'name' => $name,
                'parent_id' => $parentId,
                'position' => $position,
                'module_id' => $moduleId,
                'updated_at' => now(),
            ]);
            return (int) $existing->id;
        }

        if ($legacySlug) {
            $legacy = Category::withoutGlobalScope('translate')
                ->where('module_id', $moduleId)
                ->where('slug', $legacySlug)
                ->where('parent_id', $parentId)
                ->first();
            if ($legacy) {
                DB::table('categories')->where('id', $legacy->id)->update([
                    'slug' => $slug,
                    'name' => $name,
                    'parent_id' => $parentId,
                    'position' => $position,
                    'module_id' => $moduleId,
                    'updated_at' => now(),
                ]);
                return (int) $legacy->id;
            }
        }

        $category = new Category();
        $category->name = $name;
        $category->image = 'def.png';
        $category->parent_id = $parentId;
        $category->position = $position;
        $category->status = 1;
        $category->priority = 0;
        $category->module_id = $moduleId;
        $category->featured = 0;
        $category->save();

        // Avoid name-based slug generation so we can be fully idempotent.
        DB::table('categories')->where('id', $category->id)->update([
            'slug' => $slug,
        ]);

        return (int) $category->id;
    }
}

