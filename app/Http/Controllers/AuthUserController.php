<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LoginVerifyRequest;
use App\Services\UserService;

class AuthUserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        return $this->userService->login($request);
    }

    public function verify(LoginVerifyRequest $request)
    {
        return $this->userService->verify($request);
    }
}
