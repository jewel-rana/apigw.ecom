<?php

namespace App\Services;

use App\Constants\AuthConstant;
use App\Helpers\CommonHelper;
use App\Helpers\LogHelper;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\OtpVerifyRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Models\Customer;
use App\Models\Otp;
use App\Models\Role;
use App\Models\User;
use App\Notifications\OtpNotification;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
            ->paginate(CommonHelper::perPage($request));
        return response()->success(CommonHelper::parsePaginator($users));
    }

    public function create($data)
    {
        try {
            $user = $this->userRepository->create($data + ['email_verified_at' => now()]);
            $user->assignRole(Role::find($data['role_id']));
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_CREATE_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function update($data, User $user)
    {
        try {
            $this->userRepository->update(array_filter($data), $user->id);
            $user->syncRoles(Role::find($data['role_id']));
            if($user->roles->first()->id !== $data['role_id']) {
                $user->revokeToken();
            }
            $user->update(['updated_by' => \request()->user()->id, 'updated_at' => Carbon::now()]);
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
            CommonHelper::revokeUserToken($user->id);
            return response()->success($user->format() + [
                    'type' => 'user',
                    'token' => $user->createToken('authToken', $user->getPermissions())->accessToken,
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

    public function forgot(UserForgotPasswordRequest $request)
    {
        try {
            $otp = CommonHelper::createOtp(['email' => $request->input('email'), 'type' => 'user.forgot']);
            User::where('email', $request->input('email'))->first()
                ->notify(new OtpNotification($otp));
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

    public function verify(OtpVerifyRequest $request)
    {
        try {
            $otp = Otp::where('type', AuthConstant::USER_FORGOT_OTP_TYPE)
                ->where('reference', $request->input('reference'))
                ->first();
            $otp->update(['status' => AuthConstant::OTP_VERIFIED]);
            return response()->success(
                [
                    'reference' => $otp->reference
                ]
            );
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'VENDOR_LOGIN_VERIFY_EXCEPTION'
            ]);
            return response()->error(['message' => $exception->getMessage()]);
        }
    }

    public function resetPassword(UserResetPasswordRequest $request)
    {
        try {
            $otp = Otp::where(['reference' => $request->input('reference'), 'type' => AuthConstant::USER_FORGOT_OTP_TYPE])->first();

            $this->userRepository->getModel()
                ->where('email', $request->input('email'))
                ->update(['password' => Hash::make($request->input('password'))]);
            $otp->delete();
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'PASSWORD_RESET_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            User::find($request->user()->id)->update(['password' => Hash::make($request->input('password'))]);
            return response()->success();
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'USER_PASSWORD_CHANGE_EXCEPTION'
            ]);
            return response()->error('Internal error!');
        }
    }
}
