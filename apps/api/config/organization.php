<?php

return [
    'cache' => [
        'enabled' => env('ORGANIZATION_CACHE_ENABLED', true),
        'settings_ttl' => env('ORGANIZATION_SETTINGS_CACHE_TTL', 3600),
        'policies_ttl' => env('ORGANIZATION_POLICIES_CACHE_TTL', 3600),
    ],
    'hierarchy' => [
        'levels' => [
            'platform',
            'workspace',
            'company',
            'branch',
            'department',
            'team',
            'user',
        ],
    ],
];
