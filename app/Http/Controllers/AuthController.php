<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Services\AuthService;

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

    public function resendOtp($reference)
    {
        return $this->authService->resendOtp($reference);
    }
}
