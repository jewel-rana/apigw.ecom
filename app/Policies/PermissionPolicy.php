<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('permission-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('permission-show');
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission('permission-create');
    }

    public function store(): bool
    {
        return CommonHelper::isHierarchyOk() && CommonHelper::hasPermission('permission-create');
    }

    public function update(Request $request, Permission $user): bool
    {
        return CommonHelper::hasPermission('permission-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('permission-delete');
    }
}
