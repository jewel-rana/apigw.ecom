<?php

namespace Modules\Provider\Repositories;

use App\Repositories\BaseRepository;
use Modules\Provider\Entities\Provider;
use Modules\Provider\Repositories\Interfaces\ProviderRepositoryInterface;

class ProviderRepository extends BaseRepository implements ProviderRepositoryInterface
{
    public function __construct(Provider $model)
    {
        parent::__construct($model);
    }
}
