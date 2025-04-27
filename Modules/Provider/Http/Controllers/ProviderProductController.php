<?php

namespace Modules\Provider\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Provider\Http\Requests\ProductTagRequest;
use Modules\Provider\Services\ProviderProductService;

class ProviderProductController extends Controller
{
    private ProviderProductService $productService;

    public function __construct(ProviderProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->productService->getDatatable($request);
        }
        return view('provider::product.index');
    }

    public function store(ProductTagRequest $request)
    {
        return $this->productService->create($request);
    }

    public function destroy(Request $request, $id)
    {
        return $this->productService->delete($request);
    }
}
