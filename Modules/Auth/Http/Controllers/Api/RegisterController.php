<?php

namespace Modules\Auth\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Auth\App\Http\Requests\RegisterRequest;
use Modules\Auth\Services\CustomerAuthService as AuthService;

class RegisterController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->authService->register($request);
    }
}
