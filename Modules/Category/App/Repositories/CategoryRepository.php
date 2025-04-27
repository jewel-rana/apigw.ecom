<?php

namespace Modules\Category\App\Repositories;

use App\Repositories\BaseRepository;
use Modules\Category\App\Models\Category;
use Modules\Category\App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
