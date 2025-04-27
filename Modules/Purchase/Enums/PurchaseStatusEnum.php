<?php

namespace Modules\Purchase\Enums;

enum PurchaseStatusEnum: string
{
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case CANCELLED = 'Canceled';

    public static function statuses(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
