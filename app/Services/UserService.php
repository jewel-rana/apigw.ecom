<?php

namespace App\Services;

use App\Helpers\LogHelper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function all()
    {
        return Cache::remember('administrators', 36000, function () {
            return $this->userRepository->all();
        });
    }

    public function create($data)
    {
        try {
            $this->userRepository->create($data);
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
            $this->userRepository->update($data, $id);
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
}
