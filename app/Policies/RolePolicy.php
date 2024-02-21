<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class RolePolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('role-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('role-list');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('role-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('role-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('role-delete');
    }
}
