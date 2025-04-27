<?php

namespace Modules\Newsletter\App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    protected $fillable = [
        'name',
        'email',
        'is_subscribed'
    ];
}
