<?php

namespace Modules\CMS\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeaturePolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['feature-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['feature-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['feature-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['feature-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['feature-update']);
    }
}
