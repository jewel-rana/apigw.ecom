<?php

namespace Modules\Region\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['language-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['language-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['language-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['language-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['language-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['language-update']);
    }
}
