# Tenant Resolution Architecture

Isolation in BMS Enterprise is achieved through a multi-layered tenant resolution process.

## TenantContext
The `TenantContext` is a request-scoped singleton that holds the current resolved entities:
- `User`
- `Workspace`
- `Company`
- `Branch`

## Resolution Flow
1. **Authentication**: Laravel Sanctum identifies the user.
2. **TenantMiddleware**: Populates the `TenantContext` based on the authenticated user's profile.
3. **WorkspaceMiddleware**: Ensures a valid `workspace_id` is present for organization-specific routes.
4. **Scoped Routing**: Routes are wrapped in middleware (e.g., `company`, `branch`) to ensure context remains consistent during the request lifecycle.

## Data Isolation
Global Scopes or Repository filters (e.g., `where('workspace_id', ...)` ) are used across all services to ensure data leakage between tenants is impossible.
