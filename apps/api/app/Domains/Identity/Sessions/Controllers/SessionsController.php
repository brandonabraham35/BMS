<?php

namespace App\Domains\Identity\Sessions\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Sessions\Services\SessionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SessionsController extends Controller
{
    use ApiResponse;

    public function __construct(protected SessionService $sessionService) {}

    public function index(Request $request): JsonResponse
    {
        $sessions = $this->sessionService->getActiveSessions($request->user());
        return $this->successResponse($sessions, 'Active sessions retrieved');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $this->sessionService->terminateSession($request->user(), $id);
        return $this->successResponse(null, 'Session terminated');
    }

    public function destroyAll(Request $request): JsonResponse
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        // In tests using actingAs, currentAccessToken might be null or handled differently
        $tokenId = $token ? (string) $token->id : '';

        $this->sessionService->terminateAllOtherSessions($user, $tokenId);
        return $this->successResponse(null, 'All other sessions terminated');
    }
}
