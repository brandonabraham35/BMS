# Organization Cache Architecture

## Overview
BMS Enterprise uses a multi-level hierarchical caching system for Settings and Policies. This ensures sub-millisecond resolution of configuration even in complex organizational trees.

## Cache Hierarchy
Cache keys are namespaced by organizational level:
- `workspace:{id}`
- `company:{id}`
- `branch:{id}`

## Contracts
- `SettingsCacheInterface`: Manages retrieval and storage of hierarchical settings.
- `PolicyCacheInterface`: Manages retrieval and storage of organizational policies.
- `CacheKeyGeneratorInterface`: Generates deterministic, namespaced keys.
- `CacheInvalidatorInterface`: Handles event-driven invalidation.

## Invalidation Strategy
The system uses an event-driven approach. When an organizational entity is updated, archived, or a user is transferred, the `OrganizationCacheListener` triggers the `CacheInvalidator` to clear relevant namespaces.

## Multi-Region Support
The `DefaultCacheKeyGenerator` is designed to support regional prefixes, enabling seamless caching across different geographic deployments.
