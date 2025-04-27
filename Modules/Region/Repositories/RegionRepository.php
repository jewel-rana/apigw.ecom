<?php

namespace Modules\Region\Repositories;

use App\Repositories\BaseRepository;
use Modules\Region\Entities\Region;
use Modules\Region\Repositories\Interfaces\RegionRepositoryInterface;

class RegionRepository extends BaseRepository implements RegionRepositoryInterface
{
    public function __construct(Region $model)
    {
        parent::__construct($model);
    }
}
