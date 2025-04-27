<?php

namespace Modules\Region\Repositories;

use App\Repositories\BaseRepository;
use Modules\Region\Entities\TimeZone;
use Modules\Region\Repositories\Interfaces\TimeZoneRepositoryInterface;

class TimeZoneRepository extends BaseRepository implements TimeZoneRepositoryInterface
{
    public function __construct(TimeZone $model)
    {
        parent::__construct($model);
    }
}
