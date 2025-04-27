<?php

namespace Modules\Region\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Region;

class RegionObserver
{
    public function created(Region $region): void
    {
        Cache::forget('regions');
    }

    public function updated(Region $region): void
    {
        Cache::forget('regions');
    }

    public function deleted(Region $region): void
    {
        Cache::forget('regions');
    }
}
