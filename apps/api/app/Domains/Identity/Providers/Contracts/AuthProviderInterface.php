<?php

namespace App\Domains\Identity\Providers\Contracts;

interface AuthProviderInterface
{
    public function authenticate(array $credentials): bool;
    public function getUserData(string $identifier): array;
}
