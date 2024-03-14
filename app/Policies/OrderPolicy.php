<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Auth;

class OrderPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('order-list') || Auth::guard('customers')->user();
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('order-show') || Auth::guard('customers')->user();
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('order-create') || Auth::guard('customers')->user();
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
