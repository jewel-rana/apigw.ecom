<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'gender',
        'created_by',
        'updated_by',
        'name',
        'mobile',
        'email',
        'password',
        'status',
        'is_system'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'deleted_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('from')) {
            $query->where('created_at', '>=', $request->input('form') . ' 00:00:00');
        }
        if($request->filled('to')) {
            $query->where('created_at', '<=', $request->input('to') . ' 00:00:00');
        }
        if($request->filled('email')) {
            $query->where('email', '=', $request->input('email'));
        }
        if($request->filled('mobile')) {
            $query->where('mobile', '=', $request->input('mobile'));
        }
        if($request->filled('status') && in_array(strtolower($request->input('status')), ['pending', 'active', 'inactive'])) {
            $query->where('status', '=', ucfirst($request->input('status')));
        }
        if($request->filled('created_by')) {
            $query->where('created_by', '=', $request->input('created_by'));
        }
        if($request->filled('keyword')) {
            $query->where(function($query) use($request) {
                $query->where('name', 'like', $request->input('keyword') . "%");
            });
        }
        return $query;
    }

    public function format(): array
    {
        return $this->only(['id', 'name', 'mobile', 'email', 'gender', 'address', 'status', 'created_at', 'updated_at']) +
            [
                'created_by' => $this->createdBy->name ?? 'Self',
                'updated_by' => $this->updatedBy->name ?? 'Self',
            ];
    }
}
