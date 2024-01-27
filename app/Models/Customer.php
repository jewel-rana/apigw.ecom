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
        'type',
        'is_system'
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

    public function format(): array
    {
        return $this->only(['id', 'name', 'mobile', 'email', 'gender', 'address', 'status', 'created_at', 'updated_at']) +
            [
                'created_by' => $this->createdBy->name ?? 'Self',
                'updated_by' => $this->updatedBy->name ?? 'Self',
            ];
    }
}
