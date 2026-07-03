<?php

namespace App\Domains\AccessControl\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPermissionController extends Controller
{
    use ApiResponse;

    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $user->load(['roles.permissions', 'directPermissions']);

        return $this->successResponse([
            'roles' => $user->roles,
            'direct_permissions' => $user->directPermissions,
            'resolved_permissions' => $user->hasPermission('*') // Example placeholder for internal resolve call
        ]);
    }

    public function syncRoles(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);
        $data = $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($data['role_ids']);

        return $this->successResponse(null, 'Roles synchronized successfully');
    }

    public function syncPermissions(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);
        $data = $request->validate([
            'permissions' => 'required|array',
            'permissions.*.id' => 'required|exists:permissions,id',
            'permissions.*.expires_at' => 'nullable|date',
        ]);

        $syncData = [];
        foreach ($data['permissions'] as $p) {
            $syncData[$p['id']] = ['expires_at' => $p['expires_at'] ?? null];
        }

        $user->directPermissions()->sync($syncData);

        return $this->successResponse(null, 'Direct permissions synchronized successfully');
    }
}
