# Module Development Guide

## Structure
Each module should ideally contain:
- Controllers
- Services
- Repositories
- Models
- Migrations
- Tests

## Registration
1. Create the module logic.
2. Register the module in the `ModuleRegistry` during application boot.
3. Define permissions using dot notation (e.g., `inventory.view`).

## Scoping
Always ensure that data is scoped by `company_id` and `branch_id` where applicable using the `CompanyContext` service.
