# Organization Policies Subsystem

## Overview
The Organization Policies subsystem provides a framework for defining and resolving business rules across the organizational hierarchy. Unlike static permissions, policies represent dynamic rules that can be inherited and overridden.

## Hierarchy Resolution
Policies are resolved in the following order:
1. **Branch**: Specific rules for a location.
2. **Company**: Legal entity defaults.
3. **Workspace**: Organization group standards.
4. **Platform**: Global system defaults.

The `PolicyResolver` returns the first active policy found in this chain for a given type.

## Supported Policy Types
- `business_hours`: Opening and closing times.
- `working_days`: Active days for the business.
- `approval_rules`: Thresholds and requirements for approvals.
- `security`: MFA requirements, session timeouts, etc.
- `document_numbering`: Formats for invoices, orders, etc.
- `regional`: Tax rules and localization defaults.

## Implementation Details
- **PolicyValidator**: Ensures rules match the expected schema for the policy type.
- **Audit Logging**: Every change to a policy is recorded in the audit logs.
- **Tenant Isolation**: Policies are strictly scoped to the workspace and company they belong to.

## API Reference
- `GET /api/v1/organization/policies`: List policies for the current context.
- `POST /api/v1/organization/policies`: Create a new policy.
- `PUT /api/v1/organization/policies/{id}`: Update an existing policy.
- `DELETE /api/v1/organization/policies/{id}`: Remove a policy.
