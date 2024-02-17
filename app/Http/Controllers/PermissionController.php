<?php

namespace App\Http\Controllers;

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
}
