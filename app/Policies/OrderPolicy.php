<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class OrderPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('order-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('order-show');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('order-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('order-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('order-delete');
    }

    public function action(): bool
    {
        return CommonHelper::hasPermission('order-update');
    }
}
