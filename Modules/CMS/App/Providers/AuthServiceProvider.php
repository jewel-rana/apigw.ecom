<?php

namespace Modules\CMS\App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\CMS\App\Models\Feature;
use Modules\CMS\App\Models\HomeCard;
use Modules\CMS\Policies\FeaturePolicy;
use Modules\CMS\Policies\HomeCardPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Feature::class => FeaturePolicy::class,
        HomeCard::class => HomeCardPolicy::class,
    ];

    public function boot(): void
    {
    }
}
