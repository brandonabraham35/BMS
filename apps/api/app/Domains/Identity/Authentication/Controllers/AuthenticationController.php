<?php

namespace App\Domains\Identity\Authentication\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Authentication\Services\AuthenticationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthenticationController extends Controller
{
    use ApiResponse;

    public function __construct(protected AuthenticationService $authService) {}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $result = $this->authService->login(
            $request->only('email', 'password'),
            $request->device_name
        );

        return $this->success($result, 'Login successful');
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return $this->success(null, 'Logged out successfully');
    }
}
