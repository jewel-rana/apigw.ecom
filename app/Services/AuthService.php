<?php

namespace App\Services;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\Customer;
use App\Models\Otp;
use App\Models\User;
use App\Notifications\OtpNotification;
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
                    'token' => $customer->createToken('authToken', ['order-list', 'order-create'])->accessToken,
                    'type' => 'customer'
                ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_EXCEPTION'
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
            $otp = CommonHelper::createOtp(['email' => $request->input('email'), 'type' => 'customer.forgot']);
            Customer::where(['email' => $request->input('email')])->first()
                ->notify(new OtpNotification($otp));
            return response()->success([
                'reference' => $otp->reference
            ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FORGOT_PASSWORD_EXCEPTION'
            ]);
            return response()->error(null, 'Internal error!');
        }
    }

    public function verify(OtpVerifyRequest $request)
    {
        try {
            $otp = Otp::where('type', AuthConstant::CUSTOMER_FORGOT_OTP_TYPE)
                ->where('reference', $request->input('reference'))
                ->first();
            $otp->update(['status' => AuthConstant::OTP_VERIFIED]);
            return response()->success(
                [
                    'reference' => $otp->reference
                ]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_VERIFY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $this->customerRepository->getModel()
                ->where('email', $request->input('email'))
                ->update(['password' => Hash::make($request->input('password'))]);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_RESET_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $request->user()->update(['password' => Hash::make($request->input('password'))]);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_CHANGE_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }
}
