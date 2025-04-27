<?php

namespace Modules\Provider\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Activity\App\Traits\ActivityTrait;

class ProviderUser extends Model
{
    use ActivityTrait;
    protected $fillable = [
        'provider_id',
        'name',
        'email',
        'mobile',
        'password',
        'status'
    ];
    protected $logAttributes = ['name', 'email','mobile','password','status','provider_id'];
    protected $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Provider User {$eventName}";
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
