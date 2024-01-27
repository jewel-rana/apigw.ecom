<?php

namespace App\Helpers;

use App\Models\Otp;

class CommonHelper
{
    public static function parsePaginator($collections = null): array
    {
        return [
            'from' => $collections->firstItem(),
            'to' => $collections->lastItem(),
            'per_page' => $collections->perPage(),
            'current_page' => $collections->currentPage(),
            'last_page' => $collections->lastPage(),
            'total' => $collections->total(),
            'data' => $collections->items()
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

    public static function batchActionButtons(Batch $batch)
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

    public static function sanitizePayload(array $payload): array
    {
        $payload = preg_replace("/(')*/", "", array_filter($payload));
        return preg_replace('/(")*/', "", array_filter($payload));
    }

    public static function unlinkFiles($paths = null, array $extensions = [], $recursive = true): void
    {
        $paths = $paths ?? [public_path('uploads'), public_path('public'), storage_path('app/public'), env('FILE_STORAGE_PATH')];
        $extensions = empty($extensions) ? ['csv', 'txt', 'xls', 'xlsx'] : $extensions;
        $files = ScanDir::scan($paths, $extensions, $recursive);
        foreach (array_unique($files) as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
