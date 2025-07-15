<?php

namespace Modules\Banner\Repositories;

use App\Repositories\BaseRepository;
use Modules\Banner\Repositories\Interfaces\BannerRepositoryInterface;
use Modules\Banner\Entities\Banner;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }
}
