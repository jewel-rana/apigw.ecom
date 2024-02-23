<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FeedbackPolicy
{
    public function index(): bool
    {
        return CommonHelper::hasPermission('feedback-list');
    }

    public function show(): bool
    {
        return CommonHelper::hasPermission('feedback-update');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('feedback-create');
    }

    public function update(): bool
    {
        return CommonHelper::hasPermission('feedback-update');
    }

    public function delete(): bool
    {
        return CommonHelper::hasPermission('feedback-action');
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
