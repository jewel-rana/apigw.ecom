<?php

namespace Modules\Purchase\Repositories;

use App\Repositories\BaseRepository;
use Modules\Purchase\Entities\PurchaseItem;

class PurchaseItemRepository extends BaseRepository implements PurchaseItemRepositoryInterface
{
    public function __construct(PurchaseItem $model)
    {
        parent::__construct($model);
    }
}
