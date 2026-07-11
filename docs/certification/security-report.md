# Security Certification Report: Organization Domain

## 1. Tenant Isolation
- **Workspace Isolation**: Verified through `SecurityCertificationTest.php` and `WorkspaceIsolationTest.php`. Users cannot access resources outside their designated workspace.
- **Company/Branch Boundary**: Resolved from authenticated user context and enforced via Controller-level logic and Laravel Policies.

## 2. Authorization
- **Model Policies**: All organizational models (`Workspace`, `Company`, `Branch`, `Department`, `Team`, `User`, `OrganizationPolicy`) have corresponding Laravel Policies.
- **Strict Comparison**: Casted UUIDs to strings in all policy checks to prevent object-comparison failures.
- **Soft Delete Protection**: `restore` and `forceDelete` operations are strictly guarded by `authorize('update/delete', ...)` calls.

## 3. Data Integrity
- **Circular References**: Prevented in company hierarchies using `CompanyHierarchyService`. Verified with `HierarchyEdgeCaseTest.php`.
- **Transfer Auditing**: All user movements generate an `AuditLog` and record the state snapshot before and after the transfer.

## 4. Policy/Settings Leakage
- **Resolver Isolation**: Logic ensures that settings and policies never fall back across workspace boundaries.

**Status: CERTIFIED**
