<?php

namespace App\Domains\Organization\Settings\Contracts;

interface SettingsCacheInterface
{
    public function get(string $key, array $context): mixed;
    public function put(string $key, array $context, mixed $value): void;
    public function forget(string $key, array $context): void;
    public function flush(): void;
}
