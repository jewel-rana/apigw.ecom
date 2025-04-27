<?php

namespace Modules\Provider\Repositories;

use App\Repositories\BaseRepository;
use Modules\Provider\Entities\ProviderUser;
use Modules\Provider\Repositories\Interfaces\ProviderUserRepositoryInterface;

class ProviderUserRepository extends BaseRepository implements ProviderUserRepositoryInterface
{
    public function __construct(ProviderUser $model)
    {
        parent::__construct($model);
    }
}
