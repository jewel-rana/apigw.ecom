<?php

namespace Modules\Category\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Services\CategoryService;
use Throwable;

class CategoryController extends Controller
{
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return response()->success(
            $this->categoryService->all()->where('parent', 0)
                ->map(function (Category $item, $key) {
                    return $item->format() + [
                            'icon' => $item->media_attachment_url
                        ];
                })->values()
        );
    }

    public function show(Request $request, $slug)
    {
        try {
            if(is_numeric($slug)) {
                $category = Category::findOrFail($slug);
            } else {
                $category = Category::where('code', $slug)->first();
            }
            return response()->success(
                $category->format(true) +
                [
                    'products' => $this->categoryService->getOperators($category->id, $request)
                ]
            );
        } catch (Throwable|\Exception $exception) {
            return response()->failed();
        }
    }
}
