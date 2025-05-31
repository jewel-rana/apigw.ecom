<?php

namespace Modules\Brand\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Brand\Entities\Brand;

class BrandService
{
    public function all(Request $request)
    {
        return Cache::remember('brands', 3600, function () use ($request) {
            return Brand::filter($request)->all();
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
}
