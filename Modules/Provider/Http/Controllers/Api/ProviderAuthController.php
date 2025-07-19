<?php

namespace Modules\Provider\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\App\Http\Requests\Api\CustomerForgotRequest;
use Modules\Auth\App\Http\Requests\Api\UpdatePasswordRequest;
use Modules\Auth\App\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\CustomerLoginRequest;
use Modules\Auth\Http\Requests\LoginVerifyRequest;
use Modules\Provider\Http\Requests\Api\ProviderForgotRequest;
use Modules\Provider\Http\Requests\Api\ProviderLoginRequest;
use Modules\Provider\Services\ProviderAuthService;

class ProviderAuthController extends Controller
{
    private ProviderAuthService $authService;

    public function __construct(ProviderAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(ProviderLoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function forgot(ProviderForgotRequest $request)
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
