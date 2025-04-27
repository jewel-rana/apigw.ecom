<?php

namespace Modules\Auth\Services;

use App\Helpers\LogHelper;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\App\Events\LoginEvent;
use Modules\Auth\App\Models\Socialite as SocialLogin;
use Modules\Auth\Constants\AuthConstant;
use Modules\Customer\App\Repositories\Interfaces\CustomerRepositoryInterface;

class SocialAuthService
{
    private CustomerRepositoryInterface $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function check($request)
    {
        try {
            $user = Socialite::driver($request->input('provider'))
                ->userFromToken($request->input('access_token'));
            //LogHelper::debug($user);
            if ($user) {
                $social = SocialLogin::where('social_id', $user->getId())->latest()->first();
                $customer = $social->customer ?? null;
                $payload = [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'mobile' => $this->parseMobile($user)
                ];

                if ($social) {
                    $social->update($request->validated() +
                        [
                            'social_id' => $user->getId(),
                            'payload' => $payload,
                            'revoked' => false
                        ]
                    );

                    if ($customer && $request->input('provider') != 'fib') {

                        $data = $customer->format();
                        if (strtolower($request->input('provider')) != 'fib') {
                            $social->revoke();
                            event(new LoginEvent($customer, 'customer', $request->all()));
                            $data['token'] = $customer->createToken(AuthConstant::TOKEN_NAME)->accessToken;
                        }
                    } else {
                        $data = [
                            'is_first_time' => $customer == null,
                            'profile_id' => $social->uuid,
                            'info' => Arr::except($social->payload, ['mobile']),
                        ];
                    }

                    return response()->success($data);
                } else {
                    $social = SocialLogin::updateOrCreate(
                        [
                            'social_id' => $user->getId(),
                            'provider' => $request->input('provider')
                        ],
                        $request->validated() +
                        [
                            'payload' => $payload,
                            'revoked' => false
                        ]
                    );

                    return response()->success([
                        'is_first_time' => true,
                        'profile_id' => $social->uuid,
                        'info' => Arr::except($social->payload, ['mobile']),
                    ]);
                }
            }
            return response()->failed(['message' => __('Unauthorized'), 'code' => 401]);
        } catch (UnauthorizedException $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'SOCIALITE_UNAUTHORIZED_EXCEPTION'
            ]);
            return response()->failed(['message' => __('Unauthorized'), 'code' => 401]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'SOCIAL_LOGIN_FETCH_EXCEPTION'
            ]);
            return response()->failed(['message' => __('Internal server error!'), 'code' => 500]);
        }
    }

    public function update($request)
    {
        try {
            $socialite = SocialLogin::where('uuid', $request->input('profile_id'))->latest()->first();

            if (app()->environment('production')) {
                if ($socialite->revoked) {
                    throw new \Exception(__('You are revoked. please login first'), 422);
                }

                if ($socialite->updated_at->lte(now()->subMinutes(2))) {
                    throw new \Exception(__('Your session expired'), 401);
                }
            }

            $customer = $this->customerRepository->getModel()->updateOrCreate(
                [
                    'email' => $request->input('email')
                ],
                array_filter($request->validated()) +
                [
                    'gender' => 'male',
                    'mobile' => $socialite->payload['mobile'],
                    'status' => AuthConstant::CUSTOMER_ACTIVE,
                    'email_verified_at' => now(),
                    'password' => $request->input('password') ?? Str::random()
                ]
            );

            if ($customer->status == AuthConstant::CUSTOMER_PENDING) {
                $customer->update(['status' => AuthConstant::CUSTOMER_ACTIVE, 'email_verified_at' => now()]);
            }

            $customer->socialites()->save($socialite);
            $socialite->revoke();

            event(new LoginEvent($customer, 'customer', $request->all()));

            return response()->success($customer->format() + [
                    'token' => $customer->createToken(AuthConstant::TOKEN_NAME)->accessToken
                ]);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'SOCIAL_LOGIN_UPDATE_EXCEPTION'
            ]);
            return response()->failed(['message' => $exception->getMessage()]);
        }
    }

    public function info($profileId)
    {
        try {
            $social = SocialLogin::where('uuid', $profileId)->firstOrFail();
            $data = [];
            if ($social) {
                $data['is_first_time'] = false;
                $data['profile_id'] = $social->uuid;
                $data['info'] = $social->payload;
                if ($social->customer) {
                    $data['info']['email'] = $social->customer->email;
                }
            }
            return response()->success($data);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'SOCIAL_LOGIN_UPDATE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    public function delete($customer)
    {
        try {
            if(app()->environment('local')) {
                if ($customer->socialites) {
                    $customer->socialites()->delete();
                }
                $customer->delete();
                return response()->success();
            }
            return response()->failed(['message' => 'Cannot delete this customer for production environment.']);
        } catch (\Exception $exception) {
            LogHelper::exception($exception, [
                'keyword' => 'SOCIAL_LOGIN_UPDATE_EXCEPTION'
            ]);
            return response()->failed();
        }
    }

    private function parseMobile($user): ?string
    {
        if(config('socialite.fib.enabled') && array_key_exists('mobile', $user->attributes)) {
            return $user->attributes['mobile'] ? Str::start(substr($user->attributes['mobile'], -9), '+9647') : null;
        }
        return $user->attributes['mobile'] ?? null;
    }
}
