<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\CategoryDiscount;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Exports\StoreCategoryExport;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Exports\StoreSubCategoryExport;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;
        $moduleId = (int) $store->module_id;
        $categories=Category::where(['position'=>0])->module($store->module_id)
        ->when(isset($key) , function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->paginate(config('default_pagination'));

        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];
        $mainCategoriesCount = Category::where(['position' => 0])->module($moduleId)->count();
        $subCategoriesCount = Category::where(['position' => 1])->module($moduleId)->count();
        $totalCategoriesCount = Category::query()->module($moduleId)->count();
        $activeCategoriesCount = Category::query()->module($moduleId)->where('status', 1)->count();

        $categoryDiscounts = CategoryDiscount::query()
            ->with(['category:id,name'])
            ->where('store_id', (int) $store->id)
            ->latest('id')
            ->take(8)
            ->get();
        $categoryDiscountsCount = CategoryDiscount::query()
            ->where('store_id', (int) $store->id)
            ->count();

        // RMS table enrichments (subcats/products/sales/discount per main category)
        try {
            $parentIds = $categories->getCollection()->pluck('id')->values();

            $subCounts = Category::query()
                ->whereIn('parent_id', $parentIds)
                ->selectRaw('parent_id, COUNT(*) as cnt')
                ->groupBy('parent_id')
                ->pluck('cnt', 'parent_id');

            $childIdsByParent = Category::query()
                ->whereIn('parent_id', $parentIds)
                ->get(['id', 'parent_id'])
                ->groupBy('parent_id')
                ->map(fn ($rows) => $rows->pluck('id')->all());

            $allCategoryIds = $parentIds->merge($childIdsByParent->flatten())->unique()->values();

            $productCountsByCat = Item::query()
                ->where('store_id', $storeId)
                ->whereIn('category_id', $allCategoryIds)
                ->selectRaw('category_id, COUNT(*) as cnt')
                ->groupBy('category_id')
                ->pluck('cnt', 'category_id');

            $salesByCat = DB::table('order_details')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->join('items', 'items.id', '=', 'order_details.item_id')
                ->where('orders.store_id', $storeId)
                ->selectRaw('items.category_id as category_id, SUM(order_details.price * order_details.quantity) as total')
                ->groupBy('items.category_id')
                ->pluck('total', 'category_id');

            $discountByCat = CategoryDiscount::query()
                ->where('store_id', $storeId)
                ->whereIn('category_id', $parentIds)
                ->get()
                ->keyBy('category_id');

            $enriched = $categories->getCollection()->map(function ($cat) use ($subCounts, $childIdsByParent, $productCountsByCat, $salesByCat, $discountByCat) {
                $childIds = collect($childIdsByParent->get($cat->id, []));
                $ids = $childIds->merge([$cat->id])->unique();

                $products = (int) $ids->sum(fn ($id) => (int) ($productCountsByCat[$id] ?? 0));
                $sales = (float) $ids->sum(fn ($id) => (float) ($salesByCat[$id] ?? 0));
                $disc = $discountByCat->get($cat->id);

                $cat->mf_subcats = (int) ($subCounts[$cat->id] ?? 0);
                $cat->mf_products = $products;
                $cat->mf_sales = $sales;
                $cat->mf_discount = $disc;
                return $cat;
            });

            $categories->setCollection($enriched);
        } catch (\Throwable $e) {
            // non-blocking; keep legacy table working
        }

        return view('vendor-views.category.index', compact(
            'categories',
            'categoryWiseTax',
            'mainCategoriesCount',
            'subCategoriesCount',
            'totalCategoriesCount',
            'activeCategoriesCount',
            'categoryDiscounts',
            'categoryDiscountsCount'
        ));
    }

    public function discounts_index(Request $request)
    {
        $store = Helpers::get_store_data();
        $moduleId = (int) $store->module_id;
        $storeId = (int) $store->id;

        $discounts = CategoryDiscount::query()
            ->with(['category:id,name,parent_id', 'store:id,name'])
            ->where('store_id', $storeId)
            ->latest('id')
            ->paginate(config('default_pagination'));

        $categories = Category::query()
            ->where('position', 0)
            ->module($moduleId)
            ->active()
            ->orderBy('priority')
            ->orderBy('id')
            ->get(['id', 'name', 'parent_id']);

        return view('vendor-views.category.discounts', compact('discounts', 'categories'));
    }

    public function discounts_store(Request $request)
    {
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::unique('category_discounts', 'category_id')->where(fn ($q) => $q->where('store_id', $storeId)),
            ],
            'discount_type' => ['required', Rule::in(['percent', 'amount'])],
            'discount' => ['required', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (($validated['discount_type'] ?? 'percent') === 'percent' && (float) ($validated['discount'] ?? 0) > 100) {
            Toastr::error(translate('messages.invalid_discount_amount'));
            return back()->withInput();
        }

        CategoryDiscount::create([
            'store_id' => $storeId,
            'category_id' => (int) $validated['category_id'],
            'discount_type' => $validated['discount_type'],
            'discount' => (float) $validated['discount'],
            'max_discount' => $validated['max_discount'] !== null ? (float) $validated['max_discount'] : null,
            'status' => (bool) ($validated['status'] ?? true),
        ]);

        Toastr::success(translate('messages.added_successfully'));
        return redirect()->route('vendor.category.discounts.index');
    }

    public function discounts_edit(CategoryDiscount $discount)
    {
        $storeId = (int) Helpers::get_store_id();
        abort_unless((int) $discount->store_id === $storeId, 404);

        $store = Helpers::get_store_data();
        $moduleId = (int) $store->module_id;
        $categories = Category::query()
            ->where('position', 0)
            ->module($moduleId)
            ->active()
            ->orderBy('priority')
            ->orderBy('id')
            ->get(['id', 'name', 'parent_id']);

        return view('vendor-views.category.discount-edit', compact('discount', 'categories'));
    }

    public function discounts_update(Request $request, CategoryDiscount $discount)
    {
        $storeId = (int) Helpers::get_store_id();
        abort_unless((int) $discount->store_id === $storeId, 404);

        $validated = $request->validate([
            'category_id' => [
                'required',
                'integer',
                Rule::unique('category_discounts', 'category_id')
                    ->where(fn ($q) => $q->where('store_id', $storeId))
                    ->ignore($discount->id),
            ],
            'discount_type' => ['required', Rule::in(['percent', 'amount'])],
            'discount' => ['required', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        if (($validated['discount_type'] ?? 'percent') === 'percent' && (float) ($validated['discount'] ?? 0) > 100) {
            Toastr::error(translate('messages.invalid_discount_amount'));
            return back()->withInput();
        }

        $discount->update([
            'category_id' => (int) $validated['category_id'],
            'discount_type' => $validated['discount_type'],
            'discount' => (float) $validated['discount'],
            'max_discount' => $validated['max_discount'] !== null ? (float) $validated['max_discount'] : null,
            'status' => (bool) ($validated['status'] ?? false),
        ]);

        Toastr::success(translate('messages.updated_successfully'));
        return redirect()->route('vendor.category.discounts.index');
    }

    public function discounts_destroy(CategoryDiscount $discount)
    {
        $storeId = (int) Helpers::get_store_id();
        abort_unless((int) $discount->store_id === $storeId, 404);

        $discount->delete();
        Toastr::success(translate('messages.deleted_successfully'));
        return back();
    }

    public function get_all(Request $request){
        $data = Category::where('name', 'like', '%'.$request->q.'%')->module(Helpers::get_store_data()->module_id)->limit(8)->get([DB::raw('id, CONCAT(name, " (", if(position = 0, "'.translate('messages.main').'", "'.translate('messages.sub').'"),")") as text')]);
        if(isset($request->all))
        {
            $data[]=(object)['id'=>'all', 'text'=>translate('messages.all')];
        }
        return response()->json($data);
    }

    function sub_index(Request $request)
    {
        $key = explode(' ', $request['search']);
        $store = Helpers::get_store_data();
        $storeId = (int) $store->id;
        $moduleId = (int) $store->module_id;
        $categories=Category::with(['parent'])
        ->whereHas('parent',function($query){
            $query->module(Helpers::get_store_data()->module_id);
        })
        ->where(['position'=>1])
        ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
        ->latest()->paginate(config('default_pagination'));
        $moduleId = Helpers::get_store_data()->module_id;
        $mainCategoriesCount = Category::where(['position' => 0])->module($moduleId)->count();
        $subCategoriesCount = Category::where(['position' => 1])->module($moduleId)->count();

        // RMS enrich (products + sales)
        try {
            $subIds = $categories->getCollection()->pluck('id')->values();
            $productCountsByCat = Item::query()
                ->where('store_id', $storeId)
                ->whereIn('category_id', $subIds)
                ->selectRaw('category_id, COUNT(*) as cnt')
                ->groupBy('category_id')
                ->pluck('cnt', 'category_id');

            $salesByCat = DB::table('order_details')
                ->join('orders', 'orders.id', '=', 'order_details.order_id')
                ->join('items', 'items.id', '=', 'order_details.item_id')
                ->where('orders.store_id', $storeId)
                ->selectRaw('items.category_id as category_id, SUM(order_details.price * order_details.quantity) as total')
                ->whereIn('items.category_id', $subIds)
                ->groupBy('items.category_id')
                ->pluck('total', 'category_id');

            $enriched = $categories->getCollection()->map(function ($cat) use ($productCountsByCat, $salesByCat) {
                $cat->mf_products = (int) ($productCountsByCat[$cat->id] ?? 0);
                $cat->mf_sales = (float) ($salesByCat[$cat->id] ?? 0);
                return $cat;
            });
            $categories->setCollection($enriched);
        } catch (\Throwable $e) {
            // non-blocking
        }

        return view('vendor-views.category.sub-index', compact(
            'categories',
            'mainCategoriesCount',
            'subCategoriesCount'
        ));
    }

    public function edit(Category $category)
    {
        $store = Helpers::get_store_data();
        abort_unless((int) $category->module_id === (int) $store->module_id, 404);
        abort_unless((int) $category->position === 0, 404);

        return view('vendor-views.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $store = Helpers::get_store_data();
        abort_unless((int) $category->module_id === (int) $store->module_id, 404);
        abort_unless((int) $category->position === 0, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'status' => ['nullable', 'boolean'],
        ]);

        $category->name = $validated['name'];
        $category->status = (int) ((bool) ($validated['status'] ?? false));
        $category->slug = Str::slug($category->name);
        $category->save();

        Toastr::success(translate('messages.updated_successfully'));
        return redirect()->route('vendor.category.add');
    }

    // public function search(Request $request){
    //     $key = explode(' ', $request['search']);
    //     $categories=Category::where(['position'=>0])
    //     ->module(Helpers::get_store_data()->module_id)
    //     ->where(function ($q) use ($key) {
    //         foreach ($key as $value) {
    //             $q->orWhere('name', 'like', "%{$value}%");
    //         }
    //     })
    //     ->latest()
    //     ->limit(50)->get();
    //     return response()->json([
    //         'view'=>view('vendor-views.category.partials._table',compact('categories'))->render(),
    //         'count'=>$categories->count()
    //     ]);
    // }

//    public function sub_search(Request $request){
//        $key = explode(' ', $request['search']);
//        $categories=Category::with(['parent'])
//        ->where(function ($q) use ($key) {
//            foreach ($key as $value) {
//                $q->orWhere('name', 'like', "%{$value}%");
//            }
//        })
//        ->where(['position'=>1])->limit(50)->get();
//
//        return response()->json([
//            'view'=>view('vendor-views.category.partials._sub_table',compact('categories'))->render(),
//            'count'=>$categories->count()
//        ]);
//    }

    public function export_categories(Request $request){
        $key = explode(' ', $request['search']);
        $categories=Category::where(['position'=>0])->module(Helpers::get_store_data()->module_id)
        ->when(isset($key) , function($q) use($key){
            $q->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        })
        ->latest()->get();

        $taxData = Helpers::getTaxSystemType();
        $categoryWiseTax = $taxData['categoryWiseTax'];
        $data=[
            'data' =>$categories,
            'search' =>$request['search'] ?? null,
            'categoryWiseTax' => $categoryWiseTax
        ];
        if($request->type == 'csv'){
            return Excel::download(new StoreCategoryExport($data), 'Categories.csv');
        }
        return Excel::download(new StoreCategoryExport($data), 'Categories.xlsx');


    }

    public function export_sub_categories(Request $request){
        $key = explode(' ', $request['search']);
        $categories=Category::with(['parent'])
        ->whereHas('parent',function($query){
            $query->module(Helpers::get_store_data()->module_id);
        })
        ->where(['position'=>1])
        ->when(isset($key) , function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->get();

            $data=[
                'data' =>$categories,
                'search' =>$request['search'] ?? null,
            ];
            if($request->type == 'csv'){
                return Excel::download(new StoreSubCategoryExport($data), 'SubCategories.csv');
            }
            return Excel::download(new StoreSubCategoryExport($data), 'SubCategories.xlsx');

    }
}
