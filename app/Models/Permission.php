<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $fillable = ['name', 'guard_name'];

    protected $hidden = [
        'guard_name',
        'updated_at',
        'created_at'
    ];
}
