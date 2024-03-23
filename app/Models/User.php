<?php

namespace App\Models;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Services\PermissionService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
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
        'is_system',
        'remarks',
        'email_verified_at',
        'updated_at',
        'updated_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'type',
        'is_system'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime:d/m/Y h:i a',
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

    public function format(): array
    {
        return $this->only(['id', 'name', 'mobile', 'email', 'status', 'remarks', 'created_at', 'updated_at']) +
            [
                'type' => 'user',
                'role' => $this->roles->first()->only(['id', 'name']),
                'created_by' => $this->createdBy,
                'updated_by' => $this->updatedBy
            ];
    }

    public function getPermissions(): array
    {
        return $this->hasRole('admin') ?
            app(PermissionService::class)->all()->pluck('name')->toArray() :
            $this->getAllPermissions()->pluck('name')->toArray();
    }

    public function scopeFilter($query, $request)
    {
        return CommonHelper::filterModel($query, $request);
    }

    public function revokeToken()
    {
        CommonHelper::revokeUserToken($this->id);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->created_by =  auth()->user()->id ?? 1;
        });

        static::updating(function (User $user) {
            $user->updated_by =  auth()->user()->id ?? 1;
        });

        static::updated(function (User $user) {
            if($user->status != AuthConstant::STATUS_ACTIVE) {
                CommonHelper::revokeUserToken($user->id);
            }
        });
    }
}
