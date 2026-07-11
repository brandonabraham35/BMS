# ADR 0001: Generic Policy Engine

## Status
Accepted

## Context
BMS Enterprise needs a way to manage business rules (Policies) that can vary at different levels of the organization. Previous implementation had hardcoded categories and validation.

## Decision
We will implement a Registry-based Policy Engine.
1. Policies are defined via a `PolicyRegistry`.
2. Discovery endpoints allow the UI to dynamically render forms.
3. Validation is pluggable via `PolicyValidatorInterface`.
4. Resolution supports recursive ancestry lookup.

## Consequences
- Modules can register policies without core changes.
- Performance depends on the caching layer.
- Slight complexity increase in resolution logic.
