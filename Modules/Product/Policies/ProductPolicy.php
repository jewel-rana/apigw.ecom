<?php

namespace Modules\Product\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['product-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['product-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['product-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['product-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['product-update']);
    }
}
