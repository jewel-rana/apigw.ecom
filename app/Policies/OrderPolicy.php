<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class OrderPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('order-list') || request()->user()->type == 'customer';
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('order-show') || request()->user()->type == 'customer';
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('order-create') || request()->user()->type == 'customer';
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
