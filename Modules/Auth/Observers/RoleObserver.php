<?php

namespace Modules\Auth\Observers;

use Illuminate\Support\Facades\Artisan;
use Modules\Auth\Entities\Role;

class RoleObserver
{
    public function __construct(Role $role)
    {
        Artisan::call("permission:cache-reset");
    }

    public function creating(Role $role)
    {
        Artisan::call("permission:cache-reset");
    }
}
