<?php

namespace Modules\Region\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegionPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['city-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['city-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['city-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['city-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['city-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['city-update']);
    }
}
