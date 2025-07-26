<?php

namespace Modules\Brand\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Brand\Entities\Brand;
use Modules\Brand\Policies\BrandPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Brand::class => BrandPolicy::class
    ];

    public function boot(): void
    {
    }
}
