<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Role;
use Modules\Auth\Http\Requests\RoleCreateRequest;
use Modules\Auth\Http\Requests\RoleUpdateRequest;
use Modules\Auth\Services\RoleService;

class RoleController extends Controller
{
//    use AuthorizesRequests, ValidatesRequests;
    private RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            return $this->roleService->getDataTable($request);
        }
        return view('auth::role.index');
    }

    public function create()
    {
        $permissions = $this->roleService->getPermissions();
        return view('auth::role.create', compact('permissions'));
    }

    public function store(RoleCreateRequest $request): RedirectResponse
    {
        return $this->roleService->create($request->validated());
    }

    public function show(Role $role)
    {
        return view('auth::role.show', compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = $this->roleService->getPermissions();
        $rolePermissions = $role->permissions->pluck('id', 'id')->all();
        return view('auth::role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        return $this->roleService->update($request->validated(), $role);
    }

    public function destroy($id)
    {
        //
    }

    public function suggestions(Request $request): JsonResponse
    {
        return $this->roleService->getSuggestions($request);
    }

//    public function callAction($method, $parameters)
//    {
//        if (!in_array($method, ['attachVendor', 'stockSuggestions', 'suggestions'])) {
//            $this->authorize($method, Role::class);
//        }
//        return parent::callAction($method, $parameters);
//    }
}
