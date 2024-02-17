<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private RoleService $roleService;
    private PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        return response()->success($this->roleService->all($request)
            ->map(function (Role $role) {
                return $role->only(['id', 'name']);
            }));
    }

    public function store(RoleCreateRequest $request)
    {
        return $this->roleService->create($request->validated());
    }

    public function show(Role $role)
    {
        return response()->success($role->only(['id', 'name']) +
            [
                'permissions' => $role->permissions->map(function ($permission) {
                    return $permission->only('id', 'name', 'guard_name');
                })
            ]);
    }

    public function update(RoleUpdateRequest $request, string $id)
    {
        return $this->roleService->create($request->validated(), $id);
    }

    public function destroy(Role $role)
    {
        return $this->roleService->delete($role);
    }

    public function callAction($method, $parameters)
    {
        if($this->authorize($method, Role::class)) {
            return parent::callAction($method, $parameters);
        }
    }
}
