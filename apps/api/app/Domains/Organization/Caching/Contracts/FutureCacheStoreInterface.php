<?php

namespace App\Domains\Organization\Caching\Contracts;

interface FutureCacheStoreInterface
{
    /**
     * Extension point for custom store logic (e.g. tagging, multi-region)
     */
    public function store(string $key, mixed $value, ?int $ttl = null, array $tags = []): bool;
    public function retrieve(string $key): mixed;
    public function invalidate(array $tags): bool;
}
