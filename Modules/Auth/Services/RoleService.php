<?php

namespace Modules\Auth\Services;

use App\Constants\LogConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\Permission;
use Modules\Auth\Entities\Role;

class RoleService
{
    public function all()
    {
        Cache::forget('roles');
        return Cache::rememberForever('roles', function () {
            return Role::all();
        });
    }

    public function create(array $data): RedirectResponse
    {
        try {
            DB::transaction(function () use ($data) {
                $role = Role::create($data + ['guard_name' => 'web']);
                $role->givePermissionTo(
                    Permission::whereIn('id', $data['permission'])->get()
                );
            });
            return redirect()->route('role.index')->with(['status' => true, 'message' => __('Role successfully created')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->with(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function update(array $data, Role $role): RedirectResponse
    {
        try {
            DB::transaction(function () use ($data, $role) {
                $role->update($data);
                $role->syncPermissions(
                    Permission::whereIn('id', $data['permission'])->get()
                );
            });

            return redirect()->route('role.index')->with(['status' => true,'message' => __('Role successfully updated')]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->withErrors(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            Role::with('permissions')
        )
            ->addColumn('permissions', function (Role $role) {
                $str = '';
                foreach($role->permissions as $permission) {
                    $str .= "<span class='badge badge-primary'>{$permission->name}</span> ";
                }
                return $str;
            })
            ->addColumn('actions', function(Role $role) {
                if (CommonHelper::hasPermission(['role-update'])) {
                    return "<a href='" . route('role.edit', $role->id) . "' class='btn btn-primary'><i class='fa fa-edit'></i></a>";
                }
                return '';
            })
            ->rawColumns(['actions', 'permissions'])
            ->toJson();
    }

    public function getPermissions(): array
    {
        $array = [];
        $permissions = (new PermissionService())->all();
        foreach ($permissions as $q) {
            $param = explode('-', $q->name);
            $array[$param[0]][] = $q;
        }
        return $array;
    }

    public function getSuggestions(Request $request): JsonResponse
    {
        try {
            $data = $this->all()->filter(function ($role) use ($request) {
                $matched = true;
                if ($request->has('term')) {
                    $matched = CommonHelper::matchText($role->name, $request->input('term'));
                }

                return $matched;
            })
                ->map(function ($item, $key) {
                    return [
                        'id' => $item->id,
                        'text' => $item->name
                    ];
                })->values();
            return response()->json(['results' => $data]);
        } catch (\Exception $exception) {
            return response()->json(['message' => __('No data!'), 'results' => []]);
        }
    }
}
