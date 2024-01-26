<?php

namespace App\Models;

 use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
 use Spatie\Permission\Traits\HasRoles;

 class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'password',
        'status',
        'type',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getStatusAttribute($value): string
    {
        return $value ? 'Active' : 'Inactive';
    }

    public function format(): array
    {
        return $this->only(['id', 'name', 'mobile', 'email', 'status']) +
            [
                'gender' => $this->customer->gender ?? '---'
            ];
    }
}
