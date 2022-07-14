<?php

namespace App\Exceptions;

use ErrorException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register(): void
    {
        $this->renderable(function (Exception $exception) {
            $responseCode = match (true) {
                $exception instanceof NotFoundHttpException         => Response::HTTP_NOT_FOUND,
                $exception instanceof AuthenticationException       => Response::HTTP_UNAUTHORIZED,
                $exception instanceof UnauthorizedException         => Response::HTTP_FORBIDDEN,
                $exception instanceof MethodNotAllowedHttpException => Response::HTTP_METHOD_NOT_ALLOWED,
                $exception instanceof ValidationException,
                    $exception instanceof ErrorException,
                    $exception instanceof HttpException             => Response::HTTP_UNPROCESSABLE_ENTITY,
                default                                             => Response::HTTP_BAD_REQUEST
            };

            $message = match (true) {
                $exception instanceof ValidationException       => collect($exception->errors())->first()[0],
                $exception instanceof MethodNotAllowedHttpException,
                    $exception instanceof NotFoundHttpException => trans("Route not found!"),
                default                                         => trans($exception->getMessage())
            };

            return errorResponse([
                'message' => $message,
            ], $responseCode);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
