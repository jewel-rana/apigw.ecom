<?php

namespace Modules\Provider\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProviderPolicy
{
    use HandlesAuthorization;
    public function index(): bool
    {
        return CommonHelper::hasPermission(['supplier-list']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['supplier-show']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['supplier-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['supplier-create']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['supplier-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['supplier-update']);
    }

    public function delete(): bool
    {
        return CommonHelper::hasPermission(['supplier-action']);
    }

    public function restore(): bool
    {
        return CommonHelper::hasPermission(['supplier-action']);
    }

    public function forceDelete(): bool
    {
        return CommonHelper::hasPermission(['supplier-action']);
    }

    public function export(): bool
    {
        return CommonHelper::hasPermission(['supplier-action']);
    }
}
