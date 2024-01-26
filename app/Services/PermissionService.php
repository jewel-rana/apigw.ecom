<?php

namespace App\Services;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class PermissionService
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function all()
    {
        return Cache::remember('permissions', 36000, function () {
            return $this->roleRepository->all();
        });
    }
}
