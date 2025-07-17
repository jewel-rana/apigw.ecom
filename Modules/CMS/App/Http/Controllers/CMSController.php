<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Category\App\Services\CategoryService;
use Modules\CMS\App\Models\Feature;
use Modules\CMS\App\Services\CmsService;
use Modules\Product\Services\ProductService;
use Modules\Setting\OptionService;
use Modules\Banner\Services\BannerService;

class CMSController extends Controller
{
    public function index(Request $request)
    {
        Cache::forget('cms.initialize');
        $results = Cache::remember('cms.initialize', 600, function () use ($request) {
            return [
                'banners' => app(BannerService::class)->cms(),
                'categories' => app(CategoryService::class)->cms(),
                'options' => app(OptionService::class)->cms(),
                'features' => app(CmsService::class)->featured($request),
                'cards' => app(CmsService::class)->homeCards($request)
            ];
        });

        $results['recommendations'] = app(CmsService::class)->recommended($request);

        return response()->success($results);
    }

    public function search(Request $request)
    {
        return \response()->success(
            app(CmsService::class)->search($request)
        );
    }

    public function recommendations(Request $request)
    {
        return \response()->success(
            app(CmsService::class)->recommended($request)
        );
    }

    public function featureProducts(Request $request, $featureId)
    {
        try {
            $feature = Feature::find($featureId);
            if (!$feature) {
                return response()->failed(['message' => 'Feature not found']);
            }

            return response()->success([
                    'id' => $feature->id,
                    'title' => $feature->title,
                    'description' => $feature->description,
                    'products' => app(ProductService::class)->featureProducts($feature, $request, true)
                ]
            );
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'keyword' => 'FEATURE_PRODUCT_EXCEPTION'
            ]);
            return response()->failed(['message' => $e->getMessage()]);
        }
    }
}
