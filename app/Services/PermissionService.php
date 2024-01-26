<?php

namespace App\Services;

use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    private PermissionRepositoryInterface $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function all()
    {
        Cache::forget('permissions');
        return Cache::remember('permissions', 36000, function () {
            return $this->permissionRepository->all();
        });
    }
}
