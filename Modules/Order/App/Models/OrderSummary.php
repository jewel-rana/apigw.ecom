<?php

namespace Modules\Order\App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSummary extends Model
{
    protected $fillable = [
        'service_type_id',
        'operator_id',
        'bundle_id',
        'selling_date',
        'success_items',
        'failed_items',
        'success_amount',
        'failed_amount'
    ];
}
