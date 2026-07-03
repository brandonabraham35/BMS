# BMS Enterprise Architecture

## Overview
BMS Enterprise is a modular, multi-tenant Business Management Platform designed for enterprise-scale scalability and global extensibility.

## Core Principles
- **Clean Architecture**: Strict separation of concerns between Controllers, Services, and Repositories.
- **Hierarchical Multi-Tenancy**: Native support for complex organizational structures:
    - **Workspace**: Top-level organization or business group.
    - **Company**: Legal entities under a workspace (supports parent-child hierarchy).
    - **Branch**: Physical or logical locations under a company.
    - **Department**: Functional units within a branch (supports hierarchy).
    - **Team**: Agile groups within a department.
- **Domain-Driven Design (DDD)**: Logic isolation within bounded contexts (Identity, Organization, etc.).
- **Context-Aware Settings**: Hierarchical configuration engine with inheritance and overrides (Platform -> Workspace -> Company -> Branch).

## Backend (Laravel 12)
- **Tenant Resolution**: Automatic request-lifecycle context resolution via Middleware and `TenantContext`.
- **Repository Pattern**: Data access abstraction for all core entities.
- **Service Layer**: Bounded context logic isolation.
- **UUIDs**: All primary keys are UUIDs for global uniqueness and security.
- **Soft Deletes**: Native support for data recovery and full entity auditing.
- **Audit System**: Centralized logging of all state changes, including IP tracking and user-agent recording.

## Frontend (React + Vite + TS)
- **Component-Based**: Reusable UI components powered by Shadcn/UI.
- **State Management**: Zustand for global UI state, TanStack Query for robust server state management.
- **Theme Support**: Native dark/light mode via Tailwind CSS.

## Infrastructure
- **Docker**: Orchestrated environment (PostgreSQL 16, Redis, PHP 8.3).
- **PostgreSQL**: Primary relational database with workspace-level indexing.
- **Redis**: Prepared for high-performance caching and session storage.
