<?php

namespace Modules\Purchase\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Policies\PurchasePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Purchase::class => PurchasePolicy::class,
    ];

    public function boot(): void
    {
    }
}
