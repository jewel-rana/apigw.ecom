<?php

namespace Modules\Menu\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Menu\MenuService;

class MenuController extends Controller
{
    private MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index()
    {
        return \response()->success(
            $this->menuService->cms()
        );
    }

    public function show($name)
    {
        return \response()->success(
            $this->menuService->cms()->filter(function ($item) use ($name) {
                return trim(strtolower($item['name'])) == trim(strtolower($name));
            })->first()
        );
    }
}
