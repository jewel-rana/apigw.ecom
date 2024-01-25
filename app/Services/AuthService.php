<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Constants\AuthConstant;
use App\Models\Otp;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;

class AuthService
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input('email'))->first();

            if (!Hash::check($request->input('password'), $user->password)) {
                throw ValidationException::withMessages(['password' => __('Password does not match')]);
            }
            $otp = CommonHelper::createOtp(['email' => $request->input('email'), 'type' => AuthConstant::LOGIN_OTP_TYPE]);

            return response()->success([
                'reference' => $otp->reference
            ], __('An otp send to your email. please verify otp.'));
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function verify(LoginVerifyRequest $request)
    {
        try {
            $otp = Otp::where('type', AuthConstant::LOGIN_OTP_TYPE)
                ->where('reference', $request->input('reference'))
                ->first();
            if (!$otp || $otp->code != $request->input('otp')) {
                throw ValidationException::withMessages(['otp' => __('OTP does not match')]);
            }
            $vendor = User::where('email', $otp->email)->first();
            return response()->success($vendor->format() +
                [
                    'token' => $vendor->createToken(AuthConstant::TOKEN_NAME)->accessToken
                ]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_VERIFY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function resendOtp($reference)
    {
        try {
            $otp = CommonHelper::createOtp(['reference' => $reference]);
            return response()->success([
                'reference' => $otp->reference
            ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'RESEND_OTP_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }
}
