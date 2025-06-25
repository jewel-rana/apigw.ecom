<?php

namespace Modules\CMS\App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'title',
        'description',
        'feature_icon',
        'type',
        'model_id',
        'position',
    ];
}
