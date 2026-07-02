<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'OK', 'timestamp' => now()]);
    });

    Route::get('/ready', function () {
        // Check DB connection
        try {
            DB::connection()->getPdo();

            return response()->json(['status' => 'Ready']);
        } catch (Exception $e) {
            return response()->json(['status' => 'Not Ready', 'error' => $e->getMessage()], 503);
        }
    });

    Route::get('/status', function () {
        return response()->json([
            'status' => 'Operational',
            'version' => '1.0.0',
            'environment' => app()->environment(),
        ]);
    });

    Route::post('/auth/login', [\App\Domains\Identity\Authentication\Controllers\AuthenticationController::class, 'login']);
    Route::post('/auth/forgot-password', [\App\Domains\Identity\Passwords\Controllers\ForgotPasswordController::class, 'store']);

    Route::post('/invitations/accept', [\App\Domains\Identity\Invitations\Controllers\InvitationsController::class, 'accept']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/change-password', [\App\Domains\Identity\Passwords\Controllers\ChangePasswordController::class, 'update']);
        Route::post('/auth/verify-email', [\App\Domains\Identity\Verification\Controllers\VerificationController::class, 'verify']);
        Route::post('/auth/resend-verification', [\App\Domains\Identity\Verification\Controllers\VerificationController::class, 'resend']);
        Route::post('/auth/logout', [\App\Domains\Identity\Authentication\Controllers\AuthenticationController::class, 'logout']);

        Route::apiResource('invitations', \App\Domains\Identity\Invitations\Controllers\InvitationsController::class)->only(['store']);

        Route::get('/sessions', [\App\Domains\Identity\Sessions\Controllers\SessionsController::class, 'index']);
        Route::delete('/sessions/other', [\App\Domains\Identity\Sessions\Controllers\SessionsController::class, 'destroyAll']);
        Route::delete('/sessions/{id}', [\App\Domains\Identity\Sessions\Controllers\SessionsController::class, 'destroy']);

        Route::get('/devices', [\App\Domains\Identity\Devices\Controllers\DevicesController::class, 'index']);
        Route::patch('/devices/{deviceId}', [\App\Domains\Identity\Devices\Controllers\DevicesController::class, 'update']);
        Route::delete('/devices/{deviceId}', [\App\Domains\Identity\Devices\Controllers\DevicesController::class, 'destroy']);

        Route::get('/profile', [\App\Domains\Identity\Identity\Controllers\ProfileController::class, 'show']);
        Route::patch('/profile', [\App\Domains\Identity\Identity\Controllers\ProfileController::class, 'update']);
        Route::patch('/profile/preferences', [\App\Domains\Identity\Identity\Controllers\ProfileController::class, 'updatePreferences']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
    });
});
