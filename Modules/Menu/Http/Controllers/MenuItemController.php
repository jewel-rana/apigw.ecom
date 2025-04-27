<?php

namespace Modules\Menu\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Menu\App\Http\Requests\MenuItemAddRequest;
use Modules\Menu\Entities\Menu;
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

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->menuItemService->getDataTable($request);
        }
    }

    public function store(MenuItemCreateRequest $request): RedirectResponse
    {
        try {
            $this->menuItemService->create($request->validated());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function update(MenuItemUpdateRequest $request, $id): JsonResponse
    {
        $data = ['status' => false, 'message'=> 'Cannot save changes'];
        try {
            $this->menuItemService->update($request->validated(), $id);
            $data['message'] = 'Successfully updated menu';
            $data['status'] = true;
        } catch (\Throwable $exception) {
            $data['message'] = $exception->getMessage();
        }

        return response()->json($data);
    }

    public function save(Request $request): JsonResponse
    {
        $data = ['status' => false, 'message'=> 'Cannot save changes'];
        try {
            DB::transaction(function() use($request, &$data) {
                if(is_array($request->sorted)) {
                    collect($request->sorted)->each(function ($item, $key) {
                        if($key === 0) return;
                        $this->menuItemService->update(['menu_order' => $key, 'parent_id' => (int) $item['parent_id']], $item['id']);
                    });
                }
            }, 2);
            event(new MenuUpdateEvent());
            $data['status'] = true;
            $data['message'] = 'Your changes successfully saved';
        } catch (\Throwable $exception) {
            $data['message'] = $exception->getMessage();
        }

        return response()->json($data);
    }

    public function destroy($id): JsonResponse
    {
        $data = ['status' => false, 'message'=> 'Cannot save changes'];
        try {
            $this->menuItemService->delete($id);
            $data['status'] = true;
            $data['message'] = 'Successfully deleted menu';
        } catch (\Throwable $exception) {
            $data['message'] = $exception->getMessage();
        }
        return response()->json($data);
    }

    public function addItem(MenuItemAddRequest $request, Menu $menu): JsonResponse
    {
        try {
            $this->menuItemService->{$request->input('type')}($menu, $request);
            return response()->json(['status' => true, 'message' => __('Success')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'MENU_ITEM_ADD_FROM_RESOURCE_EXCEPTION'
            ]);
            return response()->json(['status' => false, 'message' => __('Failed')]);
        }
    }

    public function suggestions(Request $request, $menuId): JsonResponse
    {
        return $this->menuItemService->getSuggestions($menuId, $request);
    }
}
