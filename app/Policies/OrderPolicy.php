<?php

namespace App\Policies;

use App\Helpers\CommonHelper;
use App\Models\User;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function index(): bool
    {
        return CommonHelper::hasPermission('order-list');
    }

    public function store(): bool
    {
        return CommonHelper::hasPermission('order-create');
    }
}
