<?php

namespace App\Domains\Identity\Passwords\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Passwords\Services\PasswordService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChangePasswordController extends Controller
{
    use ApiResponse;

    public function __construct(protected PasswordService $passwordService) {}

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        try {
            $this->passwordService->changePassword(
                $request->user(),
                $request->current_password,
                $request->new_password
            );
            return $this->success(null, 'Password updated successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
