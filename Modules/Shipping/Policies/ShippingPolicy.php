<?php

namespace Modules\Shipping\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingPolicy
{
    use HandlesAuthorization;


    public function index(): bool
    {
        return CommonHelper::hasPermission(['shipping-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['shipping-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['shipping-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['shipping-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['shipping-update']);
    }
}
