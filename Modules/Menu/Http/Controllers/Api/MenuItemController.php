<?php

namespace Modules\Menu\Http\Controllers\Api;

use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Menu\App\Http\Requests\MenuItemAddRequest;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\MenuItem;
use Modules\Menu\Events\MenuUpdateEvent;
use Modules\Menu\Http\Requests\MenuItemCreateRequest;
use Modules\Menu\Http\Requests\MenuItemUpdateRequest;
use Modules\Menu\MenuItemService;

class MenuItemController extends Controller
{
    private MenuItemService $menuItemService;

    public function __construct(MenuItemService $menuItemService)
    {
        $this->menuItemService = $menuItemService;
    }

    public function index(Request $request, Menu $menu)
    {
        return response()->success($menu->format());
    }

    public function store(MenuItemCreateRequest $request)
    {
        try {
            $this->menuItemService->create($request->validated());
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function show(Menu $menu, MenuItem $item)
    {
        return response()->success($item->format());
    }

    public function update(MenuItemUpdateRequest $request, $id): JsonResponse
    {
        try {
            $this->menuItemService->update($request->validated(), $id);
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        try {
            DB::transaction(function () use ($request, &$data) {
                if (is_array($request->sorted)) {
                    collect($request->sorted)->each(function ($item, $key) {
                        if ($key === 0) return;
                        $this->menuItemService->update(['menu_order' => $key, 'parent_id' => (int)$item['parent_id']], $item['id']);
                    });
                }
            }, 2);
            event(new MenuUpdateEvent());
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function destroy(Menu $menu, MenuItem $item): JsonResponse
    {
        try {
            $this->menuItemService->delete($item->id);
            return response()->success();
        } catch (\Throwable $exception) {
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function addItem(MenuItemAddRequest $request, Menu $menu): JsonResponse
    {
        try {
            $this->menuItemService->{$request->input('type')}($menu, $request);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'MENU_ITEM_ADD_FROM_RESOURCE_EXCEPTION'
            ]);
            return response()->success(['message' => $exception->getMessage()]);
        }
    }

    public function suggestions(Request $request, $menuId): JsonResponse
    {
        return $this->menuItemService->getSuggestions($menuId, $request);
    }
}
