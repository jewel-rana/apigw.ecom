<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('success', function ($data = null, $message = 'Success!') use ($factory) {
            $format = [
                'status' => true,
                'message' => $message,
                'data' => $data,
            ];

            return $factory->make($format);
        });

        $factory->macro('error', function ($params = []) use ($factory){
            $format = [
                'status' => false,
                'message' => $params['message'] ?? 'Failed!',
                'errors' => $params['errors'] ?? [],
            ];

            return $factory->make($format, $params['code'] ?? 500);
        });

        $factory->macro('failed', function ($params = []) use ($factory){
            $format = [
                'status' => false,
                'message' => $params['message'] ?? 'Failed!',
                'errors' => $params['errors'] ?? [],
            ];

            return $factory->make($format, $params['code'] ?? 500);
        });
    }
}
