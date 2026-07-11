<?php

namespace App\Domains\Organization\Caching\Contracts;

interface SettingsCacheInterface
{
    public function get(string $key, array $context): mixed;
    public function put(string $key, mixed $value, array $context): void;
    public function forget(string $key, array $context): void;
    public function flush(array $context): void;
}
