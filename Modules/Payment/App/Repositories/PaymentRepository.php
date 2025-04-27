<?php

namespace Modules\Payment\App\Repositories;

use App\Repositories\BaseRepository;
use Modules\Payment\App\Models\Payment;
use Modules\Payment\App\Repositories\Interfaces\PaymentRepositoryInterface;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }
}
