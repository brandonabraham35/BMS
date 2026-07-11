<?php

namespace App\Domains\Organization\Caching\Contracts;

interface PolicyCacheInterface
{
    public function get(string $type, array $context): mixed;
    public function put(string $type, mixed $value, array $context): void;
    public function forget(string $type, array $context): void;
    public function flush(array $context): void;
}
