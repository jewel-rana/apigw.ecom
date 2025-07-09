<?php

namespace Modules\Category\App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Category\App\Http\Requests\CategoryCreateRequest;
use Modules\Category\App\Http\Requests\CategoryUpdateRequest;
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

    public function index(Request $request)
    {
        return response()->success(
            $this->categoryService->all($request)->where('parent', 0)
                ->sortBy($request->input('sort', 'position'), SORT_REGULAR, $request->input('order', 'ASC'))
                ->map(function (Category $item, $key) {
                    return $item->format() + [
                            'icon' => $item->media_attachment_url
                        ];
                })->values()
        );
    }

    public function store(CategoryCreateRequest $request)
    {
        return $this->categoryService->create($request->validated());
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

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        return $this->categoryService->update($request->validated(), $category->id);
    }

    public function destroy(Category $category)
    {
        return $this->categoryService->delete($category);
    }


    public function suggestions(Request $request)
    {
        return $this->categoryService->suggestions($request);
    }
}
