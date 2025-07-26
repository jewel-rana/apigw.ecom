<?php

namespace Modules\Banner\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class BannerPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['banner-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['banner-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['banner-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['banner-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['banner-update']);
    }
}
