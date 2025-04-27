<?php

namespace Modules\Auth\Helpers;

class AuthHelper
{
    public static function hasPermission(array $permissions): bool
    {
        return (auth()->check() && auth()->user()->can($permissions)) || auth()->user()->hasRole('admin');
    }
}
