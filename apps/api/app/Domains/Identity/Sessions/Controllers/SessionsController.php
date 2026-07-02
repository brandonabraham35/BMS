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
        return $this->success($sessions, 'Active sessions retrieved');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $this->sessionService->terminateSession($request->user(), $id);
        return $this->success(null, 'Session terminated');
    }

    public function destroyAll(Request $request): JsonResponse
    {
        $this->sessionService->terminateAllOtherSessions($request->user(), $request->user()->currentAccessToken()->id);
        return $this->success(null, 'All other sessions terminated');
    }
}
