<?php

namespace Modules\Menu\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
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

    public function index()
    {
        return \response()->success(
            $this->menuService->cms()
        );
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

    public function show($name)
    {
        return \response()->success(
            $this->menuService->cms()->filter(function ($item) use ($name) {
                return trim(strtolower($item['name'])) == trim(strtolower($name));
            })->first()
        );
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
