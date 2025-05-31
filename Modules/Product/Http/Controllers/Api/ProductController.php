<?php

namespace Modules\Product\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
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

    public function store(Request $request)
    {
        return $this->productService->create($request);
    }

    public function update(Request $request, Product $product)
    {
        return $this->productService->update($request, $product);
    }

    public function destroy(Product $product)
    {
        return $this->productService->delete($product);
    }
}
