<?php

namespace Modules\Region\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['country-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['country-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['country-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['country-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['country-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['country-update']);
    }
}
