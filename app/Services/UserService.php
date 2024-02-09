<?php

namespace App\Services;

use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\Otp;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all(Request $request)
    {
        $users = User::filter($request)
            ->latest()
            ->paginate($request->input('per_page', 10));
        return response()->success(CommonHelper::parsePaginator($users));
    }

    public function create($data)
    {
        try {
            $user = $this->userRepository->create($data);
            $user->assignRole(Role::find($data['role_id']));
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, $id)
    {
        try {
            $user = $this->userRepository->update(array_filter($data), $id);
            $user->syncRoles(Role::find($data['role_id']));
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_UPDATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_DESTROY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input('email'))->first();
            return response()->success($user->format() + [
                    'type' => 'user',
                    'token' => $user->createToken('authToken')->accessToken,
                    'role' => $user->roles->first()->name ?? '',
                    'permission' => $user->getAllPermissions()->map(function (Permission $permission) {
                        return $permission->only(['id', 'name']);
                    })
                ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_LOGIN_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function userAction($request, $id)
    {
        try {
            $this->userRepository->update($request->validated() + ['status' => $request->input('action')], $id);
            if ($request->input('action') != 'Active') {
                DB::table('oauth_access_tokens')->where('id', $id)->update(['revoked' => 1]);
            }
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_ACTION_EXCEPTION'
            ]);
        }
    }

    public function forgot(ForgotPasswordRequest $request)
    {
        try {
            $otp = CommonHelper::createOtp(['email' => $request->input('email')]);
            return response()->success([
                'reference' => $otp->reference
            ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'FORGOT_PASSWORD_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $otp = Otp::where('reference', $request->input('reference'))->first();
            if(!$otp || now()->addMinutes(5)->lt($otp->created_at)) {
                return response()->error('Sorry! otp does not match or expired');
            }

            $this->userRepository->getModel()
                ->where('email', $request->input('email'))
                ->update(['password' => Hash::make($request->input('password'))]);
            $otp->update(['code' => mt_rand(111111,999999)]);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_RESET_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }
}
