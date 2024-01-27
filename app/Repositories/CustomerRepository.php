<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
