<?php

namespace App\Models;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable;

    protected function getDefaultGuardName(): string
    {
        return 'customers';
    }

    protected $fillable = [
        'gender',
        'created_by',
        'updated_by',
        'name',
        'mobile',
        'email',
        'password',
        'status',
        'is_system',
        'remarks'
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
        return $this->only(['id', 'name', 'mobile', 'email', 'gender', 'address', 'status', 'created_at', 'updated_at', 'remarks']) +
            [
                'type' => 'customer',
                'created_by' => $this->createdBy,
                'updated_by' => $this->updatedBy,
            ];
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function(Customer $customer) {
            $customer->email_verified_at = now();
            $customer->status = AuthConstant::STATUS_ACTIVE;
            if(auth()->check() && request()->user()->type == 'admin') {
                $customer->created_by = request()->user()->id;
            }
        });

        static::updating(function(Customer $customer) {
            if(auth()->check() && request()->user()->type == 'admin') {
                $customer->updated_by = request()->user()->id ?? 1;
            }
        });
    }
}
