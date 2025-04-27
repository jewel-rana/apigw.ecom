<?php

namespace Modules\Region\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurrencyPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['currency-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['currency-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['currency-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['currency-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['currency-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['currency-update']);
    }
}
