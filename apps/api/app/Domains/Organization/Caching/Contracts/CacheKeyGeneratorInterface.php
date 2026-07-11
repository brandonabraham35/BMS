<?php

namespace App\Domains\Organization\Caching\Contracts;

interface CacheKeyGeneratorInterface
{
    public function generate(string $type, array $params): string;
    public function getTags(array $context): array;
}
