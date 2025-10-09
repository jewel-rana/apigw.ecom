<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductWishList;
use Modules\Product\Http\Requests\StoreProductRequest;
use Modules\Product\Http\Requests\UpdateProductRequest;
use Modules\Product\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        return $this->productService->index($request);
    }

    public function show(Product $product)
    {
        return response()->success($product->format(true));
    }

    public function store(StoreProductRequest $request)
    {
        return $this->productService->create($request);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        return $this->productService->update($request, $product);
    }

    public function destroy(Product $product)
    {
        return $this->productService->delete($product);
    }

    public function wishlist(Request $request)
    {
        return $this->productService->getWishlistedProducts($request);
    }

    public function suggestions(Request $request)
    {
        return $this->productService->suggestions($request);
    }

    public function removeMedia(Product $product, $mediaId)
    {
        try {
            DB::transaction(function () use ($product, $mediaId) {
                $media = $product->medias()->where('media_id', $mediaId)->first();
                $media->delete();
                $product->medias()->detach($mediaId);
            });
            return response()->success();
        } catch (\Exception $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }
}
