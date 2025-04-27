<?php

namespace Modules\CMS\App\Repositories;

use App\Repositories\BaseRepository;
use Modules\CMS\App\Models\Banner;
use Modules\CMS\App\Repositories\Interfaces\BannerRepositoryInterface;

class BannerRepository extends BaseRepository implements BannerRepositoryInterface
{
    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }
}
