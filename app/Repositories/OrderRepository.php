<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Interfaces\UserRepositoryInterface;

class OrderRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
