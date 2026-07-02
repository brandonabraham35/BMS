<?php

namespace App\Domains\Identity\Invitations\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Invitations\Services\InvitationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InvitationsController extends Controller
{
    use ApiResponse;

    public function __construct(protected InvitationService $invitationService) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'role_slug' => 'nullable|string',
        ]);

        $invitation = $this->invitationService->invite($request->only('email', 'role_slug'), $request->user());

        return $this->success($invitation, 'Invitation sent successfully', 201);
    }

    public function accept(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string',
            'name' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = $this->invitationService->accept($request->token, $request->only('name', 'password'));
            return $this->success($user, 'Invitation accepted successfully');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 422);
        }
    }
}
