<?php

namespace Modules\Product\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Entities\Product;
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

    public function create(Request $request)
    {
        try {
            $this->productRepository->create($request->all());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            $product->update($request->all());
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
}
