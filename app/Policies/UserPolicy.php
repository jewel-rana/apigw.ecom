<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class UserPolicy
{
    private bool $authorize;

    public function __construct()
    {
        $this->authorize = auth()->check();
    }

    public function index(): bool
    {
        return CommonHelper::hasPermission('user-list');
    }

    public function show(): bool
    {
        return $this->authorize && CommonHelper::hasPermission('user-show');
    }

    public function create(): bool
    {
        return $this->authorize && CommonHelper::hasPermission('user-create');
    }

    public function update(): bool
    {
        return $this->authorize && CommonHelper::hasPermission('user-update');
    }

    public function destroy(): bool
    {
        return $this->authorize && CommonHelper::hasPermission('user-delete');
    }
}
