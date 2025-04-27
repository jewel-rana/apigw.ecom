<?php

namespace Modules\Media\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Bundle\App\Policies\BundlePolicy;
use Modules\Bundle\Entities\Bundle;
use Modules\Media\App\Policies\MediaPolicy;
use Modules\Media\Entities\Media;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Media::class => MediaPolicy::class,
    ];

    public function boot(): void
    {
    }
}
