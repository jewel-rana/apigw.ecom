<?php

namespace Modules\Auth\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\App\Events\LoginEvent;
use Modules\Auth\App\Http\Requests\RegisterRequest;
use Modules\Auth\Constants\AuthConstant;
use Modules\Auth\Entities\Otp;
use Modules\Auth\Http\Requests\LoginVerifyRequest;
use Modules\Auth\Traits\Auth2FaTrait;
use Modules\Customer\App\Models\Customer;

class CustomerAuthService
{
    use Auth2FaTrait;

    public function login($request)
    {
        try {
            $otp = CommonHelper::createOtp([
                'reference' => $this->generateToken($request->input('email')),
                'type' => AuthConstant::CUSTOMER_LOGIN_OTP_TYPE
            ]);

            return response()->success(['reference' => $otp->reference], __('otp_sent'));
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_LOGIN_EXCEPTION'
            ]);
            return response()->error(['message' => __('internal_server_error')]);
        }
    }

    public function forgot($request)
    {
        try {
            $otp = CommonHelper::createOtp([
                'reference' => $this->generateToken($request->input('email')),
                'type' => AuthConstant::CUSTOMER_FORGOT_OTP_TYPE
            ]);

            return response()->success(['reference' => $otp->reference], __('otp_sent'));
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_FORGOT_EXCEPTION'
            ]);
            return response()->error(['message' => __('internal_server_error')]);
        }
    }

    public function verify(LoginVerifyRequest $request)
    {
        try {
            $otp = Otp::where('reference', $request->input('reference'))->first();

            if(!in_array($otp->type, [AuthConstant::CUSTOMER_REGISTER_OTP_TYPE, AuthConstant::CUSTOMER_LOGIN_OTP_TYPE])) {
                $otp->revoked();
                return response()->error(['message' => __('Sorry! invalid otp type')]);
            }

            $customer = Customer::where('email', $this->decryptToken($otp->reference))->firstOrFail();

            if(!in_array($customer->status, [AuthConstant::CUSTOMER_ACTIVE, AuthConstant::CUSTOMER_PENDING])) {
                $otp->revoked();
                return response()->error(['message' => __('Your account is :status', ['status', $customer])]);
            }

            if ($customer->status == AuthConstant::CUSTOMER_PENDING) {
                $customer->update([
                    'status' => AuthConstant::CUSTOMER_ACTIVE,
                    'email_verified_at' => now()
                ]);
            }

            //Revoke OTP
            $otp->revoked();

            event(new LoginEvent($customer, 'customer', $request->all()));
            return response()->success($customer->format() +
                [
                    'token' => $customer->createToken(AuthConstant::TOKEN_NAME)->accessToken
                ]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_VERIFY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function reset($request)
    {
        try {
            $otp = Otp::where('reference', $request->input('reference'))->first();

            if($otp->type == AuthConstant::CUSTOMER_FORGOT_OTP_TYPE) {
                $otp->revoked();
                return response()->error(['message' => __('Sorry! invalid otp type')]);
            }

            $customer = Customer::where('email', $this->decryptToken($otp->reference))->firstOrFail();

            $customer->update(['password' => Hash::make($request->input('password'))]);

            //Revoke OTP
            $otp->revoked();

            event(new LoginEvent($customer, 'customer', $request->all()));
            return response()->success($customer->format() +
                [
                    'token' => $customer->createToken(AuthConstant::TOKEN_NAME)->accessToken
                ]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_RESET_EXCEPTION'
            ]);
            return response()->error(['message' => __('Internal server error!')]);
        }
    }

    public function logout($request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error(['message' => __('Internal server error!')]);
        }
    }

    public function resendOtp($reference)
    {
        try {
            $otp = CommonHelper::createOtp([
                'reference' => $reference
            ]);
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

    public function register(RegisterRequest $request)
    {
        try {
            $customer = Customer::create($request->validated());
            if ($customer) {
                $otp = CommonHelper::createOtp([
                    'reference' => $this->generateToken($request->input('email')),
                    'type' => AuthConstant::CUSTOMER_REGISTER_OTP_TYPE
                ]);

                return response()->success(
                    [
                        'reference' => $otp->reference
                    ]
                );
            }
            return response()->failed(['message' => __('register_failed')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_REGISTER_EXCEPTION'
            ]);
            return response()->error();
        }
    }

    public function updatePassword($request)
    {
        try {
            $customer = auth()->user();
            $customer->update(['password' => Hash::make($request->input('password'))]);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'UPDATE_PASSWORD_EXCEPTION'
            ]);
            return response()->failed();
        }
    }
}
