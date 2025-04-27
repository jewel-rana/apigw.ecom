<?php

namespace Modules\Menu\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['menu-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['menu-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['menu-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['menu-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['menu-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['menu-update']);
    }
}
