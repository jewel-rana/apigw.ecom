<?php

namespace Modules\Payment\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['payment-list', 'order-show']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['payment-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['payment-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['payment-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['payment-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['payment-update']);
    }
}
