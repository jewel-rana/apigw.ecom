<?php

namespace Modules\Region\Repositories;

use App\Repositories\BaseRepository;
use Modules\Region\Entities\Country;
use Modules\Region\Repositories\Interfaces\CountryRepositoryInterface;

class CountryRepository extends BaseRepository implements CountryRepositoryInterface
{
    public function __construct(Country $model)
    {
        parent::__construct($model);
    }
}
