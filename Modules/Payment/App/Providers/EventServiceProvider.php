<?php

namespace Modules\Payment\App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Payment\App\Events\PaymentVerifiedEvent;
use Modules\Payment\App\Listeners\PaymentVerifiedEventListener;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentVerifiedEvent::class => [
            PaymentVerifiedEventListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
