<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'promotion_id',
        'promotion_objective_id',
        'promotion_period',
        'gender',
        'min_age',
        'max_age',
        'amount',
        'location',
        'divisions',
        'status',
        'created_by',
        'updated_by',
        'remarks'
    ];

    protected $casts = [
        'divisions' => 'array'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $attributes = [
        'divisions' => []
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function scopeFilter($query, Request $request)
    {
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->input('form') . ' 00:00:00');
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->input('to') . ' 00:00:00');
        }
        if ($request->filled('email')) {
            $query->where('email', '=', $request->input('email'));
        }
        if ($request->filled('mobile')) {
            $query->where('mobile', '=', $request->input('mobile'));
        }
        if ($request->filled('status') && in_array(strtolower($request->input('status')), ['pending', 'active', 'inactive'])) {
            $query->where('status', '=', ucfirst($request->input('status')));
        }
        if ($request->filled('created_by')) {
            $query->where('created_by', '=', $request->input('created_by'));
        }
        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', $request->input('keyword') . "%");
            });
        }
        return $query;
    }
}
