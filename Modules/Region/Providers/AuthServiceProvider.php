<?php

namespace Modules\Region\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Bundle\App\Policies\BundlePolicy;
use Modules\Bundle\Entities\Bundle;
use Modules\Region\App\Policies\CountryPolicy;
use Modules\Region\App\Policies\LanguagePolicy;
use Modules\Region\App\Policies\RegionPolicy;
use Modules\Region\Entities\Country;
use Modules\Region\Entities\Language;
use Modules\Region\Entities\Region;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Country::class => CountryPolicy::class,
        Region::class => RegionPolicy::class,
        Language::class => LanguagePolicy::class,
    ];

    public function boot(): void
    {
    }
}
