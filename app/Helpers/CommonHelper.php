<?php

namespace App\Helpers;

use App\Constants\AuthConstant;
use App\Models\Order;
use App\Models\Otp;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonHelper
{
    public static function perPage(Request $request)
    {
        return (!$request->has('per_age') || $request->input('per_page', 10) > 50) ? 10 : $request->input('per_page');
    }

    public static function getPromotionLogo($promotion = 'facebook'): string
    {
        return asset('images/' . strtolower($promotion) . '.png');
    }

    public static function revokeUserToken($userId)
    {
        try {
            DB::table('oauth_access_tokens')
                ->where('user_id', $userId)
                ->delete();
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
        }
    }

    public static function isHierarchyOk(): bool
    {
        $userRole = request()->user()->roles->first()->id;
        return match ($userRole) {
            1 => true,
            default => request()->input('role_id') > 2
        };
    }

    public static function hasPermission($permissions): bool
    {
        if(is_array($permissions)) {
            $user = request()->user();
            return $user->hasRole('admin') || $user->canAny($permissions);
        }

        return request()->user()->status == AuthConstant::STATUS_ACTIVE && (request()->user()->hasRole('admin') || request()->user()->tokenCan($permissions));
    }

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
                'status' => 0
            ]
        );
    }

    public static function generateOtp(): int
    {
        return app()->environment('local') ? 123456 : mt_rand(111111, 999999);
    }

    public static function batchActionButtons($batch): string
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
        if($request->filled('invoice_no')) {
            $query->where('id', $request->input('invoice_no'));
        }

        if($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if($request->filled('order_id')) {
            $query->where('order_id', $request->input('order_id'));
        }

        if($request->filled('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }

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

        if ($request->filled('status') && in_array(strtolower($request->input('status')), ['pending', 'active', 'inactive', 'publish', 'failed', 'complete', 'refunded'])) {
            $query->where('status', '=', ucfirst($request->input('status')));
        }

        if ($request->filled('created_by')) {
            $query->where('created_by', '=', $request->input('created_by'));
        }

        if ($request->filled('promotion')) {
            $query->where('promotion', '=', $request->input('promotion'));
        }

        if ($request->filled('promotion_objective')) {
            $query->where('promotion_objective', '=', $request->input('promotion_objective'));
        }

        if($request->filled('company')) {
            $query->where('company', $request->input('company'));
        }

        if($request->filled('designation')) {
            $query->where('designation', $request->input('designation'));
        }

        if ($request->filled('keyword')) {
            $query->where(function ($query) use ($request) {
                $query->where('name', 'like', "%" . $request->input('keyword') . "%");
            });
        }

        return $query;
    }
}
