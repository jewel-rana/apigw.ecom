<?php

namespace App\Services;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input('email'))->first();

            if (!Hash::check($request->input('password'), $user->password)) {
                throw ValidationException::withMessages(['password' => __('Password does not match')]);
            }
            $otp = Otp::updateOrCreate(
                [
                    'type' => AuthConstant::LOGIN_OTP_TYPE,
                    'email' => $request->input('email')
                ],
                [
                    'code' => CommonHelper::generateOtp(),
                ]
            );

            return [
                'status' => true,
                'message' => __('An otp send to your email. please verify otp.'),
                'reference' => $otp->reference
            ];
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_EXCEPTION'
            ]);
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

            $user = User::where('email', $otp->email)->first();
            return [
                'status' => true,
                'message' => __('Login successful'),
                'data' => $user->format() +
                    [
                        'token' => $user->createToken(AuthConstant::TOKEN_NAME)->accessToken
                    ]
            ];
        } catch (\Exception $exception) {
            dd($exception);
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_VERIFY_EXCEPTION'
            ]);
        }
    }
}
