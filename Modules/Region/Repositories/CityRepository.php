<?php

namespace Modules\Region\Repositories;

use App\Repositories\BaseRepository;
use Modules\Region\App\Models\City;
use Modules\Region\Repositories\Interfaces\CityRepositoryInterface;

class CityRepository extends BaseRepository implements CityRepositoryInterface
{
    public function __construct(City $model)
    {
        parent::__construct($model);
    }
}
