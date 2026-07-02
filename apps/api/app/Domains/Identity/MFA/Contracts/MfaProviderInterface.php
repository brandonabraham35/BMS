<?php

namespace App\Domains\Identity\MFA\Contracts;

use App\Models\User;

interface MfaProviderInterface
{
    public function generateChallenge(User $user): mixed;
    public function verifyChallenge(User $user, mixed $response): bool;
    public function isEnabled(User $user): bool;
}
