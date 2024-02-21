<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    protected $fillable = [
        'company',
        'moto',
        'name',
        'designation',
        'video_link',
        'website',
        'comments',
        'status',
        'remarks',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y h:i a'
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function scopeFilter($query, $request)
    {
        return CommonHelper::filterModel($query, $request);
    }

    public function format(): array
    {
        return $this->only([
                'id',
                'company',
                'moto',
                'name',
                'designation',
                'comments',
                'website',
                'video_link',
                'remarks',
                'status'
            ]) +
            [
                'created_by' => $this->createdBy->only(['id', 'name', 'email']) ?? null,
                'updated_by' => $this->updatedBy->only(['id', 'name', 'email']) ?? null,
            ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Feedback $feedback) {
            $feedback->created_by = request()->user()->id;
        });

        static::updating(function (Feedback $feedback) {
            $feedback->updated_by = request()->user()->id;
        });
    }
}
