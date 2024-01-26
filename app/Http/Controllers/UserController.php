<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        return response()->success($this->userService->all());
    }

    public function store(UserCreateRequest $request)
    {
        return $this->userService->create($request->validated());
    }

    public function show(User $user)
    {
        return response()->success($user->only(['id', 'name', 'mobile', 'email', 'status']));
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        return $this->userService->update($request->validated(), $id);
    }

    public function destroy(User $user)
    {
        return $this->userService->delete($user);
    }
}
