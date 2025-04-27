<?php

namespace Modules\Menu;

use App\Helpers\CommonHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Menu\Repository\MenuRepositoryInterface;

class MenuService
{
    private MenuRepositoryInterface $repository;

    public function __construct(MenuRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        Cache::forget('menus');
        return Cache::rememberForever('menus', function() {
            return $this->repository->with('items.childs')->get();
        });
    }

    public function cms()
    {
        $local = app()->getLocale();
        return Cache::rememberForever('menu_' . $local, function() {
            return $this->all()
                ->map(function ($menu) {
                    return [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'items' => $menu->items->map(function ($item) {
                            return $item->only(['name', 'description', 'menu_url', 'css_class', 'icon']) +
                                [
                                    'child' => $item->childs->map(function ($child) {
                                        return $child->only(['name', 'description', 'menu_url', 'css_class', 'icon']);
                                    })
                                ];
                        })
                    ];
                });
        });
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->repository->update($data, $id);
    }

    public function getMenuList(): Collection
    {
        return $this->repository->all()->pluck('name', 'name');
    }

    public function getMenu($name)
    {
        return $this->repository->get($name);
    }

    public function getSectionMenu($name)
    {
        return $this->cms()->filter(function ($item) use ($name) {
            return trim(strtolower($item['name'])) == trim(strtolower($name));
        })->first();
    }

    public function menuIcons()
    {
        return Cache::remember('menu_icons', 3600, function () {
            return CommonHelper::getMenuIcons(public_path('default/menus')) ?? [];
        });
    }

    public function getIconSuggestions($request)
    {
        try {
            $data = collect($this->menuIcons())->filter(function ($item) use ($request) {
                $matched = true;
                if ($request->has('term')) {
                    $matched = CommonHelper::matchText($item, $request->input('term'));
                }
                return $matched;
            })
                ->map(function ($item, $key) {
                    return [
                        'id' => $item,
                        'text' => $item
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'No data!', 'results' => []]);
        }
    }
}
