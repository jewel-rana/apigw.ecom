<?php

namespace Modules\Category\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Services\CategoryService;

class CategoryProductController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        return response()->success(
            $this->categoryService->all($request)
                ->map(function ($category) {
                    return $category->only(['id', 'name', 'slug', 'icon']) +
                        [
                            'children' => $category->children->map(function ($category) {
                                return $category->only(['id', 'name', 'slug', 'icon']);
                            })
                        ];
                })
        );
    }

    public function show(Request $request, Category $category)
    {
        try {
            $products = app(CategoryService::class)->categoryProducts($category, $request);
            return response()->success(
                $category->only(['id', 'name', 'slug', 'icon']) +
                [
                    'products' => $products
                ]
            );
        } catch (\Throwable $th) {
            return response()->failed(['message' => $th->getMessage()]);
        }
    }
}
