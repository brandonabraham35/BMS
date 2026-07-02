<?php

namespace App\Domains\Identity\Passwords\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Passwords\Services\PasswordService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    use ApiResponse;

    public function store(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        // Future: Send Reset Link
        return $this->success(null, 'Reset link sent if account exists');
    }
}
