<?php

namespace Modules\Report\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Auth\Entities\User;

class ReportExport extends Model
{
    use ActivityTrait;

    protected $fillable = [
        'type',
        'user_id',
        'criteria',
        'attachment',
        'remarks',
        'status'
    ];


    protected $casts = [
        'criteria' => 'array',
        'created_at' => 'datetime:d/m/Y h:i a',
        'updated_at' => 'datetime:d/m/Y h:i a'
    ];

    protected static $logAttributes = ['type', 'user_id', 'criteria', 'attachment', 'remarks', 'status'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Report export {$eventName}";
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, $request)
    {
        if($request->filled('from_date')) {
            $fromDate = Carbon::createFromFormat('Y-m-d', $request->input('from_date'));
            $query->where('created_at', '>=', $fromDate->startOfDay());
        }

        if($request->filled('to_date')) {
            $toDate = Carbon::createFromFormat('Y-m-d', $request->input('to_date'));
            $query->where('created_at', '<=', $toDate->endOfDay());
        }

        if($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if($request->filled('keyword')) {
            $query->where('type', 'like', '%' . $request->input('keyword') . '%');
        }
        return $query;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function (ReportExport $export) {
            $export->user_id = auth()->id();
        });
    }
}
