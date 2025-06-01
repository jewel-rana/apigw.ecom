<?php

namespace Modules\Category\App\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Brand\Entities\Brand;
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

    public function store(Request $request)
    {
        return $this->categoryService->create($request->all());
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
                $category->format(true)
            );
        } catch (Throwable|\Exception $exception) {
            return response()->failed();
        }
    }

    public function update(Request $request, Category $category)
    {
        return $this->categoryService->update($request->all(), $category->id);
    }

    public function destroy(Category $category)
    {
        return $this->categoryService->delete($category);
    }


    public function suggestions(Request $request): JsonResponse
    {
        return $this->categoryService->suggestions($request);
    }
}
