# Policies API

Endpoints for managing and discovering hierarchical business policies.

## Discovery
- `GET /organization/policies/schema`: List all registered policy definitions and metadata.
- `GET /organization/policies/categories`: List all policy categories.
- `GET /organization/policies/types`: List all valid policy types.

## Management
- `GET /organization/policies`: List policies in the current context.
- `POST /organization/policies`: Create a local policy override.
- `PATCH /organization/policies/{id}`: Update policy rules.
- `DELETE /organization/policies/{id}`: Remove a local override.

## Format
Requests must include a `rules` array matching the schema defined in the discovery endpoints.
```json
{
  "name": "Local Business Hours",
  "type": "business_hours",
  "rules": {
    "monday": {"open": "09:00", "close": "18:00"}
  }
}
```
