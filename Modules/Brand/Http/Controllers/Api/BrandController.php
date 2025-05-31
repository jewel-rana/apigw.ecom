<?php

namespace Modules\Brand\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Services\BrandService;

class BrandController extends Controller
{
    private BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        return $this->brandService->index($request);
    }

    public function store(Request $request)
    {
        return $this->brandService->create($request);
    }

    public function show(Brand $brand)
    {
        return response()->success($brand->format);
    }

    public function update(Request $request, Brand $brand)
    {
        return $this->brandService->update($request, $brand);
    }

    public function destroy(Brand $brand)
    {
        return $this->brandService->delete($brand);
    }

    public function suggestions(Request $request)
    {
        return $this->brandService->suggestions($request);
    }
}
