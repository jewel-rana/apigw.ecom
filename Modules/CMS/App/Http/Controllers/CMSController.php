<?php

namespace Modules\CMS\App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Bundle\Services\BundleService;
use Modules\CMS\App\Services\BannerService;
use Modules\CMS\App\Services\CmsService;
use Modules\Menu\MenuService;
use Modules\Region\Services\LanguageService;
use Modules\Setting\OptionService;

class CMSController extends Controller
{
    public function index(Request $request)
    {
//        $results = Cache::remember('cms.initialize', 600, function () use ($request) {
        $data = [
            'banners' => app(BannerService::class)->cms(),

            'menus' => app(MenuService::class)->cms(),

            'options' => app(OptionService::class)->cms(),

            'section2_menus' => app(MenuService::class)
                ->getSectionMenu(getOption('section2_menu_id', 'section2_menu')),

            'section3_menus' => app(MenuService::class)
                ->getSectionMenu(getOption('section3_menu_id', 'section3_menu')),

            'section4_products' => app(BundleService::class)
                ->sectionServiceProducts(
                    getOption('section4_service_type_id', 1),
                    getOption('section4_item_limit'),
                    getOption('section4_item_type', 'bundle')
                ),

            'section6_products' => app(BundleService::class)
                ->sectionServiceProducts(
                    getOption('section6_service_type_id', 1),
                    getOption('section6_item_limit', 5),
                    getOption('section6_item_type', 'bundle')
                ),

            'section8_products' => app(BundleService::class)
                ->sectionCategoryProducts(
                    getOption('section8_category_id', 1),
                    getOption('section8_item_limit', 5),
                    getOption('section8_item_type', 'bundle')
                ),
        ];

        if (!config('socialite.fib.enabled')) {
            $data['languages'] = app(LanguageService::class)->cms();

            $data['header_menus'] = app(MenuService::class)
                ->getSectionMenu(getOption('header_top_menu', 'main'));

            $data['header_explore_menus'] = app(MenuService::class)
                ->getSectionMenu(getOption('header_explore_menu', 'explore_menu'));

            $data['recommendations'] = app(CmsService::class)->recommended($request);

            $data['footer_category_menus'] = app(MenuService::class)
                ->getSectionMenu(getOption('footer_category_menu', 'footer_category'));

            $data['footer_menus'] = app(MenuService::class)
                ->getSectionMenu(getOption('footer_menu', 'footer'));
        }
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

    public function sectionProducts(Request $request)
    {
        return response()->success(
            app(BundleService::class)
                ->sectionCategoryProducts(
                    $request->input('category_id'),
                    $request->input('limit', 5)
                )
        );
    }

    public function giftCards(Request $request)
    {
        return response()->success(
            app(CmsService::class)->getGiftCards($request)
        );
    }

    public function mobileRecharge(Request $request)
    {
        return response()->success(
            app(CmsService::class)->mobileRecharge($request)
        );
    }

    public function internetRecharge(Request $request)
    {
        return response()->success(
            app(CmsService::class)->internetRecharge($request)
        );
    }
}
