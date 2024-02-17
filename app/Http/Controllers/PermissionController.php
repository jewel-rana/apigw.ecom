<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        return response()->success($this->permissionService->format());
    }

    public function callAction($method, $parameters)
    {
        if($this->authorize($method, Permission::class)) {
            return parent::callAction($method, $parameters);
        }
    }
}
