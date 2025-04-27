<?php

namespace Modules\Order\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class RefundPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['refund-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['refund-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['refund-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['refund-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['refund-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['refund-update']);
    }

    public function export(): bool
    {
        return CommonHelper::hasPermission(['refund-export']);
    }
}
