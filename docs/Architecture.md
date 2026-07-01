# BMS Enterprise Architecture

## Overview
BMS Enterprise is a modular, multi-tenant Business Management Platform designed for scalability and extensibility.

## Core Principles
- **Clean Architecture**: Separation of concerns between layers.
- **Modular Monorepo**: Shared packages with independent applications.
- **Domain-Driven Design (DDD)**: Ready for domain-specific logic isolation.
- **Multi-tenancy**: Native support for multiple companies and branches.

## Backend (Laravel 12)
- **Repository Pattern**: Data access abstraction.
- **Service Layer**: Business logic isolation.
- **UUIDs**: All primary keys are UUIDs.
- **Soft Deletes**: Native support for data recovery and auditing.

## Frontend (React + Vite + TS)
- **Component-Based**: Reusable UI components.
- **State Management**: Zustand for global state, TanStack Query for server state.
- **Theme Support**: Native dark/light mode via Tailwind CSS.

## Infrastructure
- **Docker**: Containerized environment for consistent development and deployment.
- **PostgreSQL**: Primary relational database.
- **Redis**: Caching and session management.
