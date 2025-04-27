<?php

namespace Modules\Menu\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Menu\App\Models\MenuAttribute;
use Modules\Menu\MenuItemService;
use Modules\Menu\MenuService;
use Modules\Region\App\Http\Requests\LanguageAttributeStoreRequest;
use Modules\Region\App\Http\Requests\LanguageAttributeUpdateRequest;

class MenuAttributeController extends Controller
{
    private MenuService $menuService;
    private MenuItemService $menuItemService;

    public function __construct(
        MenuService $menuService,
        MenuItemService $menuItemService
    )
    {
        $this->menuService = $menuService;
        $this->menuItemService = $menuItemService;
    }

    public function index(Request $request)
    {
        if($request->ajax()) {
            return $this->menuItemService->getAttributeDataTable($request);
        }
        return view('menu::attribute.index');
    }

    public function store(LanguageAttributeStoreRequest $request)
    {
        try {
            MenuAttribute::updateOrCreate(
                [
                    'menu_item_id' => $request->input('menu_item_id'),
                    'language' => $request->input('language')
                ], $request->validated()
            );
            Cache::forget('menus');
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'MENU_ATTRIBUTE_CREATE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function update(LanguageAttributeUpdateRequest $request, MenuAttribute $attribute): RedirectResponse
    {
        try {
            $attribute->update($request->validated());
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'MENU_ATTRIBUTE_UPDATE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function destroy($id)
    {
        //
    }
}
