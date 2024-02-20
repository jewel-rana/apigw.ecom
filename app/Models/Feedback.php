<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'company',
        'moto',
        'name',
        'designation',
        'comments',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i a'
    ];

    public function scopeFilter($query, $request)
    {
        return CommonHelper::filterModel($query, $request);
    }
}
