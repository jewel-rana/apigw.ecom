<?php

namespace Modules\Region\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Country;

class CountryObserver
{
    public function created(Country $country): void
    {
        Cache::forget('countries');
    }

    public function updated(Country $country): void
    {
        Cache::forget('countries');
    }

    public function deleted(Country $country): void
    {
        Cache::forget('countries');
    }
}
