<?php

namespace Modules\Auth\Services;

use App\Constants\LogConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Auth\Entities\Role;
use Modules\Auth\Entities\User;
use Modules\Auth\Repositories\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(array $data): RedirectResponse
    {
        try {
            DB::transaction(function() use ($data) {
                $user = $this->userRepository->create($data);
                $user->assignRole(Role::find($data['role']));
            });
            return redirect()->route('user.index')->with(['status' => true, 'message' => 'User successfully created']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->withErrors(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function update(array $data, $id): RedirectResponse
    {
        try {
            DB::transaction(function() use ($data, $id) {
                $user = $this->userRepository->update(Arr::except(array_filter($data), ['role']), $id);
                if (array_key_exists('role', $data) && $user) {
                    $user->syncRoles(Role::find($data['role']));
                }
            });
            return redirect()->route('user.index')->with(['status' => true, 'message' => 'User successfully updated']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => LogConstant::EXCEPTION_GENERAL
            ]);
            return redirect()->back()->withInput($data)->with(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    public function getDataTable($request): JsonResponse
    {
        return datatables()->eloquent(
            $this->userRepository->getModel()->filter($request)
        )
            ->addColumn('role', function (User $user) {
                return $user->roles->first()->name ?? '---';
            })
            ->addColumn('status', function (User $user) {
                return $user->nice_status;
            })
            ->addColumn('actions', function(User $user) {
                $str = '';
                if(CommonHelper::hasPermission(['user-update']) && $user->actionPermitted()) {
                    $str .= "<a href='" . route('user.edit', $user->id) . "' class='btn btn-primary'><i class='fa fa-edit'></i></a>";
                }
                if(CommonHelper::hasPermission(['user-action']) && $user->actionPermitted()) {
                    if($user->isActive()) {
                        $str .= "<button data-url='" . route('user.edit', $user->id) . "' data-action='active' class='btn btn-danger'><i class='fa fa-times'></i></button>";
                    } else {
                        $str .= "<button data-url='" . route('user.edit', $user->id) . "' data-action='active' class='btn btn-success'><i class='fa fa-check'></i></button>";
                    }
                }
                return $str;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
