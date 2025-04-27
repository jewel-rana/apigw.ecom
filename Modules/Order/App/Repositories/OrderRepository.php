<?php

namespace Modules\Order\App\Repositories;

use App\Repositories\BaseRepository;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }
}
