<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Provider\Entities\Provider;

class Purchase extends Model
{
    use HasFactory, SoftDeletes, ActivityTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'provider_id',
        'quantity',
        'amount',
        'currency',
        'exchange_rate',
        'status',
    ];
    protected $logAttributes = ['provider_id', 'quantity', 'amount', 'currency', 'exchange_rate', 'status'];
    protected $logOnlyDirty = true;
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Purchase {$eventName}";
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('provider_id')) {
            $query->where('provider_id', $request->input('provider_id'));
        }

        if($request->filled('term')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->term . '%');
            });
        }

        return $query;
    }
}
