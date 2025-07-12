<?php

namespace Modules\Menu\Http\Controllers\Api;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Brand\Entities\Brand;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Http\Requests\MenuCreateRequest;
use Modules\Menu\Http\Requests\MenuUpdateRequest;
use Modules\Menu\MenuService;

class MenuController extends Controller
{
    private MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        $menus = Menu::filter($request)
            ->latest()
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($menus));
    }

    public function store(MenuCreateRequest $request)
    {
        try {
            $this->menuService->create($request->validated());
            return \response()->success();
        } catch (\Throwable $th) {
            LogHelper::error($th, [
                'keyword' => 'MENU_CREATE_EXCEPTION'
            ]);
            return response()->error();
        }
    }

    public function show(Menu $menu)
    {
        return response()->success($menu->format(true));
    }

    public function update(MenuUpdateRequest $request, Menu $menu)
    {
        try {
            $this->menuService->update($request->validated(), $menu->id);
            return response()->success();
        } catch (\Throwable $th) {
            LogHelper::error($th, [
                'keyword' => 'MENU_CREATE_EXCEPTION'
            ]);
            return response()->error();
        }
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return response()->success();
        } catch (\Throwable $th) {
            LogHelper::error($th, [
                'keyword' => 'MENU_DELETE_EXCEPTION'
            ]);
            return response()->error();
        }
    }
}
