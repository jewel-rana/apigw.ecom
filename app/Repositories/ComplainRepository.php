<?php

namespace App\Repositories;

use App\Models\Complain;
use App\Repositories\Interfaces\ComplainRepositoryInterface;

class ComplainRepository extends BaseRepository implements ComplainRepositoryInterface
{
    public function __construct(Complain $model)
    {
        parent::__construct($model);
    }
}
