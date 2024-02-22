<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;

class Role extends \Spatie\Permission\Models\Role
{
    public $guard_name = 'web';
    protected $fillable = ['name', 'guard_name'];

    public function revokeToken()
    {
        $this->users->each(function($user, $key) {
            CommonHelper::revokeUserToken($user->id);
        });
    }
}
