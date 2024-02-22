<?php

namespace App\Services;

use App\Helpers\LogHelper;
use App\Models\Permission;
use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RoleService
{
    private RoleRepositoryInterface $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function all(Request $request)
    {
        return Cache::remember('roles', 36000, function () {
            return $this->roleRepository->all();
        });
    }

    public function create($data)
    {
        try {
            $role = $this->roleRepository->create($data);
            $role->syncPermissions($data['permissions']);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ROLE_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $id)
    {
        try {
            $role = $this->roleRepository->find($id);
            $role->syncPermissions($data['permissions']);
            $role->revokeToken();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ROLE_UPDATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function delete(Role $role)
    {
        try {
            $role->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'ROLE_DESTROY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }
}
