<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
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
        return response()->success($product->format());
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
}
