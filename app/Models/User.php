<?php

namespace App\Models;

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
        'email_verified_at' => 'datetime:d/m/Y h:i a',
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

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function format(): array
    {
        return $this->only(['id', 'name', 'mobile', 'email', 'status', 'created_at', 'updated_at']) +
            [
                'created_by' => $this->createdBy->only(['id', 'name', 'email'])
            ];
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
         if($request->filled('status') && in_array(strtolower($request->input('status')), ['active', 'inactive'])) {
             $query->where('status', '=', ucfirst($request->input('status')));
         }

         if($request->filled('keyword')) {
             $query->where(function($query) use($request) {
                 $query->where('name', 'like', $request->input('keyword') . "%");
             });
         }
         return $query;
     }
}
