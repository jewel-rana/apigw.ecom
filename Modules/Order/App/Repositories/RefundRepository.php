<?php

namespace Modules\Order\App\Repositories;

use App\Repositories\BaseRepository;
use Modules\Order\App\Models\Refund;
use Modules\Order\App\Repositories\Interfaces\RefundRepositoryInterface;

class RefundRepository extends BaseRepository implements RefundRepositoryInterface
{
    public function __construct(Refund $model)
    {
        parent::__construct($model);
    }
}
