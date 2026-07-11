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

        // Access Control Core
        Route::apiResource('roles', \App\Domains\AccessControl\Controllers\RoleController::class);
        Route::post('roles/{role}/clone', [\App\Domains\AccessControl\Controllers\RoleController::class, 'clone']);

        Route::get('permissions', [\App\Domains\AccessControl\Controllers\PermissionController::class, 'index']);
        Route::get('permission-categories', [\App\Domains\AccessControl\Controllers\PermissionController::class, 'categories']);
        Route::get('permission-groups', [\App\Domains\AccessControl\Controllers\PermissionController::class, 'groups']);

        Route::get('users/{user}/permissions', [\App\Domains\AccessControl\Controllers\UserPermissionController::class, 'show']);
        Route::post('users/{user}/roles/sync', [\App\Domains\AccessControl\Controllers\UserPermissionController::class, 'syncRoles']);
        Route::post('users/{user}/permissions/sync', [\App\Domains\AccessControl\Controllers\UserPermissionController::class, 'syncPermissions']);

        Route::post('authorization/simulate', [\App\Domains\AccessControl\Controllers\AuthorizationSimulatorController::class, 'simulate']);

        // Organization Core
        Route::post('workspaces/{workspace}/restore', [\App\Domains\Organization\Controllers\WorkspaceController::class, 'restore']);
        Route::delete('workspaces/{workspace}/force', [\App\Domains\Organization\Controllers\WorkspaceController::class, 'forceDelete']);
        Route::apiResource('workspaces', \App\Domains\Organization\Controllers\WorkspaceController::class);

        // Global Settings Retrieval
        Route::get('/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'index']);

        Route::middleware('workspace')->group(function () {
            Route::post('companies/{company}/restore', [\App\Domains\Organization\Controllers\CompanyController::class, 'restore']);
            Route::delete('companies/{company}/force', [\App\Domains\Organization\Controllers\CompanyController::class, 'forceDelete']);
            Route::apiResource('companies', \App\Domains\Organization\Controllers\CompanyController::class);
            Route::patch('/workspace/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateWorkspace']);

            Route::middleware('company')->group(function () {
                Route::post('branches/{branch}/restore', [\App\Domains\Organization\Controllers\BranchController::class, 'restore']);
                Route::delete('branches/{branch}/force', [\App\Domains\Organization\Controllers\BranchController::class, 'forceDelete']);
                Route::apiResource('branches', \App\Domains\Organization\Controllers\BranchController::class);
                Route::apiResource('organization/policies', \App\Domains\Organization\Controllers\OrganizationPolicyController::class);
                Route::patch('/company/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateCompany']);

                Route::middleware('branch')->group(function () {
                    Route::post('departments/{department}/restore', [\App\Domains\Organization\Controllers\DepartmentController::class, 'restore']);
                    Route::delete('departments/{department}/force', [\App\Domains\Organization\Controllers\DepartmentController::class, 'forceDelete']);
                    Route::apiResource('departments', \App\Domains\Organization\Controllers\DepartmentController::class);

                    Route::post('teams/{team}/restore', [\App\Domains\Organization\Controllers\TeamController::class, 'restore']);
                    Route::delete('teams/{team}/force', [\App\Domains\Organization\Controllers\TeamController::class, 'forceDelete']);
                    Route::apiResource('teams', \App\Domains\Organization\Controllers\TeamController::class);

                    Route::patch('/branch/settings', [\App\Domains\Organization\Controllers\SettingsController::class, 'updateBranch']);
                });
            });
        });

        // User Transfers
        Route::get('users/{user}/transfers', [\App\Domains\Organization\Controllers\TransferController::class, 'index']);
        Route::post('users/{user}/transfers', [\App\Domains\Organization\Controllers\TransferController::class, 'store']);
    });
});
