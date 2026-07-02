<?php

namespace App\Domains\Identity\Authentication\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Services\AuditLogger;

class AuthenticationService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function login(array $credentials, string $deviceName): array
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => [__('Account is :status.', ['status' => $user->status])],
            ]);
        }

        $token = $user->createToken($deviceName)->plainTextToken;

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        $this->auditLogger->log('login', $user, null, ['device' => $deviceName]);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        $this->auditLogger->log('logout', $user);
    }
}
