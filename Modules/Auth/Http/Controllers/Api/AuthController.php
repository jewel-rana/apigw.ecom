<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Auth\App\Http\Requests\Api\CustomerForgotRequest;
use Modules\Auth\App\Http\Requests\Api\UpdatePasswordRequest;
use Modules\Auth\App\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\CustomerLoginRequest;
use Modules\Auth\Http\Requests\LoginVerifyRequest;
use Modules\Auth\Services\CustomerAuthService;

class AuthController extends Controller
{
    private CustomerAuthService $authService;

    public function __construct(CustomerAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(CustomerLoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function forgot(CustomerForgotRequest $request)
    {
        return $this->authService->login($request);
    }

    public function verify(LoginVerifyRequest $request)
    {
        return $this->authService->verify($request);
    }

    public function reset(ResetPasswordRequest $request)
    {
        return $this->authService->reset($request);
    }

    public function resendOtp($reference)
    {
        return $this->authService->resendOtp($reference);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    public function changePassword(UpdatePasswordRequest $request)
    {
        return $this->authService->updatePassword($request);
    }
}
