<?php

namespace Modules\Shipping\Repositories;

use App\Repositories\BaseRepository;
use Modules\Shipping\Entities\Shipping;
use Modules\Shipping\Repositories\Interfaces\ShippingRepositoryInterface;

class ShippingRepository extends BaseRepository implements ShippingRepositoryInterface
{
    public function __construct(Shipping $model)
    {
        parent::__construct($model);
    }
}
