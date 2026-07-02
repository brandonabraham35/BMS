<?php

namespace App\Domains\Identity\Sessions\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use App\Services\AuditLogger;

class SessionService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function getActiveSessions(User $user): Collection
    {
        return $user->tokens()->latest()->get();
    }

    public function terminateSession(User $user, string $tokenId): void
    {
        $token = $user->tokens()->findOrFail($tokenId);
        $token->delete();

        $this->auditLogger->log('session_revoked', $user, null, ['token_id' => $tokenId]);
    }

    public function terminateAllOtherSessions(User $user, string $currentTokenId): void
    {
        $user->tokens()->where('id', '!=', $currentTokenId)->delete();

        $this->auditLogger->log('all_other_sessions_revoked', $user);
    }
}
