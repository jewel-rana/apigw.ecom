<?php

namespace Modules\Page\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Page\App\Policies\PagePolicy;
use Modules\Page\Entities\Page;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Page::class => PagePolicy::class
    ];

    public function boot(): void
    {
    }
}
