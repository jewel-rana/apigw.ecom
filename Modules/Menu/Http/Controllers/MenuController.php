<?php

namespace Modules\Menu\Http\Controllers;

use App\Constants\AppConst;
use App\Constants\Constant;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Events\MenuUpdateEvent;
use Modules\Menu\Http\Requests\MenuCreateRequest;
use Modules\Menu\Http\Requests\MenuUpdateRequest;
use Modules\Menu\MenuService;
use Modules\Operator\Entities\Operator;

class MenuController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    private MenuService $menus;

    public function __construct(MenuService $menus)
    {
        $this->menus = $menus;
    }

    public function index(): Renderable
    {
        $menus = $this->menus->all();
        return view('menu::index', compact('menus'))->withTitle('Menus');
    }

    public function create(): Renderable
    {
        return view('menu::create')->withTitle('Add New Menu');
    }

    public function store(MenuCreateRequest $request): RedirectResponse
    {
        try {
           $this->menus->create($request->validated());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back()->withInput($request->all());
        }

        return redirect()->route('menu.index');
    }

    public function show(Menu $menu): Renderable
    {
        return view('menu::show', compact('menu'))->withTitle('Menu: ' . $menu->name);
    }

    public function edit(Menu $menu): Renderable
    {
        return view('menu::edit', compact('menu'))->withTitle('Update menu');
    }

    public function update(MenuUpdateRequest $request, $id): RedirectResponse
    {
        try {
            $this->menus->update($request->validated(), $id);
            event(new MenuUpdateEvent());
        } catch (\Throwable $exception) {
            session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }

        return redirect()->route('menu.index');
    }

    public function destroy($id)
    {
        //
    }

    public function iconSuggestions(Request $request)
    {
        return $this->menus->getIconSuggestions($request);
    }

    public function callAction($method, $parameters)
    {
        if (!in_array($method, ['iconSuggestions'])) {
            $this->authorize($method, Operator::class);
        }
        return parent::callAction($method, $parameters);
    }
}
