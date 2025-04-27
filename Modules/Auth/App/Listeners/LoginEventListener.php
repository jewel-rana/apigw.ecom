<?php

namespace Modules\Auth\App\Listeners;

use App\Helpers\LogHelper;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Auth\App\Events\LoginEvent;
use Modules\Auth\App\Models\LoginActivity;
use Modules\Auth\Entities\Otp;

class LoginEventListener
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(LoginEvent $event): void
    {
        try {
            LoginActivity::create([
                'type' => $this->getType($event->type),
                'user_id' => $event->user->id,
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
                'payload' => $event->payload
            ]);
            $otp = Otp::where('email', request()->input('token'))->where('revoked', false)->first();
            if($otp) {
                $otp->revoked();
            }
        } catch (\Exception $e) {
            LogHelper::error($e->getMessage(), [
                'keyword' => 'LOGIN_ACTIVITY_FAILED'
            ]);
        }
    }

    private function getType($type): string
    {
        $class = "\\Modules\\Customer\\App\\Models\\Customer";
        if($type != 'customer') {
            $class = "\\Modules\\Auth\\Entities\\User";
        }

        if(!class_exists($class)) {
            $class = "\\App\\Models\\User";
        }
        return get_class( new $class);
    }
}
