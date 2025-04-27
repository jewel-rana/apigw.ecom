<?php

namespace Modules\Provider\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderUserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
