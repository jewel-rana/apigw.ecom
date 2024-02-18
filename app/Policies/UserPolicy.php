<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use App\Models\User;
use Illuminate\Http\Request;

class UserPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('user-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('user-show');
    }

    public function store(): bool
    {
        return CommonHelper::isHierarchyOk() && CommonHelper::hasPermission('user-create');
    }

    public function update(Request $request, User $user): bool
    {
        return CommonHelper::hasPermission('user-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('user-delete');
    }
}
