<?php

namespace Modules\Category\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Category\App\Http\Requests\CategoryCreateRequest;
use Modules\Category\App\Http\Requests\CategoryUpdateRequest;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Services\CategoryService;
use Modules\ServiceType\Services\ServiceTypes;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        if($request->wantsJson() || $request->expectsJson()) {
            return $this->categoryService->getDataTable($request);
        }
        return response(view('category::index')->with(['title' => 'Categories']));
    }

    public function create()
    {
        return response(view('category::create', [
            'service_types' => app(ServiceTypes::class)->all(),
            'parents' => $this->categoryService->all()->where('parent', 0),
            'colors' => config('category.colors')
        ])->with(['title' => 'Add new category']));
    }

    public function store(CategoryCreateRequest $request): RedirectResponse
    {
        return $this->categoryService->create($request->validated());
    }

    public function show(Category $category): Response
    {
        return response(view('category::show', compact('category'))
            ->with(['title' => 'View category']));
    }

    public function edit(Category $category)
    {
        return response(view('category::edit', [
            'service_types' => app(ServiceTypes::class)->all(),
            'parents' => $this->categoryService->all()->where('parent', 0),
            'category' => $category,
            'colors' => config('category.colors')
        ])->with(['title' => 'Update category']));
    }

    public function update(CategoryUpdateRequest $request, $id): RedirectResponse
    {
        return $this->categoryService->update($request->validated(), $id);
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $category->delete();
            return redirect()->back()->with(['status' => true, 'message' => __('Category successfully deleted')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
        }
        return redirect()->back()->with(['status' => false, 'message' => __('Category failed to delete')]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        return $this->categoryService->getSuggestions($request);
    }

    public function callAction($method, $parameters): Response
    {
        if(!Arr::except(['suggestions'], $method)) {
            $this->authorize($method, Category::class);
        }

        return parent::callAction($method, $parameters);
    }
}
