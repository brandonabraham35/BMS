<?php

namespace App\Domains\Identity\Identity\Services;

use App\Models\User;
use App\Services\AuditLogger;

class ProfileService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function updateProfile(User $user, array $data): User
    {
        $oldValues = $user->only(array_keys($data));
        $user->update($data);

        $this->auditLogger->log('profile_updated', $user, $oldValues, $data);

        return $user;
    }

    public function updatePreferences(User $user, array $preferences): User
    {
        $oldPrefs = $user->preferences;
        $user->update(['preferences' => $preferences]);

        $this->auditLogger->log('preferences_updated', $user, $oldPrefs, $preferences);

        return $user;
    }
}
