<?php

namespace Modules\Setting\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['setting-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['setting-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['setting-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['setting-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['setting-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['setting-update']);
    }
}
