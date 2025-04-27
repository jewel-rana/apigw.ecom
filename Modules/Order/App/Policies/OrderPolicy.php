<?php

namespace Modules\Order\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['order-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['order-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['order-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['order-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['order-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['order-update']);
    }

    public function sold(): bool
    {
        return CommonHelper::hasPermission(['order-product-list']);
    }

    public function export(): bool
    {
        return CommonHelper::hasPermission(['order-export']);
    }

    public function export_sold(): bool
    {
        return CommonHelper::hasPermission(['order-product-export']);
    }
}
