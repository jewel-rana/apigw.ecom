<?php

namespace Modules\Auth\Entities;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $fillable = ['name', 'guard_name'];
    protected $guard_name = 'web';
}
