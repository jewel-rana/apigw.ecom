<?php

namespace Modules\CMS\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\CMS\Database\factories\NewArrivalFactory;

class NewArrival extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): NewArrivalFactory
    {
        //return NewArrivalFactory::new();
    }
}
