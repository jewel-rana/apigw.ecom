<?php

namespace Modules\Auth\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['role-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['role-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['role-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['role-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['role-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['role-update']);
    }
}
