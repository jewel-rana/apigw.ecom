<?php

namespace Modules\CMS\App\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\CMS\App\Models\Banner;

class BannerObserver
{
    public function __construct()
    {
        Cache::forget('banners');
        Cache::forget('api_banners');
    }

    public function created(Banner $banner): void
    {
        //
    }

    public function updated(Banner $banner): void
    {
        //
    }

    public function deleted(Banner $banner): void
    {
        //
    }

    public function restored(Banner $banner): void
    {
        //
    }

    public function forceDeleted(Banner $banner): void
    {
        //
    }
}
