<?php

namespace Modules\Purchase\Repositories;

use App\Repositories\BaseRepository;
use Modules\Purchase\Entities\Purchase;

class PurchaseRepository extends BaseRepository implements PurchaseRepositoryInterface
{
    public function __construct(Purchase $model)
    {
        parent::__construct($model);
    }
}
