<?php

namespace Modules\Menu\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Language;

class MenuUpdateEvent
{
    use SerializesModels;

    public function __construct()
    {
        Cache::forget('menus');
        Language::pluck('code')->each(function($item) {
            $key = "menu_{$item}";
            Cache::forget($key);
        });
    }
}
