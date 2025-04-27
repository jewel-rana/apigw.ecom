<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\LogHelper;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\App\Events\LoginEvent;
use Modules\Auth\Constants\AuthConstant;
use Modules\Auth\Entities\Otp;
use Modules\Auth\Entities\User;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Http\Requests\Login2FaRequest;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\ResetEmailRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Interfaces\LoginInterface;
use Modules\Auth\Traits\Auth2FaTrait;
use Modules\Auth\Traits\LoginTrait;

class AuthController extends Controller implements LoginInterface
{
    use Auth2FaTrait, LoginTrait;

    public function index(): View
    {
        return view('auth::index');
    }

    public function login(): View
    {
        return view('auth::auth.login');
    }

    public function check(LoginRequest $request): RedirectResponse
    {
        try {
            if ($this->is2FaEnabled()) {
                $otp = Otp::create([
                    'type' => AuthConstant::LOGIN_OTP_TYPE,
                    'purpose' => AuthConstant::SIGN_IN,
                    'code' => $this->newOtp(),
                    'reference' => $this->generateToken($request->input('email'))
                ]);

                return redirect()->route('auth.2fa', ['token' => $otp->reference])
                    ->with(['message' => 'Login success! please verify 2fa']);
            } else {
                return redirect()->back()->withInput($request->all());
            }
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'AUTH_CHECK_EXCEPTION',
                'data' => $request->all()
            ]);
        }

        return redirect()->route('auth.login');
    }

    public function otp(Request $request): View|RedirectResponse
    {
        $otp = Otp::where('updated_at', '>=', now()->subMinutes(5))
            ->where('type', AuthConstant::LOGIN_OTP_TYPE)
            ->where('reference', $request->input('token'))
            ->first();

        if (!$otp) {
            return redirect()->route('auth.login');
        }

        return view('auth::auth.code', ['token' => $otp->reference]);
    }

    public function verify(Login2FaRequest $request): RedirectResponse
    {
        try {
            $email = $this->decryptToken($request->input('token'));
            $user = User::where('email', $email)->first();
            Auth::guard('web')->login($user, true);

            event(new LoginEvent($user, 'user', $request->all() + ['email' => $email]));
            return redirect()->intended($this->redirectTo());
        } catch (\Exception $exception) {
            LogHelper::exception($exception);
        }

        return redirect()->back()->with(['message' => 'Sorry! could not verify.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.login')->with(['message' => __('auth.logout')]);
    }

    public function profile(): View
    {
        $user = Auth::user();
        return view('auth::user.show', ['user' => $user])->with(['title' => 'My profile']);
    }

    public function changePassword(): View
    {
        $user = Auth::user();
        return view('auth::change-password', compact('user'))
            ->with(['title' => 'Change Password']);

    }

    public function updatePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return redirect()->route('auth.profile')->with(['message' => 'Password has been updated!']);
    }

    public function forgotPassword(): View
    {
        return view('auth::reset-email')->with(['title' => 'Forgot Password']);
    }

    public function resetOtp(ResetEmailRequest $request): RedirectResponse
    {
        $otp = Otp::create([
            'type' => AuthConstant::RESET_OTP_TYPE,
            'purpose' => AuthConstant::RESET_PASSWORD,
            'reference' => $this->generateToken($request->input('email')),
            'code' => $this->newOtp()
        ]);
        return redirect()->route('auth.get-otp', $otp->reference);
    }

    public function getotp($token): View|RedirectResponse
    {
        $otp = Otp::where('updated_at', '>=', now()->subMinutes(5))
            ->where('type', AuthConstant::RESET_OTP_TYPE)
            ->where('reference', $token)
            ->first();
        if (!$otp) {
            return redirect()->route('auth.forgot-password')->with(['message' => 'Sorry! Your OTP code has expired.']);
        }
        return view('auth::reset-otp', ['token' => $token])->with(['title' => 'Reset OTP']);
    }

    public function verifyResetOtp(Login2FaRequest $request): RedirectResponse
    {
        try {
            return redirect()->route('auth.get-reset-password', $request->input('token'));

        } catch (\Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->withInput($request->all())->withErrors($exception->getMessage());
        }

    }

    public function getResetPassword($token): View
    {
        return view('auth::reset-password', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        try {
            $user = User::where('email', $this->decryptToken($request->input('token')))->first();
            $user->update([
                'password' => $request->input('password')
            ]);
            Otp::where('reference', request()->input('token'))->first()->revoked();
            return redirect()->route('auth.login')->with(['message' => 'Password has been updated!']);
        } catch (Exception $exception) {
            LogHelper::exception($exception);
            return redirect()->back()->withInput($request->all())->withErrors($exception->getMessage());
        }
    }
}
