<?php

namespace Modules\Brand\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;
    public function index(): bool
    {
        return CommonHelper::hasPermission(['brand-list']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['brand-show']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['brand-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['brand-create']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['brand-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['brand-update']);
    }

    public function delete(): bool
    {
        return CommonHelper::hasPermission(['brand-action']);
    }

    public function action(): bool
    {
        return CommonHelper::hasPermission(['brand-action']);
    }

    public function restore(): bool
    {
        return CommonHelper::hasPermission(['brand-action']);
    }

    public function forceDelete(): bool
    {
        return CommonHelper::hasPermission(['brand-action']);
    }

    public function export(): bool
    {
        return CommonHelper::hasPermission(['brand-action']);
    }
}
