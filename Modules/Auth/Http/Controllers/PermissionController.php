<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Http\Requests\PermissionCreateRequest;
use Modules\Auth\Http\Requests\PermissionUpdateRequest;
use Modules\Auth\Services\PermissionService;

class PermissionController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            return $this->permissionService->getDataTable($request);
        }
        return view('auth::permission.index');
    }

    public function create()
    {
        return view('auth::permission.create');
    }

    public function store(PermissionCreateRequest $request): RedirectResponse
    {
        return $this->permissionService->create($request->validated());
    }

    public function show(Permission $permission)
    {
        return view('auth::permission.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('auth::permission.edit', compact('permission'));
    }

    public function update(PermissionUpdateRequest $request, Permission $permission): RedirectResponse
    {
        return $this->permissionService->update($request->validated(), $permission);
    }

    public function destroy($id)
    {
        //
    }
}
