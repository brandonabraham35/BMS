# Changelog
All notable changes to this project will be documented in this file.

## [0.3.0] - 2026-07-04
### Added
- **Organization Domain Hardening**: Full enterprise-ready organizational structure.
- **Hierarchical Settings Engine**: Recursive inheritance and overrides (Platform -> Workspace -> Company -> Branch).
- **Generic Policy Engine**: Registry-based policy management with pluggable validation.
- **User Transfer Engine**: Transactional user transfers with state snapshots and audit history.
- **Enterprise Caching**: Context-aware caching layer for settings and policies.
- **Organization UI**: Production-ready management interfaces for Companies, Branches, Departments, and Teams.
- **Soft Delete Management**: Standardized restore and force-delete operations across all entities.

### Fixed
- **Authorization Blocker**: Corrected UUID comparison in policies to allow authorized requests to proceed to validation.
- **Circular References**: Added detection and prevention for circular company hierarchies.
- **N+1 Queries**: Implemented eager loading in organization list endpoints.

### Changed
- Refactored Policy Engine to be generic and module-agnostic.
- Unified tenant resolution logic via `TenantContext`.

## [0.2.0] - 2026-07-01
### Added
- Core IAM features (Authentication, Sessions, Invitations).
- Initial Workspace and Company models.
- Role-Based Access Control (RBAC) foundation.

## [0.1.0] - 2026-06-15
### Added
- Initial project structure and Laravel 12 setup.
- SMS Uganda standalone module.
