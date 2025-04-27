<?php

namespace Modules\Provider\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Policies\ProviderPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Provider::class => ProviderPolicy::class,
    ];

    public function boot(): void
    {
    }
}
