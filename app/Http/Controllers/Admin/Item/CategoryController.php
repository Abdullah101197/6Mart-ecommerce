<?php

namespace App\Http\Controllers\Admin\Item;

use App\CentralLogics\Helpers;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\TranslationRepositoryInterface;
use App\Enums\ExportFileNames\Admin\Category;
use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Exports\CategoryExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\CategoryAddRequest;
use App\Http\Requests\Admin\CategoryBulkExportRequest;
use App\Http\Requests\Admin\CategoryBulkImportRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Services\CategoryService;
use App\Traits\ImportExportTrait;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CategoryController extends BaseController
{
    use ImportExportTrait;

    public function __construct(
        protected CategoryRepositoryInterface    $categoryRepo,
        protected CategoryService                $categoryService,
        protected TranslationRepositoryInterface $translationRepo
    )
    {
    }

    public function index(?Request $request): View|Collection|LengthAwarePaginator|null
    {
        return $this->getCategoryView($request);
    }

    private function getCategoryView(Request $request): View
    {
        $moduleId = Config::get('module.current_module_id');
        $allCategories = \App\Models\Category::withoutGlobalScope('translate')
            ->with(['module'])
            ->when($moduleId, fn ($q) => $q->where('module_id', $moduleId))
            ->orderBy('parent_id')
            ->orderBy('priority', 'desc')
            ->orderBy('id')
            ->get();

        $categoryTree = $this->buildCategoryTree($allCategories);
        $categoryOptions = $this->buildCategoryOptions($categoryTree);
        $treeRows = $this->flattenCategoryTree($categoryTree);

        $language = getWebConfig('language');
        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];
        $taxVats = $taxData['taxVats'];

        return view($this->categoryService->getViewByPosition((int) ($request['position'] ?? 0)), compact(
            'language',
            'categoryWiseTax',
            'taxVats',
            'allCategories',
            'categoryTree',
            'categoryOptions',
            'treeRows'
        ));
    }

    public function add(CategoryAddRequest $request): RedirectResponse|JsonResponse
    {
        $parentCategory = null;
        $parentId = (filled($request['parent_id']) && (int) $request['parent_id'] > 0) ? (int) $request['parent_id'] : 0;
        if ($parentId > 0) {
            $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $parentId]);
            $hierarchyError = $this->validateHierarchy(parent: $parentCategory, currentId: null);
            if ($hierarchyError) {
                Toastr::error($hierarchyError);
                return back();
            }
        }

        $category = $this->categoryRepo->add(
            data: $this->categoryService->getAddData(
                request: $request,
                parentCategory: $parentCategory
            )
        );
        $this->translationRepo->addByModel(request: $request, model: $category, modelPath: 'App\Models\Category', attribute: 'name');

        if (addon_published_status('TaxModule')) {
            $SystemTaxVat = \Modules\TaxModule\Entities\SystemTaxSetup::where('is_active', 1)->where('is_default', 1)->first();
            if ($SystemTaxVat?->tax_type == 'category_wise') {

                foreach ($request['tax_ids'] ?? [] as $tax_ids) {
                    \Modules\TaxModule\Entities\Taxable::create(
                        [
                            'taxable_type' => 'App\Models\Category',
                            'taxable_id' => $category->id,
                            'system_tax_setup_id' => $SystemTaxVat->id
                            , 'tax_id' => $tax_ids
                        ],
                    );
                }

            }
        }

        Toastr::success(translate('messages.category_added_successfully'));

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'id' => $category->id,
                'name' => $category->name,
                'position' => $category->position,
                'parent_id' => $category->parent_id,
                'message' => translate('messages.category_added_successfully'),
            ]);
        }

        return back();
    }

    public function getUpdateView(string|int $id): JsonResponse
    {
        $category = $this->categoryRepo->getFirstWithoutGlobalScopeWhere(params: ['id' => $id]);
        $language = getWebConfig('language');

        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];
        $taxVats = $taxData['taxVats'];
        $taxVatIds = $categoryWiseTax ? $category->taxVats()->pluck('tax_id')->toArray() : [];
        $moduleId = Config::get('module.current_module_id');
        $allCategories = \App\Models\Category::withoutGlobalScope('translate')
            ->with(['module'])
            ->when($moduleId, fn ($q) => $q->where('module_id', $moduleId))
            ->orderBy('parent_id')
            ->orderBy('priority', 'desc')
            ->orderBy('id')
            ->get();

        $categoryTree = $this->buildCategoryTree($allCategories);
        $categoryOptions = $this->buildCategoryOptions($categoryTree, disabledIds: [$category->id]);
        return response()->json([
            'view' => view('admin-views.category._edit', compact('category', 'taxVats', 'categoryWiseTax', 'language', 'taxVatIds', 'categoryOptions'))->render(),
        ]);
    }

    public function updateStatus(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['status' => $request['status']]);
        Toastr::success(translate('messages.category_status_updated'));
        return back();
    }

    public function updateFeatured(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['id'], data: ['featured' => $request['featured']]);
        Toastr::success(translate('messages.category_featured_updated'));
        return back();
    }

    public function update(CategoryUpdateRequest $request, string|int $id): RedirectResponse
    {
        $mainCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $id]);
        $parentId = (filled($request['parent_id']) && (int) $request['parent_id'] > 0) ? (int) $request['parent_id'] : 0;
        if ($parentId > 0) {
            $parentCategory = $this->categoryRepo->getFirstWhere(params: ['id' => $parentId]);
            $hierarchyError = $this->validateHierarchy(parent: $parentCategory, currentId: (int) $id);
            if ($hierarchyError) {
                Toastr::error($hierarchyError);
                return back();
            }
        }
        $category = $this->categoryRepo->update(id: $id, data: $this->categoryService->getUpdateData(request: $request, object: $mainCategory));
        $this->translationRepo->updateByModel(request: $request, model: $category, modelPath: 'App\Models\Category', attribute: 'name');


        if (addon_published_status('TaxModule') && $category['position'] == 0) {
            $taxVatIds = $category->taxVats()->pluck('tax_id')->toArray() ?? [];
            $newTaxVatIds = array_map('intval', $request['tax_ids'] ?? []);
            sort($newTaxVatIds);
            sort($taxVatIds);
            if ($newTaxVatIds != $taxVatIds) {
                $category->taxVats()->delete();
                $SystemTaxVat = \Modules\TaxModule\Entities\SystemTaxSetup::where('is_active', 1)->where('is_default', 1)->first();
                if ($SystemTaxVat?->tax_type == 'category_wise') {
                    foreach ($request['tax_ids'] ?? [] as $tax_ids) {
                        \Modules\TaxModule\Entities\Taxable::create(
                            [
                                'taxable_type' => 'App\Models\Category',
                                'taxable_id' => $category->id,
                                'system_tax_setup_id' => $SystemTaxVat->id
                                , 'tax_id' => $tax_ids
                            ],
                        );
                    }

                }
            }
        }


        Toastr::success(translate('messages.category_updated_successfully'));
        return redirect()->route('admin.category.add');
    }

    public function tree(Request $request): JsonResponse
    {
        $moduleId = Config::get('module.current_module_id');
        $allCategories = \App\Models\Category::withoutGlobalScope('translate')
            ->when($moduleId, fn ($q) => $q->where('module_id', $moduleId))
            ->orderBy('parent_id')
            ->orderBy('priority', 'desc')
            ->orderBy('id')
            ->get(['id', 'name', 'parent_id', 'position', 'status', 'priority', 'module_id', 'slug']);

        $tree = $this->buildCategoryTree($allCategories);
        return response()->json([
            'max_level' => 4,
            'tree' => $tree,
        ]);
    }

    private function validateHierarchy($parent, ?int $currentId): ?string
    {
        if (!$parent) {
            return 'Parent category not found.';
        }

        // Max 4 levels: parent depth cannot be >= 4.
        $depth = 1;
        $node = $parent;
        $guard = 0;
        while ($node && filled($node->parent_id) && (int) $node->parent_id > 0) {
            if ($currentId && (int) $node->id === $currentId) {
                return 'Invalid parent category (circular hierarchy).';
            }
            $node = \App\Models\Category::withoutGlobalScope('translate')->find((int) $node->parent_id);
            $depth++;
            $guard++;
            if ($guard > 10) {
                break;
            }
        }

        if ($currentId && (int) $parent->id === $currentId) {
            return 'Invalid parent category (cannot set itself as parent).';
        }

        if ($depth >= 4) {
            return 'Max depth is 4 levels. You cannot add a child under a level 4 category.';
        }

        return null;
    }

    private function buildCategoryTree($categories): array
    {
        $items = [];
        foreach ($categories as $category) {
            $items[(int) $category->id] = [
                'id' => (int) $category->id,
                'name' => $category->name,
                'parent_id' => $category->parent_id ? (int) $category->parent_id : null,
                'position' => (int) ($category->position ?? 0),
                'status' => (int) ($category->status ?? 1),
                'priority' => (int) ($category->priority ?? 0),
                'module_id' => (int) ($category->module_id ?? 0),
                'slug' => $category->slug ?? null,
                'children' => [],
            ];
        }

        $tree = [];
        foreach ($items as $id => &$item) {
            $pid = $item['parent_id'];
            if ($pid && isset($items[$pid])) {
                $items[$pid]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }
        unset($item);

        return $tree;
    }

    private function buildCategoryOptions(array $tree, int $depth = 1, array $options = [], array $disabledIds = []): array
    {
        foreach ($tree as $node) {
            $options[] = [
                'id' => $node['id'],
                'label' => str_repeat('— ', max(0, $depth - 1)) . $node['name'],
                'depth' => $depth,
                'disabled' => in_array($node['id'], $disabledIds, true) || $depth >= 4,
            ];
            if (!empty($node['children'])) {
                $options = $this->buildCategoryOptions($node['children'], $depth + 1, $options, $disabledIds);
            }
        }
        return $options;
    }

    private function flattenCategoryTree(array $tree, int $depth = 1, array $rows = []): array
    {
        foreach ($tree as $node) {
            $rows[] = [
                'id' => $node['id'],
                'name' => $node['name'],
                'depth' => $depth,
            ];
            if (!empty($node['children'])) {
                $rows = $this->flattenCategoryTree($node['children'], $depth + 1, $rows);
            }
        }
        return $rows;
    }

    public function delete(Request $request): RedirectResponse
    {

        if ($this->categoryRepo->delete(id: $request['id'])) {
            Toastr::success('Category removed!');
        } else {
            Toastr::warning(translate('messages.remove_sub_categories_first'));
        }
        return back();
    }

    public function getNameList(Request $request): JsonResponse
    {
        $data = $this->categoryRepo->getNameList(request: $request, dataLimit: 8);
        $data[] = (object)['id' => 'all', 'text' => translate('messages.all')];
        return response()->json($data);
    }

    public function updatePriority(Request $request): RedirectResponse
    {
        $this->categoryRepo->update(id: $request['category'], data: ['priority' => $request['priority']]);
        Toastr::success(translate('messages.category_priority_updated successfully'));
        return back();
    }

    public function getBulkImportView(): View
    {
        return view(CategoryViewPath::BULK_IMPORT['view']);
    }

    public function importBulkData(CategoryBulkImportRequest $request): RedirectResponse
    {
        $data = $this->categoryService->getImportData(request: $request);

        if (array_key_exists('flag', $data) && $data['flag'] == 'wrong_format') {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        if (array_key_exists('flag', $data) && $data['flag'] == 'required_fields') {
            Toastr::error(translate('messages.please_fill_all_required_fields'));
            return back();
        }

        try {
            DB::beginTransaction();
            $this->categoryRepo->addByChunk(data: $data);
            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }

        Toastr::success(translate('messages.category_imported_successfully', ['count' => count($data)]));
        return back();
    }

    public function updateBulkData(CategoryBulkImportRequest $request): RedirectResponse
    {
        $data = $this->categoryService->getImportData(request: $request, toAdd: false);

        if (array_key_exists('flag', $data) && $data['flag'] == 'wrong_format') {
            Toastr::error(translate('messages.you_have_uploaded_a_wrong_format_file'));
            return back();
        }

        if (array_key_exists('flag', $data) && $data['flag'] == 'required_fields') {
            Toastr::error(translate('messages.please_fill_all_required_fields'));
            return back();
        }

        try {
            DB::beginTransaction();
            $this->categoryRepo->updateByChunk(data: $data);
            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            Toastr::error(translate('messages.failed_to_import_data'));
            return back();
        }

        Toastr::success(translate('messages.category_updated_successfully', ['count' => count($data)]));
        return back();
    }

    public function getBulkExportView(): View
    {
        return view(CategoryViewPath::BULK_EXPORT['view']);
    }

    /**
     * @throws IOException
     * @throws WriterNotOpenedException
     * @throws UnsupportedTypeException
     * @throws InvalidArgumentException
     */
    public function exportBulkData(CategoryBulkExportRequest $request): StreamedResponse|string
    {
        $categories = $this->categoryRepo->getBulkExportList(request: $request);
        return (new FastExcel($this->categoryService->getExportData(collection: $this->exportGenerator(data: $categories))))->download(Category::EXPORT_XLSX);
    }

    public function exportList(Request $request): BinaryFileResponse
    {
        $categories = $this->categoryRepo->getExportList(request: $request);

        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];

        $data = [
            'data' => $categories,
            'search' => $request['search'] ?? null,
            'categoryWiseTax' => $categoryWiseTax
        ];

        if ($request['type'] == 'csv') {
            return Excel::download(new CategoryExport($data), Category::EXPORT_CSV);
        }
        return Excel::download(new CategoryExport($data), Category::EXPORT_XLSX);
    }
}
