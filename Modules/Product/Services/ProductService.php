<?php

namespace Modules\Product\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Modules\CMS\App\Models\Feature;
use Modules\Product\Entities\Product;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Jobs\ProductMediaUploadJob;
use Modules\Product\Repositories\Interfaces\ProductRepositoryInterface;

class ProductService
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function all(Request $request)
    {
        return Cache::remember('brands', 3600, function () use ($request) {
            return $this->productRepository->all();
        });
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->getModel()
            ->filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($products));
    }

    public function suggestions(Request $request)
    {
        try {
            return response()->success(
                $this->productRepository->getModel()
                    ->filter($request)
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

    public function create(StoreProductRequest $request)
    {
        try {
            $product = $this->productRepository->create($request->validated());

            if ($request->filled('tags')) {
                foreach ($request->input('tags') as $tag) {
                    $product->tags()->create([
                        'name' => $tag,
                        'slug' => Str::slug($tag)
                    ]);
                }
            }

            if ($request->has('attachments') && is_array($request->input('attachments'))) {
                foreach ($request->input('attachments') as $attachment) {
                    ProductMediaUploadJob::dispatch($product, $attachment, false);
                }
            }

            if ($request->hasFile('thumbnail')) {
                ProductMediaUploadJob::dispatch($product, $request->file('thumbnail'), true);
            }

            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $this->productRepository->update($request->validated(), $product->id);
            if ($request->filled('tags')) {
                foreach ($request->input('tags') as $tag) {
                    $product->tags()->create([
                        'name' => $tag,
                        'slug' => Str::slug($tag)
                    ]);
                }
            }

            if ($request->hasFile('attachments')) {
                $attachments = $request->attachments;
                if (is_array($attachments)) {
                    foreach ($attachments as $attachment) {
                        ProductMediaUploadJob::dispatch($product, $attachment, false);
                    }
                } else {
                    ProductMediaUploadJob::dispatch($product, $attachments, false);
                }
            }

            if ($request->hasFile('thumbnail')) {
                ProductMediaUploadJob::dispatch($product, $request->input('thumbnail'), true);
            }
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function delete(Product $product)
    {
        try {
            $product->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_DELETE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function featureProducts(Feature $feature, Request $request, $paginate = false)
    {
        return $this->{$feature->type}($feature, $request, $paginate);
    }

    public function category(Feature $feature, Request $request, $paginate = false)
    {
        $query = $this->productRepository->getModel()
            ->where('category_id', $feature->model_id)
//            ->where('is_featured', true)
            ->filter($request)
            ->latest();

        if ($paginate) {
            return CommonHelper::parsePaginator($query->paginate(CommonHelper::perPage($request)));
        }

        return $query->limit($feature->limit)
            ->get()
            ->map(function ($product) {
                return $product->format();
            });
    }

    public function brand(Feature $feature, Request $request, $paginate = false)
    {
        $query = $this->productRepository->getModel()
            ->where('brand_id', $feature->model_id)
            ->where('is_featured', true)
            ->filter($request)
            ->latest();

        if ($paginate) {
            return CommonHelper::parsePaginator($query->paginate(CommonHelper::perPage($request)));
        }

        return $query->limit($feature->limit)
            ->get()
            ->map(function ($product) {
                return $product->format();
            });
    }

    public function tag(Feature $feature, Request $request, $paginate = false)
    {
        $query = $this->productRepository->getModel()
            ->where('is_featured', true)
            ->filter($request)
            ->whereHas('tags', function ($query) use ($feature) {
                $query->where('name', $feature->model_id);
            })
            ->latest();

        if ($paginate) {
            return CommonHelper::parsePaginator($query->paginate(CommonHelper::perPage($request)));
        }

        return $query->limit($feature->limit)
            ->get()
            ->map(function ($product) {
                return $product->format();
            });
    }

    public function product(Feature $feature, Request $request, $paginate = false)
    {
        $query = $feature->products();

        if ($paginate) {
            return CommonHelper::parsePaginator($query->paginate(CommonHelper::perPage($request)));
        }

        return $query->limit($feature->limit)
            ->get()
            ->map(function ($product) {
                return $product->format();
            });
    }
}
