<?php

namespace Modules\Provider\Repositories;

use App\Repositories\BaseRepository;
use Modules\Provider\Entities\ProviderDeposit;
use Modules\Provider\Repositories\Interfaces\ProviderDepositRepositoryInterface;

class ProviderDepositRepository extends BaseRepository implements ProviderDepositRepositoryInterface
{
    public function __construct(ProviderDeposit $model)
    {
        parent::__construct($model);
    }
}
