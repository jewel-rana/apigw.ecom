<?php

namespace Modules\Category\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {

    }

    public function index(): bool
    {
        return CommonHelper::hasPermission(['category-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['category-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['category-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['category-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['category-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['category-update']);
    }
}
