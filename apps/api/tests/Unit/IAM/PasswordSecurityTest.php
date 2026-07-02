<?php

namespace Tests\Unit\IAM;

use Tests\TestCase;
use App\Models\User;
use App\Models\PasswordHistory;
use App\Domains\Identity\Passwords\Services\PasswordService;
use App\Services\AuditLogger;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_history_prevents_reuse(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        $auditLogger = $this->createMock(AuditLogger::class);
        $service = new PasswordService($auditLogger);

        // Record current as history
        $service->recordPasswordHistory($user);

        $this->expectException(ValidationException::class);
        $service->changePassword($user, 'old-password', 'old-password');
    }
}
