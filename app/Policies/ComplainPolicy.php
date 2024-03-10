<?php

namespace App\Policies;

use App\Helpers\CommonHelper;

class ComplainPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('complain-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('complain-update');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('complain-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('complain-update');
    }

    public function delete(): bool
    {
        return CommonHelper::hasPermission('complain-action');
    }

    public function restore(): bool
    {
        //
    }

    public function forceDelete(): bool
    {
        //
    }
}
