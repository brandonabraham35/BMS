# Settings Engine Architecture

BMS Enterprise uses a hierarchical settings engine that allows for fine-grained configuration at any level of the organization.

## Resolution Logic
The `SettingsResolver` searches for a key in the following order (specific to general):
1. User
2. Team (Future)
3. Department (Future)
4. Branch
5. Company (Recursive ancestry: Child -> Parent -> Grandparent)
6. Workspace
7. Platform

## Storage
Settings are stored in the `settings` table, indexed by polymorphic keys (`workspace_id`, `company_id`, etc.) and the setting `key`.

## Extension
New settings keys do not require database changes. Use the `SettingsService` to set values at any level.
