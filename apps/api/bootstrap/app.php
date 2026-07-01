<?php

use App\Http\Middleware\TenantMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(\App\Http\Middleware\RequestIdMiddleware::class);
        $middleware->append(TenantMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Resource not found',
                    ], 404);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Unauthenticated',
                    ], 401);
                }

                if ($e instanceof AccessDeniedHttpException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Unauthorized access',
                    ], 403);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => 'Validation failed',
                        'errors' => $e->errors(),
                    ], 422);
                }

                return response()->json([
                    'status' => 'Error',
                    'message' => $e->getMessage() ?: 'Internal server error',
                    'trace' => config('app.debug') ? $e->getTrace() : null,
                ], 500);
            }
        });
    })->create();
