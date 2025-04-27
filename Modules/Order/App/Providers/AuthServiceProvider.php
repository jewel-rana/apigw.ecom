<?php

namespace Modules\Order\App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Policies\OrderPolicy;
use Modules\Order\App\Policies\RefundPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
        Refund::class => RefundPolicy::class,
    ];

    public function boot(): void
    {
    }
}
