<?php

namespace Modules\Region\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Region\App\Models\City;

class CityObserver
{
    public function created(City $city): void
    {
        Cache::forget('cities');
    }

    public function updated(City $city): void
    {
        Cache::forget('cities');
    }

    public function deleted(City $city): void
    {
        Cache::forget('cities');
    }
}
