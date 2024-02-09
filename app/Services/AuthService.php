<?php

namespace App\Services;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\Customer;
use App\Models\Otp;
use App\Models\User;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function login(LoginRequest $request)
    {
        try {
            $customer = Customer::where('email', $request->input('email'))->first();
            return response()->success($customer->format() + [
                    'token' => $customer->createToken('authToken')->accessToken,
                    'type' => 'customer'
                ]);
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

    public function register(RegisterRequest $request)
    {
        try {
            $customer = $this->customerRepository->create($request->validated());
            return response()->success($customer->format() + [
                    'token' => $customer->createToken($customer->name)->accessToken
                ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'CUSTOMER_REGISTER_EXCEPTION'
            ]);
            return response()->error(['message' => 'Internal error!']);
        }
    }

    public function logout($request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->success();
        } catch (\Exception $exception) {
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        try {
            $otp = CommonHelper::createOtp(['email' => $request->input('email')]);
            return response()->success([
                'reference' => $otp->reference
            ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FORGOT_PASSWORD_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $otp = Otp::where('reference', $request->input('reference'))->first();
            if(!$otp || now()->addMinutes(5)->lt($otp->created_at)) {
                return response()->error('Sorry! otp does not match or expired');
            }

            $this->customerRepository->getModel()
                ->where('email', $request->input('email'))
                ->update(['password' => Hash::make($request->input('password'))]);
            $otp->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_RESET_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }
}
