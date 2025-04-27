<?php

namespace Modules\Report\App\Policies;

use App\Helpers\CommonHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return CommonHelper::hasPermission(['report-list']);
    }

    public function create(): bool
    {
        return CommonHelper::hasPermission(['report-create']);
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission(['report-create']);
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission(['report-show']);
    }

    public function edit(): bool
    {
        return CommonHelper::hasPermission(['report-update']);
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission(['report-update']);
    }
}
