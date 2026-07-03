<?php

namespace App\Domains\AccessControl\Controllers;

use App\Domains\AccessControl\Services\AuthorizationEngine;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorizationSimulatorController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthorizationEngine $engine) {}

    public function simulate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'permission' => 'required|string',
        ]);

        $user = User::findOrFail($data['user_id']);
        $this->authorize('view', $user);

        $allowed = $this->engine->check($user, $data['permission']);

        return $this->successResponse([
            'allowed' => $allowed,
            'explanation' => [
                'roles' => $user->roles->pluck('name'),
                'matched_permission' => $data['permission'],
            ]
        ]);
    }
}
