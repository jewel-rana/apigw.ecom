<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\Interfaces\UserRepositoryInterface;

class RoleRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }
}
