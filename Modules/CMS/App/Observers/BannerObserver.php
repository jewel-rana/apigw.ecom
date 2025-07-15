<?php

namespace Modules\CMS\App\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Banner\Entities\Banner;

class BannerObserver
{
    public function __construct()
    {
        Cache::forget('banners');
        Cache::forget('api_banners');
    }

    public function creating(Banner $banner)
    {
        $banner->created_by = auth()->id();
    }

    public function created(Banner $banner): void
    {
        //
    }

    public function updating(Banner $banner)
    {
        $banner->updated_by = auth()->id();
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
