<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\UserCreateRequest;
use Modules\Auth\Http\Requests\UserUpdateRequest;
use Modules\Auth\Services\RoleService;
use Modules\Auth\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;
    private RoleService $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            return $this->userService->getDataTable($request);
        }
        return view('auth::user.index');
    }

    public function create()
    {
        $roles = $this->roleService->all();
        return view('auth::user.create', compact('roles'));
    }

    public function store(UserCreateRequest $request): RedirectResponse
    {
        return $this->userService->create($request->validated());
    }

    public function show(User $user)
    {
        return view('auth::user.show', compact('user'));
    }

    public function edit(User $user)
    {
        abort_if(!$user->is_editable, 403);
        $roles = $this->roleService->all();
        return view('auth::user.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, $id): RedirectResponse
    {
        return $this->userService->update($request->validated(), $id);
    }

    public function destroy($id)
    {
        //
    }

//    public function callAction($method, $parameters)
//    {
//        if (!in_array($method, ['suggestions'])) {
//            $this->authorize($method, User::class);
//        }
//        return parent::callAction($method, $parameters);
//    }
}
