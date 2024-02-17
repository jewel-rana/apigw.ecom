<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $exception) {
            //
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            $previous = $e->getPrevious();

            if ($previous instanceof AuthorizationException) {
                $response = [
                    'status' => false,
                    'message' => __('You have no permission to access this.')
                ];
                throw new HttpResponseException(response()->json($response, 403));
            }
            return null;
        });

        $this->renderable(function (UnauthorizedException $e, $request) {
            $previous = $e->getPrevious();

            if ($previous instanceof UnauthorizedException) {
                $response = [
                    'status' => false,
                    'message' => __('You are unauthorized!')
                ];
                throw new HttpResponseException(response()->json($response, 401));
            }
            return null;
        });
    }
}
