<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use App\Models\User;

class PaymentPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('payment-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('payment-show');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('payment-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('payment-update');
    }

    public function destroy(): bool
    {
        return CommonHelper::hasPermission('payment-delete');
    }

    public function action(): bool
    {
        return CommonHelper::hasPermission('payment-update');
    }
}
