<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        $this->stopIgnoring(HttpException::class);
        // Handle Unauthenticated Exception
        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        });

        // Handle Model Not Found Exception
        $this->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Model not found.',
            ], 404);
        });

        // Handle Route Not Found Exception
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Resource Not found!',
            ], 404);
        });

        // Handle Route Not Found Exception
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'status' => false,
                'message' => "The {$request->method()} method is not allowed for this route.",
            ], 404);
        });

        // Fallback for all other exceptions
        $this->renderable(function (Throwable $e, $request) {
            return response()->json([
                'status' => false,
                'message' => 'Server error.',
            ], $e->getCode() ?: 500);
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $response = [
                    'status' => false,
                    'message' => __('Record not found!')
                ];
                throw new HttpResponseException(response()->json($response, 404));
            }
        });

        $this->reportable(function (Throwable $exception) {
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
