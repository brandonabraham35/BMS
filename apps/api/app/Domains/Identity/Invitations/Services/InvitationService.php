<?php

namespace App\Domains\Identity\Invitations\Services;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\AuditLogger;

class InvitationService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function invite(array $data, User $invitedBy): Invitation
    {
        return DB::transaction(function () use ($data, $invitedBy) {
            $invitation = Invitation::create([
                'company_id' => $invitedBy->company_id,
                'invited_by' => $invitedBy->id,
                'email' => $data['email'],
                'role_slug' => $data['role_slug'] ?? null,
                'token' => Str::random(40),
                'expires_at' => now()->addDays(7),
            ]);

            $this->auditLogger->log('invitation_sent', $invitation, null, ['email' => $data['email']]);

            // Future: Send Invitation Email

            return $invitation;
        });
    }

    public function accept(string $token, array $userData): User
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            throw new \Exception('Invitation is invalid or expired.');
        }

        return DB::transaction(function () use ($invitation, $userData) {
            $user = User::create([
                'company_id' => $invitation->company_id,
                'email' => $invitation->email,
                'name' => $userData['name'],
                'password' => bcrypt($userData['password']),
                'status' => 'active',
            ]);

            $invitation->update(['accepted_at' => now()]);

            $this->auditLogger->log('invitation_accepted', $invitation, null, ['user_id' => $user->id]);

            return $user;
        });
    }
}
