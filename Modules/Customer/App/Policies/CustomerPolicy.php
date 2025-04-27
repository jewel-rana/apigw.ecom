<?php

namespace Modules\Customer\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['customer-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['customer-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['customer-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['customer-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['customer-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['customer-update']);
    }

    public function export(): bool
    {
        return CommonHelper::hasPermission(['customer-export']);
    }

    public function orderExport(): bool
    {
        return CommonHelper::hasPermission(['customer-export']);
    }
}
