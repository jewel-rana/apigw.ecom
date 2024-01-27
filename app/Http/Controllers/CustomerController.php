<?php

namespace App\Http\Controllers;

use App\Constants\AuthConstant;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        return $this->customerService->all($request);
    }

    public function store(UserCreateRequest $request)
    {
        return $this->customerService->create($request->validated());
    }

    public function show(User $user)
    {
        return response()->success($user->format());
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        return $this->customerService->update($request->validated(), $id);
    }

    public function destroy(User $user)
    {
        return $this->customerService->delete($user);
    }
}
