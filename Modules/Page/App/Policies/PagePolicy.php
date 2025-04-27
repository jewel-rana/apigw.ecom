<?php

namespace Modules\Page\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['page-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['page-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['page-create']);
    }

    public function show(): bool
    {
        return true;
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['page-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['page-update']);
    }
}
