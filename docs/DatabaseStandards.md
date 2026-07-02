# Database Standards

## Conventions
- Table names are plural and snake_case.
- Use UUID for all primary keys (`id`).
- Use `bigIncremental` only if absolutely necessary for legacy reasons.
- Foreign keys must be indexed and use the naming convention `entity_id`.

## Required Columns
Every business table must support:
- `id` (UUID)
- `created_at`
- `updated_at`
- `deleted_at` (Soft Deletes)
- `company_id` (where applicable)
- `branch_id` (where applicable)

## Migrations
- Always include a `down()` method for rollback.
- Use anonymous migration classes.
