<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json(['status' => 'OK', 'timestamp' => now()]);
    });

    Route::post('/auth/login', [\App\Domains\Identity\Authentication\Controllers\AuthenticationController::class, 'login']);
    Route::post('/auth/forgot-password', [\App\Domains\Identity\Passwords\Controllers\ForgotPasswordController::class, 'store']);
    Route::post('/invitations/accept', [\App\Domains\Identity\Invitations\Controllers\InvitationsController::class, 'accept']);

    Route::middleware(['auth:sanctum', 'tenant'])->group(function () {
        // Identity
        Route::post('/auth/change-password', [\App\Domains\Identity\Passwords\Controllers\ChangePasswordController::class, 'update']);
        Route::post('/auth/logout', [\App\Domains\Identity\Authentication\Controllers\AuthenticationController::class, 'logout']);

        Route::get('/profile', [\App\Domains\Identity\Identity\Controllers\ProfileController::class, 'show']);
        Route::patch('/profile', [\App\Domains\Identity\Identity\Controllers\ProfileController::class, 'update']);

        Route::apiResource('invitations', \App\Domains\Identity\Invitations\Controllers\InvitationsController::class)->only(['store']);
        Route::get('/sessions', [\App\Domains\Identity\Sessions\Controllers\SessionsController::class, 'index']);
        Route::delete('/sessions/other', [\App\Domains\Identity\Sessions\Controllers\SessionsController::class, 'destroyAll']);

        // Organization Core
        Route::apiResource('workspaces', \App\Domains\Organization\Controllers\WorkspaceController::class);

        // Global Settings Retrieval
        Route::get('/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'index']);

        Route::middleware('workspace')->group(function () {
            Route::apiResource('companies', \App\Domains\Organization\Controllers\CompanyController::class);
            Route::patch('/workspace/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateWorkspace']);

            Route::middleware('company')->group(function () {
                Route::apiResource('branches', \App\Domains\Organization\Controllers\BranchController::class);
                Route::apiResource('organization/policies', \App\Domains\Organization\Controllers\OrganizationPolicyController::class);
                Route::patch('/company/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateCompany']);

                Route::middleware('branch')->group(function () {
                    Route::apiResource('departments', \App\Domains\Organization\Controllers\DepartmentController::class);
                    Route::apiResource('teams', \App\Domains\Organization\Controllers\TeamController::class);
                    Route::patch('/branch/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateBranch']);
                });
            });
        });
    });
});
