<?php

namespace Modules\CMS\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeCardPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['home-card-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['home-card-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['home-card-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['home-card-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['home-card-update']);
    }
}
