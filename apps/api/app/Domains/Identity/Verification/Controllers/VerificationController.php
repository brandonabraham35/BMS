<?php

namespace App\Domains\Identity\Verification\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Verification\Services\EmailVerificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerificationController extends Controller
{
    use ApiResponse;

    public function __construct(protected EmailVerificationService $verificationService) {}

    public function verify(Request $request): JsonResponse
    {
        $this->verificationService->verify($request->user());
        return $this->success(null, 'Email verified successfully');
    }

    public function resend(Request $request): JsonResponse
    {
        $this->verificationService->sendVerification($request->user());
        return $this->success(null, 'Verification link resent');
    }
}
