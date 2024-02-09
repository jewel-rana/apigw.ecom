<?php

namespace App\Helpers;

use App\Models\Order;
use App\Models\Otp;
use App\Notifications\OtpNotification;

class CommonHelper
{
    public static function parsePaginator($collections = null): array
    {
        return [
            'from' => $collections->firstItem() ?? 0,
            'to' => $collections->lastItem() ?? 0,
            'per_page' => $collections->perPage(),
            'current_page' => $collections->currentPage(),
            'last_page' => $collections->lastPage(),
            'total' => $collections->total(),
            'data' => collect($collections->items())->map(function($item) {
                return $item->format();
            })
        ];
    }

    public static function createOtp($data)
    {
        return Otp::updateOrCreate($data,
            [
                'code' => self::generateOtp(),
            ]
        );
    }

    public static function generateOtp(): int
    {
        return app()->environment('local') ? 123456 : mt_rand(111111, 999999);
    }

    public static function batchActionButtons(Batch $batch): string
    {
        $btns = "<a href='" . route('batch.action', [$batch->id, 'action' => 'cancel']) . "' class='btn btn-danger'><i class='fa fa-times'></i> Cancel</a>";
        switch ($batch->status) {
            case 4:
                $btns .= "<a href='" . route('batch.action', [$batch->id, 'action' => 'process']) . "' class='btn btn-success'><i class='fa fa-check'></i>Process</a>";
                break;
            case 8:
                $btns .= "<a href='" . route('batch.action', [$batch->id, 'action' => 'activate']) . "' class='btn btn-success'><i class='fa fa-check'></i>Start selling</a>";
                break;
            default:
                if (in_array($batch->status, [1, 9, 99])) {
                    $btns = "";
                }
        }
        return $btns;
    }

    public static function matchText(string $subject, string $match): bool
    {
        preg_match("/(" . strtolower($match) . ")/", strtolower($subject), $matches, PREG_OFFSET_CAPTURE);
        return (bool)$matches;
    }

    public static function filterModel($query, $request)
    {
        if ($request->filled('from')) {
            $query->where('created_at', '>=', $request->input('from') . ' 00:00:00');
        }
        if ($request->filled('to')) {
            $query->where('created_at', '<=', $request->input('to') . ' 23:59:59');
        }
        if ($request->filled('email')) {
            $query->where('email', '=', $request->input('email'));
        }
        if ($request->filled('mobile')) {
            $query->where('mobile', 'like', "%" . $request->input('mobile') . "%");
        }
        if ($request->filled('status') && in_array(strtolower($request->input('status')), ['pending', 'active', 'inactive'])) {
            $query->where('status', '=', ucfirst($request->input('status')));
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', '=', $request->input('created_by'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->input('keyword') . "%");
            });
        }
        return $query;
    }
}
