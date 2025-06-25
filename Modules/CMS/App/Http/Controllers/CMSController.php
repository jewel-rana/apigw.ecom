<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMS\App\Models\Feature;
use Modules\CMS\App\Services\BannerService;
use Modules\CMS\App\Services\CmsService;
use Modules\Product\Services\ProductService;
use Modules\Setting\OptionService;

class CMSController extends Controller
{
    public function index(Request $request)
    {
//        $results = Cache::remember('cms.initialize', 600, function () use ($request) {
        $data = [
            'banners' => app(BannerService::class)->cms(),
            'options' => app(OptionService::class)->cms(),
            'recommendations' => app(CmsService::class)->recommended($request)
        ];

//            return $data;
//        });

        return \response()->success($data);
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

    public function featureProducts(Request $request, Feature $feature)
    {
        return response()->success(
            app(ProductService::class)
                ->featureProducts($feature, $request)
        );
    }
}
