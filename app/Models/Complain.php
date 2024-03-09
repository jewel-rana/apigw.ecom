<?php

namespace App\Models;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complain extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'description',
        'remarks',
        'status',
        'created_by',
        'updated_by'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id')->select('id', 'name', 'email');
    }

    public function scopeFilter($query, $request)
    {
        return CommonHelper::filterModel($query, $request);
    }

    public function format(): array
    {
        return $this->only([
                'id',
                'title',
                'description',
                'remarks',
                'status',
                'created_at',
                'updated_at'
            ]) +
            [
                'customer' => $this->customer,
                'created_by' => $this->createdBy,
                'updated_by' => $this->updatedBy
            ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (Complain $complain) {
            $complain->created_by = auth()->user()->id ?? 1;
        });

        static::updating(function (Complain $complain) {
            $complain->updated_by = auth()->user()->id ?? 1;
        });
    }
}
