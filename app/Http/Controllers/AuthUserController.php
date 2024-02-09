<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Services\UserService;

class AuthUserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(UserLoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function verify(LoginVerifyRequest $request)
    {
        return $this->userService->verify($request);
    }

    public function forgot(UserForgotPasswordRequest $request)
    {
        return $this->userService->forgot($request);
    }

    public function resetPassword(UserResetPasswordRequest $request)
    {
        return $this->userService->resetPassword($request);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->userService->changePassword($request);
    }
}
