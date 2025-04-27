<?php
namespace Modules\Activity\App\Services;

use Modules\Activity\App\Models\ActivityLog;

class ActivityService
{
    private ActivityLog $model;

    public function __construct(ActivityLog $model)
    {
        $this->model = $model;
    }

    public function getModel(): ActivityLog
    {
        return $this->model;
    }
}
