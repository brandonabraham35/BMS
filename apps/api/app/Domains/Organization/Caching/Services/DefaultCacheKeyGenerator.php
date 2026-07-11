<?php

namespace App\Domains\Organization\Caching\Services;

use App\Domains\Organization\Caching\Contracts\CacheKeyGeneratorInterface;

class DefaultCacheKeyGenerator implements CacheKeyGeneratorInterface
{
    public function generate(string $type, array $params): string
    {
        $parts = [$type];

        if (isset($params['workspace_id'])) {
            $parts[] = "workspace:{$params['workspace_id']}";
        }
        if (isset($params['company_id'])) {
            $parts[] = "company:{$params['company_id']}";
        }
        if (isset($params['branch_id'])) {
            $parts[] = "branch:{$params['branch_id']}";
        }
        if (isset($params['key'])) {
            $parts[] = $params['key'];
        }
        if (isset($params['policy_type'])) {
            $parts[] = $params['policy_type'];
        }

        return implode(':', $parts);
    }

    public function getTags(array $context): array
    {
        $tags = ['organization'];

        if (isset($context['workspace_id'])) {
            $tags[] = "workspace:{$context['workspace_id']}";
        }
        if (isset($context['company_id'])) {
            $tags[] = "company:{$context['company_id']}";
        }
        if (isset($context['branch_id'])) {
            $tags[] = "branch:{$context['branch_id']}";
        }

        return $tags;
    }
}
