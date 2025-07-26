<?php

namespace Modules\Banner\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Banner\Entities\Banner;
use Modules\Banner\Policies\BannerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Banner::class => BannerPolicy::class
    ];

    public function boot(): void
    {
    }
}
