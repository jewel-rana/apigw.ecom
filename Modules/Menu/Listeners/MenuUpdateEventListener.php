<?php

namespace Modules\Menu\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Menu\Events\MenuUpdateEvent;
use Modules\Menu\MenuService;

class MenuUpdateEventListener
{
    private MenuService $menus;

    public function __construct(MenuService $menuService)
    {
        $this->menus = $menuService;
    }

    public function handle(MenuUpdateEvent $event): void
    {
        $this->menus->all()->each(function($item, $key) {
            Cache::forget($item->name);
        });
        Cache::forget('menus');
    }
}
