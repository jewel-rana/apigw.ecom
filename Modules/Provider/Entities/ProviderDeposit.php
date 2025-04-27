<?php

namespace Modules\Provider\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;

class ProviderDeposit extends Model
{
    use ActivityTrait;
    protected $fillable = [
        'provider_id',
        'currency',
        'voucher_number',
        'amount',
        'currency_rate',
        'amount_iqd',
        'previous_balance',
        'current_balance'
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];
    protected $logAttributes = ['currency', 'voucher_number', 'amount'];
    protected $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Provider Deposit {$eventName}";
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('provider_id')) {
            $query->where('provider_id', $request->input('provider_id'));
        }
        return $query;
    }
}
