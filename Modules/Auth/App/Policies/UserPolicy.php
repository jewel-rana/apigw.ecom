<?php

namespace Modules\Auth\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['administrator-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['administrator-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['administrator-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['administrator-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['administrator-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['administrator-update', 'administrator-edit']);
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission(['administrator-action']);
    }
}
