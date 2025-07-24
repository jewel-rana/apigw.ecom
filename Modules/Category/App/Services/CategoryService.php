<?php

namespace Modules\Category\App\Services;

use App\Constants\AppConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Category\App\Jobs\CategoryMediaUploadJob;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Media\MediaService;
use Modules\Product\Entities\Product;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepository;
    private MediaService $mediaService;

    public function __construct(CategoryRepositoryInterface $categoryRepository, MediaService $mediaService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->mediaService = $mediaService;
    }

    public function all(Request $request)
    {
        return Cache::remember('categories', 3600, function () {
            return $this->categoryRepository->getModel()->orderBy('position', 'asc')->get();
        });
    }

    public function get($id)
    {
        return $this->categoryRepository->show($id);
    }

    public function create(array $data)
    {
        try {
            DB::transaction(function () use ($data) {
                $category = $this->categoryRepository->create($data);
                CategoryMediaUploadJob::dispatch($category, $this->mediaService);
            });
            if (request()->wantsJson()) {
                return response()->success();
            }
            return redirect()->route('category.index')->with(['status' => true, 'message' => __('Category created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);

            if (request()->wantsJson()) {
                return response()->failed(['message' => $exception->getMessage()]);
            }
            return redirect()->back()->withInput($data)->with(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function update(array $data, int $id)
    {
        try {
            DB::transaction(function () use ($data, $id) {
                $category = $this->categoryRepository->update($data, $id);
                CategoryMediaUploadJob::dispatch($category, $this->mediaService);
            });

            if (request()->wantsJson()) {
                return response()->success();
            }
            return redirect()->route('category.index')->with(['status' => true, 'message' => __('Category updated successfully')]);;
        } catch (\Exception $exception) {
            LogHelper::exception($exception);

            if (request()->wantsJson()) {
                return response()->failed(['message' => $exception->getMessage()]);
            }
            return redirect()->back()->with(['status' => false, 'message' => __('Category failed to update')]);
        }
    }

    public function getDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            $this->categoryRepository->with(['parent', 'serviceType'])->filter($request)
        )
            ->addColumn('parent', function (Category $category) {
                return $category->parent->name ?? '---';
            })
            ->addColumn('icon', function (Category $category) {
                return "<img src='{$category->media_attachment_url}' class='table-avatar' />";
            })
            ->addColumn('actions', function (Category $category) {
                $str = '';
                if (CommonHelper::hasPermission(['category-show'])) {
                    $str .= '<a class="btn btn-default" href = "' . route('category.show', $category->id) . '" ><i class="fa fa-eye" ></i ></a>';
                }
                if (CommonHelper::hasPermission(['category-update'])) {
                    $str .= '<a class="btn btn-default" href = "' . route('category.edit', $category->id) . '" ><i class="fa fa-edit" ></i ></a >';
                }
                if (CommonHelper::hasPermission(['category-action'])) {
//                    $str .= '<button class="btn btn-danger delete" data-action="' . route('category.destroy', $category->id). '"><i class="fa fa-times" ></i ></button>';
                }
                return $str;
            })
            ->rawColumns(['actions', 'icon'])
            ->toJson();
    }

    public function suggestions(Request $request)
    {
        try {
            return response()->success(
                $this->all($request)
                    ->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'name' => $item->name,
                        ];
                    })
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'AGENT_NOT_FOUND_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $this->all($request)->filter(function ($category) use ($request) {
                $matched = true;
                if ($request->filled('term')) {
                    $matched = CommonHelper::matchText($category->name, $request->input('term'));
                }

                if ($request->filled('service_type_id')) {
                    $matched = $category->service_type_id == $request->input('service_type_id') && $matched;
                }

                return $matched;
            })
                ->map(function ($category, $key) {
                    return [
                        'id' => $category->id,
                        'text' => $category->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => __('No data!'), 'results' => []]);
        }
    }

    public function delete(Category $category)
    {
        try {
            $category->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_DELETE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function categoryProducts(Category $category, Request $request): array
    {
        return CommonHelper::parsePaginator(
            Product::where('category_id', $category->id)
                ->filter($request)
                ->paginate(CommonHelper::perPage($request))
        );
    }

    public function index(Request $request)
    {
        $categories = $this->categoryRepository->getModel()->paginate($request->input('per_page', 10));
        return response()->success(
            CommonHelper::parsePaginator($categories)
        );
    }

    public function cms()
    {
        return $this->all(request())
            ->filter(function (Category $banner) {
                return $banner->status === AppConstant::ACTIVE;
            })
            ->map(function (Category $category) {
                return $category->format();
            })
            ->values();
    }
}
