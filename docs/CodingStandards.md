# Coding Standards

## PHP / Laravel
- Follow PSR-12 coding standards.
- Use strict typing: `declare(strict_types=1);`.
- All models must use the `HasUuid` trait.
- Controllers should be thin; move logic to Services.
- Use Laravel Pint for automated formatting.

## TypeScript / React
- Use functional components and hooks.
- Prefix interfaces with `I` (optional) or use descriptive names.
- Use ESLint and Prettier for automated formatting.
- Prefer `const` over `let`.
- Ensure proper type definitions for all props and state.
