# API v1 Freeze: Organization Domain

## Stability Guarantee
The following namespaces are frozen for v1. No breaking changes (removing fields, changing types, changing paths) will occur. All enhancements must be backward-compatible.

## Frozen Resource Paths
- `/api/v1/workspaces/*`
- `/api/v1/companies/*`
- `/api/v1/branches/*`
- `/api/v1/departments/*`
- `/api/v1/teams/*`
- `/api/v1/organization/policies/*`
- `/api/v1/settings/*`
- `/api/v1/users/{id}/transfers/*`

## Standard Response Format
All v1 endpoints must return:
```json
{
  "status": "Success|Error",
  "message": "Optional message",
  "data": { ... }
}
```

## Future Changes
Any non-compatible changes will be implemented in `/api/v2/`.
