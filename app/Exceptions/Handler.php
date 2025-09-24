<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
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
        if ($request->expectsJson()) {
            return $this->renderJson($e);
        }

        return parent::render($request, $e);
    }

    protected function renderJson(Throwable $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'Данные не прошли проверку.',
                'errors' => $e->errors(),
            ], 422);
        }

        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Необходима аутентификация.',
            ], 401);
        }

        if ($e instanceof AuthorizationException) {
            return response()->json([
                'message' => 'Недостаточно прав для выполнения действия.',
            ], 403);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Запись не найдена.',
            ], 404);
        }

        $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        Log::error('Unhandled exception occurred', [
            'message' => $e->getMessage(),
            'exception' => $e,
        ]);

        return response()->json([
            'message' => $status === 500 ? 'Внутренняя ошибка сервера.' : $e->getMessage(),
        ], $status);
    }
}
