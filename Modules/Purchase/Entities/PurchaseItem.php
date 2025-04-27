<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Bundle\Entities\Bundle;
use Modules\Operator\Entities\Operator;

class PurchaseItem extends Model
{
    use HasFactory, SoftDeletes, ActivityTrait;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'purchase_id',
        'operator_id',
        'bundle_id',
        'quantity',
        'unit_price',
        'amount',
    ];
    protected $logAttributes = ['quantity', 'unit_price', 'amount','purchase_id'];
    protected $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Purchase Item {$eventName}";
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(Operator::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('purchase_id')){
            $query->where('purchase_id',$request->input('purchase_id'));
        }
        return $query;
    }
}
