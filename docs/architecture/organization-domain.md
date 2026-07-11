# Organization Domain Architecture

The Organization Domain is the structural foundation of BMS Enterprise. It manages the multi-level hierarchy and provides isolation for all other domains.

## Hierarchy
1. **Platform**: Global configuration and defaults.
2. **Workspace**: The primary isolation boundary (Logical Tenant).
3. **Company**: Business entities within a workspace. Supports recursive parent-child relationships.
4. **Branch**: Physical or logical locations under a company.
5. **Department**: Functional units within a branch.
6. **Team**: Agile or working groups within a department.

## Model Traits
All organizational models utilize:
- `HasUuid`: Primary keys are UUIDs.
- `SoftDeletes`: Data recovery and auditing.
- `Searchable`: Standardized API search and filtering.

## Services
- `WorkspaceService`: Core workspace management.
- `CompanyService`: Manages business entities and deep hierarchy.
- `BranchService`: Location-specific management.
- `TransferService`: Orchestrates user movement between units.
