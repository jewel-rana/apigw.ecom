<?php

namespace Modules\Category\App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Bundle\App\Constant\BundleConstant;
use Modules\Bundle\Repositories\BundleRepository;
use Modules\Category\App\Jobs\CategoryMediaUploadJob;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Repositories\Interfaces\CategoryRepositoryInterface;
use Modules\Media\MediaService;
use Modules\Operator\Repositories\Interfaces\OperatorRepositoryInterface;
use Modules\Operator\Services\OperatorService;

class CategoryService
{
    private CategoryRepositoryInterface $categoryRepository;
    private MediaService $mediaService;

    public function __construct(CategoryRepositoryInterface $categoryRepository, MediaService $mediaService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->mediaService = $mediaService;
    }

    public function all()
    {
        return Cache::remember('categories', 3600, function () {
            return $this->categoryRepository->all()->sortBy('position');
        });
    }

    public function get($id)
    {
        return $this->categoryRepository->show($id);
    }

    public function create(array $data): RedirectResponse
    {
        try {
            DB::transaction(function () use ($data) {
                $category = $this->categoryRepository->create($data);
                CategoryMediaUploadJob::dispatch($category, $this->mediaService);
            });
            return redirect()->route('category.index')->with(['status' => true, 'message' => __('Category created successfully')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->withInput($data)->with(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function update(array $data, int $id): RedirectResponse
    {
        try {
            DB::transaction(function () use ($data, $id) {
                $category = $this->categoryRepository->update($data, $id);
                CategoryMediaUploadJob::dispatch($category, $this->mediaService);
            });
            return redirect()->route('category.index')->with(['status' => true, 'message' => __('Category updated successfully')]);;
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
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

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $this->all()->filter(function ($category) use ($request) {
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

    public function getOperators($id, $request): ?array
    {
        try {
            if ($request->input('type', 'bundle') == 'bundle') {
                $operatorIds = app(OperatorService::class)->getCategoryOperatorIds($id);
                $query = app(BundleRepository::class)->getModel()
                    ->whereIn('operator_id', $operatorIds)
                    ->filter($request, true);
            } else {
                $query = app(OperatorRepositoryInterface::class)->getModel()
                    ->where('category_id', $id)
                    ->filter($request, true);
                if(CommonHelper::isRestrictedCustomers()) {
                    $query->whereNotIn('service_type_id', config('customer.restricted.service_type_ids'));
                }
            }
            return CommonHelper::parsePaginator(
                $query->orderBy('position')->paginate(10)
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return null;
        }
    }
}
