# BMS Enterprise — Enterprise-Grade Modular Business Platform

## Overview
BMS Enterprise is a modular, multi-tenant Business Management Platform designed to power diverse industries including bakeries, restaurants, hotels, and manufacturing.

## Key Features (Phase 1)
- **Modular Monorepo**: Scalable layout for multiple applications.
- **Enterprise Backend**: Laravel 12 with DDD principles, Repository/Service patterns, and UUIDs.
- **Modern Frontend**: React 18, TypeScript, Vite, and Tailwind CSS.
- **Multi-tenancy**: Native support for Company and Branch scoping.
- **RBAC**: Fine-grained role-based access control with dot-notation permissions.
- **Audit Logging**: Centralized tracking of all entity changes.
- **Infrastructure**: Dockerized environment for consistent delivery.

## Quick Start
1. Ensure Docker is installed.
2. Run `docker-compose up -d`.
3. The API will be available at `http://localhost:8000` (via Nginx/FPM).
4. The Web app will be available at `http://localhost:5173`.

## Documentation
Refer to the `docs/` directory for detailed documentation:
- [Architecture](./docs/Architecture.md)
- [Development Guide](./docs/DevelopmentGuide.md)
- [Coding Standards](./docs/CodingStandards.md)
- [Phase 1 Completion Report](./docs/Phase1Report.md)

## License
Proprietary / Enterprise License.
