<?php

namespace App\Domains\Identity\Verification\Services;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use App\Services\AuditLogger;

class EmailVerificationService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function sendVerification(User $user): void
    {
        // Future: Send actual email with signed URL
        // $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), ['id' => $user->id]);

        $this->auditLogger->log('verification_sent', $user);
    }

    public function verify(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();
        $this->auditLogger->log('email_verified', $user);
    }
}
