<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;

class PageAttribute extends Model
{
    public $timestamps = false;
    protected $fillable = ['page_id', 'label', 'content'];
    protected $hidden = ['id', 'page_id'];
}
