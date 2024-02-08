<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        return $this->authService->login($request);
    }

    public function verify(LoginVerifyRequest $request)
    {
        return $this->authService->verify($request);
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request);
    }

    public function resendOtp($reference)
    {
        return $this->authService->resendOtp($reference);
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        return $this->authService->forgot($request);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->authService->resetPassword($request);
    }
}
