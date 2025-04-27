<?php

namespace Modules\CMS\App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class BannerCacheRemoveEvent
{
    use SerializesModels;

    public function __construct()
    {
        Cache::forget('banners');
        Cache::forget('api_banners');
    }
}
