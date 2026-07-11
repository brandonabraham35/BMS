# Organization API

Endpoints for managing the organizational structure of BMS Enterprise.

## Base URL
`/api/v1`

## Endpoints

### Workspaces
- `GET /workspaces`: List workspaces (Platform level).
- `POST /workspaces`: Create workspace.
- `GET /workspaces/{id}`: Show details.
- `PATCH /workspaces/{id}`: Update workspace.
- `DELETE /workspaces/{id}`: Archive workspace.

### Companies
- `GET /companies`: List companies in current workspace.
- `POST /companies`: Create company.
- `PATCH /companies/{id}`: Update company (Supports recursive `parent_company_id`).
- `POST /companies/{id}/restore`: Restore soft-deleted company.

### Branches
- `GET /branches`: List branches in current company.
- `POST /branches`: Create branch.

### User Transfers
- `GET /users/{id}/transfers`: Get transfer history for a user.
- `POST /users/{id}/transfers`: Perform a transfer (Workspace/Company/Branch).
