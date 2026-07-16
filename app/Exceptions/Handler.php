<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    public function render($request, Throwable $e)
    {
        if($e instanceof ValidationException){
            return ApiResponse::error(
                'Validation Error',
                422,
                $e->errors(),
            );
        }
        if($e instanceof AuthorizationException){
            return ApiResponse::error(
                'Authorization Error',
                403,
                $e->getMessage(),
            );
        }
        if($e instanceof AuthenticationException){
            return ApiResponse::error(
                'Authentication Error',
                401,
                $e->getMessage(),
            );
        }
        if($e instanceof ModelNotFoundException){
            return ApiResponse::error(
                $e->getMessage(),
                404,
                $e->getMessage(),
            );
        }
        if($e instanceof NotFoundHttpException){
            return ApiResponse::error(
                'Route Not Found',
                404,
                $e->getMessage(),
            );
        }
        return ApiResponse::error(
            'Internal Server Error',
            500,
            $e->getMessage(),
        );
    }
}
