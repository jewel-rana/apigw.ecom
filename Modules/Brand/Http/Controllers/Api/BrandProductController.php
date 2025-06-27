<?php

namespace Modules\Brand\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Services\BrandService;

class BrandProductController extends Controller
{
    private BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        return response()->success(
            $this->brandService->all($request)
                ->map(function ($brand) {
                    return $brand->only(['id', 'name', 'slug', 'icon']);
                })
        );
    }

    public function show(Brand $brand, Request $request)
    {
        try {
            $products = app(BrandService::class)->brandProducts($brand, $request);
            return response()->success(
                $brand->only(['id', 'name', 'slug', 'icon']) +
                [
                    'products' => $products
                ]
            );
        } catch (\Throwable $th) {
            return response()->failed(['message' => $th->getMessage()]);
        }
    }
}
