<?php

namespace App\Domains\Identity\Passwords\Services;

use App\Models\User;
use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\AuditLogger;

class PasswordService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [__('The provided password does not match your current password.')],
            ]);
        }

        $this->validatePasswordHistory($user, $newPassword);

        $user->update([
            'password' => Hash::make($newPassword),
            'password_changed_at' => now(),
        ]);

        $this->recordPasswordHistory($user);

        $this->auditLogger->log('password_change', $user);
    }

    public function validatePasswordHistory(User $user, string $password): void
    {
        $history = PasswordHistory::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        foreach ($history as $pastPassword) {
            if (Hash::check($password, $pastPassword->password)) {
                throw ValidationException::withMessages([
                    'password' => [__('You cannot reuse any of your last 5 passwords.')],
                ]);
            }
        }
    }

    public function recordPasswordHistory(User $user): void
    {
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);
    }
}
