<?php

namespace Modules\Region\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Region\Entities\Language;

class LanguageObserver
{
    public function created(Language $language): void
    {
        Cache::forget('languages');
    }

    public function updated(Language $language): void
    {
        Cache::forget('languages');
    }

    public function deleted(Language $language): void
    {
        Cache::forget('languages');
    }
}
