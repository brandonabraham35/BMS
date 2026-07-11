<?php

namespace App\Domains\Organization\Caching\Services;

use App\Domains\Organization\Caching\Contracts\SettingsCacheInterface;
use App\Domains\Organization\Caching\Contracts\CacheKeyGeneratorInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class SettingsCache implements SettingsCacheInterface
{
    public function __construct(
        protected CacheRepository $cache,
        protected CacheKeyGeneratorInterface $keyGenerator
    ) {}

    public function get(string $key, array $context): mixed
    {
        $cacheKey = $this->keyGenerator->generate('settings', array_merge($context, ['key' => $key]));
        return $this->cache->get($cacheKey);
    }

    public function put(string $key, mixed $value, array $context): void
    {
        $cacheKey = $this->keyGenerator->generate('settings', array_merge($context, ['key' => $key]));
        $ttl = config('organization.cache.settings_ttl', 3600);

        $this->cache->put($cacheKey, $value, $ttl);
    }

    public function forget(string $key, array $context): void
    {
        $cacheKey = $this->keyGenerator->generate('settings', array_merge($context, ['key' => $key]));
        $this->cache->forget($cacheKey);
    }

    public function flush(array $context): void
    {
        // For non-tag-supporting drivers, we rely on individual forget or full clear.
        // If driver supports tags, we would use $this->cache->tags($this->keyGenerator->getTags($context))->flush();
    }
}
