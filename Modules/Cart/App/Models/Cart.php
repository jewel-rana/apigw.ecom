<?php

namespace Modules\Cart\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class Cart extends Model
{
    protected $fillable = ['token', 'customer_id'];
    protected $hidden = ['customer_id'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeFilter($query, $request)
    {
        if (auth()->check()) {
            $query->where('customer_id', auth('api')->id());
        } elseif ($request->hasCookie('guest_unique_id')) {
            $query->where('token', $request->cookie('guest_unique_id'));
        }

        return $query;
    }

    public function format(): array
    {
        return [
            'token' => $this->token,
            'total_qty' => $this->items->sum('qty'),
            'total_amount' => $this->items->map(function ($item, $k) {
                return [
                    'amount' => $item->qty * $item->price
                ];
            })->sum('amount'),
            'items' => $this->items->map(function ($item) {
                return [
                    'id' => $item->item_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'sub_total' => $item->qty * $item->price
                ];
            })
        ];
    }

    public static function boot()
    {
        parent::boot();
    }
}
