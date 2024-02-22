<?php

namespace App\Http\Controllers;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Http\Requests\UserActionRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $users = User::where('is_system', false)
                ->filter($request)
            ->orderBy('created_at', $request->order ??'DESC')
            ->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($users));
    }

    public function store(UserCreateRequest $request)
    {
        return $this->userService->create($request->validated() + ['type' => AuthConstant::TYPE_ADMIN]);
    }

    public function show(User $user)
    {
        return response()->success($user->format());
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        return $this->userService->update($request->validated(), $user);
    }

    public function destroy(User $user)
    {
        return $this->userService->delete($user);
    }

    public function action(UserActionRequest $request, $id)
    {
        return $this->userService->userAction($request, $id);
    }

    public function callAction($method, $parameters)
    {
        if($this->authorize($method, User::class)) {
            return parent::callAction($method, $parameters);
        }
    }
}
