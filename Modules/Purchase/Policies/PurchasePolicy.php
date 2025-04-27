<?php

namespace Modules\Purchase\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchasePolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['purchase-list']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['purchase-show']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['purchase-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['purchase-create']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['purchase-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['purchase-update']);
    }

    public function delete(): bool
    {
        return CommonHelper::hasPermission(['purchase-action']);
    }

    public function restore(): bool
    {
        return CommonHelper::hasPermission(['purchase-action']);
    }

    public function forceDelete(): bool
    {
        return CommonHelper::hasPermission(['purchase-action']);
    }
}
