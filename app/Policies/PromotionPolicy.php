<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class PromotionPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('user-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('user-show');
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission('user-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('user-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('user-delete');
    }
}
