<?php

namespace Modules\Brand\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Brand\Entities\Brand;
use Modules\Product\Entities\Product;

class BrandService
{
    public function all(Request $request)
    {
        return Cache::remember('brands', 3600, function () use ($request) {
            return Brand::filter($request)->get();
        });
    }

    public function index(Request $request)
    {
        $agents = Brand::filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($agents));
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

    public function create(Request $request)
    {
        try {
            Brand::create($request->all());
            Cache::forget('brands');
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function update(Request $request, Brand $brand)
    {
        try {
            $brand->update($request->all());
            Cache::forget('brands');
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function delete(Brand $brand)
    {
        try {
            $brand->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_DELETE_EXCEPTION'
            ]);
            return  response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function brandProducts(Brand $brand, Request $request): array
    {
        return CommonHelper::parsePaginator(
            Product::where('category_id', $brand->id)
                ->filter($request)
                ->paginate(CommonHelper::perPage($request))
        );
    }
}
