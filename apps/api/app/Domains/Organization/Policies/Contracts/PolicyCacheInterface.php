<?php

namespace App\Domains\Organization\Policies\Contracts;

interface PolicyCacheInterface
{
    public function get(string $type, string $companyId): mixed;
    public function put(string $type, string $companyId, mixed $value): void;
    public function forget(string $type, string $companyId): void;
    public function flush(): void;
}
