<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class CustomerPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('customer-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('customer-show');
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission('customer-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('customer-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('customer-delete');
    }
}
