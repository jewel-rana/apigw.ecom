<?php

namespace Modules\Page\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $fillable  = ['title', 'slug', 'description', 'status', 'user_id', 'template'];

    public function pageAttributes(): HasMany
    {
        return $this->hasMany(PageAttribute::class, 'page_id');
    }

    public function format(): array
    {
        return $this->only(['title', 'slug', 'description', 'template']) +
            [
                'attributes' => $this->pageAttributes
            ];
    }
}
