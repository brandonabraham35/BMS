# Development Guide

## Prerequisites
- Docker & Docker Compose
- Node.js 22+
- PHP 8.3+
- Composer

## Getting Started
1. Clone the repository.
2. Run `docker-compose up -d`.
3. Install backend dependencies: `cd apps/api && composer install`.
4. Install frontend dependencies: `cd apps/web && npm install`.
5. Run migrations: `cd apps/api && php artisan migrate`.

## API Development
- Place new controllers in `apps/api/app/Http/Controllers`.
- Place business logic in `apps/api/app/Services`.
- Use `php artisan make:module` (future) to scaffold new modules.

## Frontend Development
- Components reside in `apps/web/src/components`.
- Pages reside in `apps/web/src/pages`.
- Hooks reside in `apps/web/src/hooks`.
