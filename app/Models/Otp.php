<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

class Otp extends Model
{
    protected $fillable = ['type', 'email', 'code', 'reference', 'status'];


    public static function boot()
    {
        parent::boot();

        static::creating(function (Otp $otp) {
            $otp->reference = $otp->uniqueStockId();
        });
    }

    private function uniqueStockId(): UuidInterface
    {
        while(1) {
            $uuid = Str::uuid();
            if(!self::where('reference', $uuid)->count()) {
                break;
            }
        }
        return $uuid;
    }
}
