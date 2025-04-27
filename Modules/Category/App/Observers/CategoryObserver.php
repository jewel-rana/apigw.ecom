<?php

namespace Modules\Category\App\Observers;

use Illuminate\Support\Facades\Cache;
use Modules\Category\App\Models\Category;

class CategoryObserver
{
    public function __construct()
    {
        Cache::forget('categories');
    }

    public function created(Category $category)
    {
        //
    }

    public function updated(Category $category)
    {
        //
    }

    public function deleted(Category $category)
    {
        //
    }
}
