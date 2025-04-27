<?php

namespace Modules\Auth\Entities;

use App\Helpers\CommonHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Activity\App\Traits\ActivityTrait;
use Modules\Auth\App\Notifications\OtpNotification;
use Modules\Auth\Constants\AuthConstant;
use Modules\Auth\Traits\Auth2FaTrait;
use Modules\Customer\App\Models\Customer;

class Otp extends Model
{
    use Auth2FaTrait, ActivityTrait;

    protected $fillable = ['type', 'reference', 'code', 'purpose', 'revoked'];

    protected static $logAttributes = ['type', 'code', 'purpose', 'revoked'];
    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "OTP {$eventName}";
    }

    public function getCreatedAtAttribute($datetime): string
    {
        return CommonHelper::parseLocalTimeZone($datetime);
    }

    public function revoked()
    {
        DB::table('otps')->where('id', $this->id)->update(['revoked' => true]);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function (Otp $otp) {
            $otp->notify($otp);
        });

        static::updated(function (Otp $otp) {
            $otp->notify($otp);
        });
    }

    public function notify($otp): void
    {
        if($otp && app()->environment(['production', 'staging', 'development', 'local'])) {
            switch ($otp->type) {
                case AuthConstant::RESET_OTP_TYPE:
                case AuthConstant::LOGIN_OTP_TYPE :
                    User::where('email', $this->decryptToken($otp->reference))->first()
                        ->notify(new OtpNotification($otp));
                    break;
                default:
                    Customer::where('email', $this->decryptToken($otp->reference))->first()
                        ->notify(new OtpNotification($otp));
                    break;
            }
        }
    }
}
