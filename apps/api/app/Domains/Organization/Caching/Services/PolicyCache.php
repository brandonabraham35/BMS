<?php

namespace App\Domains\Organization\Caching\Services;

use App\Domains\Organization\Caching\Contracts\PolicyCacheInterface;
use App\Domains\Organization\Caching\Contracts\CacheKeyGeneratorInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class PolicyCache implements PolicyCacheInterface
{
    public function __construct(
        protected CacheRepository $cache,
        protected CacheKeyGeneratorInterface $keyGenerator
    ) {}

    public function get(string $type, array $context): mixed
    {
        $cacheKey = $this->keyGenerator->generate('policies', array_merge($context, ['policy_type' => $type]));
        return $this->cache->get($cacheKey);
    }

    public function put(string $type, mixed $value, array $context): void
    {
        $cacheKey = $this->keyGenerator->generate('policies', array_merge($context, ['policy_type' => $type]));
        $ttl = config('organization.cache.policies_ttl', 3600);

        $this->cache->put($cacheKey, $value, $ttl);
    }

    public function forget(string $type, array $context): void
    {
        $cacheKey = $this->keyGenerator->generate('policies', array_merge($context, ['policy_type' => $type]));
        $this->cache->forget($cacheKey);
    }

    public function flush(array $context): void
    {
        // Tag-based flushing handled by specific stores if supported
    }
}
