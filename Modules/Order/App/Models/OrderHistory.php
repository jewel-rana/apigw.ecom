<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Auth\Entities\User;

class OrderHistory extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'type',
        'old_value',
        'new_value',
        'remarks'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if(!$model->type) {
                $model->type = 'status';
            }
        });
    }
}
