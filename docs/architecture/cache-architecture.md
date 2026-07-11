# Cache Architecture

The BMS caching layer provides sub-millisecond resolution for hierarchical data like settings and policies.

## Key Generation
The `CacheKeyGeneratorInterface` defines the contract for deterministic keys. The default implementation uses namespaced keys:
- `settings:workspace:{uuid}:{key}`
- `policies:company:{uuid}:{type}`

## Invalidation
Invalidation is event-driven to ensure high consistency:
- `SettingsChanged` event -> invalidates settings for the specific context.
- `PolicyChanged` event -> invalidates policies for the specific context.
- `WorkspaceUpdated`/`CompanyUpdated`/`BranchUpdated` -> invalidates all related cached items for that entity.

## Implementation
The system uses Laravel's `Cache` repository, making it compatible with Redis, Memcached, or Database drivers.
