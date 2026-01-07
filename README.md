# Laravel Passport Authorization Core

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/laravel-passport-authorization-core.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/laravel-passport-authorization-core)
![ISO 27001 Audit Ready](https://img.shields.io/badge/ISO%2027001-audit--ready-blue?style=flat-square)
![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/laravel-passport-authorization-core/run-tests.yml?branch=main&label=tests&style=flat-square)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core)
[![Code Coverage](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core/coverage.svg)](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core)
![Framework Agnostic Domain Layer](https://img.shields.io/badge/architecture-domain--core-blue?style=flat-square)
![OAuth2 / Passport Compatible](https://img.shields.io/badge/oauth2-laravel%20passport-blue?style=flat-square)

---

## Overview

**Laravel Passport Authorization Core** provides a **domain-oriented authorization model** on top of
**Laravel Passport**.

It defines **structured concepts** for scopes, permissions, grants, and authorization context **without any UI, admin
panel, or framework-specific assumptions** beyond Laravel itself.

This package is intended to serve as a **reusable foundation** for:

- administrative UIs (e.g. Filament, custom dashboards)
- API-level authorization enforcement
- policy-driven access control
- audit-aware security architectures

It does **not** implement OAuth flows and does **not** replace Passport.  
Passport remains the runtime authority for token issuance and validation.

---

## What this package does

This package introduces a **formal domain layer** for Passport-based authorization:

- Defines a **structured scope model** (instead of free-form strings)
- Encapsulates authorization intent as **resource + action**
- Provides reusable logic for reasoning about permissions and grants
- Centralizes authorization-related domain rules
- Exposes a stable API for UI layers and enforcement layers alike

All logic operates **above Passport**, never inside it.

---

## Core Concepts

### Structured Scopes

Instead of treating scopes as arbitrary strings, this package models them explicitly:

- Scopes represent **intent**, not implementation
- Typical structure:  
  `resource:action` (e.g. `users:read`, `orders:write`)
- Enables:
    - consistent naming
    - reasoning about permissions
    - grouping and documentation
    - safer long-term evolution

### Authorization Context

Authorization is modeled as a **contextual decision**, not a hardcoded rule:

- Which client?
- Which grant type?
- Which scopes?
- Which actor (user / machine)?
- Which resource and action?

This context can be:

- inspected
- logged
- audited
- reused across UI and runtime enforcement

### Passport Compatibility

- Uses Passport’s existing tables and models
- Does not alter token issuance, validation, or guards
- Works with default or custom Passport models
- Can be adopted incrementally

---

## What this package does *not* do

- ❌ No OAuth flow implementation
- ❌ No token issuance logic
- ❌ No UI or admin panel
- ❌ No policy enforcement by itself
- ❌ No assumptions about application architecture

This package **defines structure and intent**  enforcement remains the responsibility of the application.

---

## Why this exists

Laravel Passport deliberately avoids opinions about **authorization modeling** and **governance**.

In larger or long-lived systems this often leads to:

- Scopes as undocumented strings
- No shared understanding of permissions
- UI layers re-implementing domain logic
- Authorization rules scattered across policies, middleware, and services

**Laravel Passport Authorization Core** provides a **single, explicit domain model** that can be reused everywhere.

It enables:

- a shared mental model across teams
- clearer security reviews
- safer refactoring
- UI and runtime layers built on the same foundation

---

## Typical Usage

This package is designed to be consumed by:

- admin interfaces (e.g. Filament Passport UI)
- API gateways
- policy layers
- background services
- audit and compliance tooling

It acts as the **authorization backbone**, not the presentation layer.

---

## Requirements

- PHP ^8.4
- Laravel ^12
- Laravel Passport ^13

---

## Installation

```bash
composer require n3xt0r/laravel-passport-authorization-core
```

Publish configuration if needed:

```bash
php artisan vendor:publish --tag=passport-authorization-core-config
```

---

## Configuration & Extensibility

- Supports default and custom Passport models
- Designed for extension via:
    - custom scope providers
    - domain services
    - application-specific authorization rules
- No hard coupling to a specific UI or workflow

---

## Audit & Compliance Considerations

This package is **audit-friendly by design**:

- Explicit authorization concepts
- Clear separation of concerns
- Deterministic permission modeling

It can be combined with logging or audit libraries (e.g. activity logging) but does not enforce a specific solution.

> Note: Compliance certifications apply to organizations and processes.  
> This package supports auditability but does not constitute compliance by itself.

---

## Relationship to Filament Passport UI

This package serves as the **domain core** for:

https://github.com/N3XT0R/filament-passport-ui

- All non-UI authorization logic lives here
- Filament Passport UI focuses purely on administration and presentation
- Both packages evolve independently with a stable boundary

---

## Status

This package is under active development and considered **foundational infrastructure**.

Architectural discussion, feedback, and contributions are welcome.
