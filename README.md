# Laravel Passport Authorization Core

[![Latest Version on Packagist](https://img.shields.io/packagist/v/n3xt0r/laravel-passport-authorization-core.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/laravel-passport-authorization-core)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=N3XT0R_laravel-passport-authorization-core&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=N3XT0R_laravel-passport-authorization-core)
![ISO 27001 Audit Ready](https://img.shields.io/badge/ISO%2027001-audit--ready-blue?style=flat-square)
![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/n3xt0r/laravel-passport-authorization-core/run-tests.yml?branch=main&label=tests&style=flat-square)
[![Maintainability](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core/maintainability.svg)](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core)
[![Code Coverage](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core/coverage.svg)](https://qlty.sh/gh/N3XT0R/projects/laravel-passport-authorization-core)
![Framework Agnostic Domain Layer](https://img.shields.io/badge/architecture-domain--core-blue?style=flat-square)
![OAuth2 / Passport Compatible](https://img.shields.io/badge/oauth2-laravel%20passport-blue?style=flat-square)
[![Total Downloads](https://img.shields.io/packagist/dt/n3xt0r/laravel-passport-authorization-core.svg?style=flat-square)](https://packagist.org/packages/n3xt0r/laravel-passport-authorization-core)
---

## Overview

**Laravel Passport Authorization Core** replaces implicit, scattered OAuth authorization with a **structured,
database-backed permission model**.

Instead of generic scopes floating around as strings, it enables you to define explicit permissions as **resource +
action** combinations (e.g., `user:read`, `invoice:delete`) and store them in the database. You then extend Passport's
`tokenCan()` and `hasScope()` methods to check these structured permissions at runtime.

The result: clear, auditable, fine-grained authorization that lives in your database, not in middleware magic or
configuration files.

---

## The Problem It Solves

### Without This Package

Typical Passport setups have scattered, implicit authorization:

- **Scopes are strings without structure:** `'users:read'` and `'users:read-all'` mean different things but look the
  same
- **No central source of truth:** Permissions are defined in middleware, policies, configuration, and CLI commands
- **Hard to manage:** Who has what permission? No systematic way to answer that question
- **Impossible to audit:** Authorization decisions are hidden. You can't review what a client can actually do without
  reading code
- **No database visibility:** Permissions exist in code or config, not queryable data
- **Manual governance:** Changing permissions requires code or CLI commands, no UI

Example: A Dropbox integration client has scopes defined via CLI. Nobody knows the exact permissions. Revoking requires
manual intervention. Auditing what it accessed requires parsing logs.

### With This Package

Authorization becomes explicit and database-driven:

- **Structured permissions:** Resources (`user`, `invoice`, `report`) + actions (`read`, `create`, `delete`, `update`,
  `list`) stored in the database
- **Single source of truth:** Permissions are queryable, not scattered across code
- **Clear governance:** Every permission is explicit, trackable, and can be reviewed in one place
- **Auditable by design:** Who has what permission is a database query, not a mystery
- **Runtime-ready:** Extended `tokenCan()` and `hasScope()` check the database, not guessing
- **UI-friendly:** Permissions can be managed through an admin interface (e.g., Filament Passport UI)

Example: The same Dropbox client has permissions explicitly stored: `files:read`, `files:write`. You can see exactly
what it can do, change permissions with a click, and audit who did what when.

---

## How It Works

### Permission Model

A permission is a **resource + action** combination:

```
Resource: "user"
Action: "read"
Permission: user:read
```

Permissions are stored explicitly in the database for each client or user.

### Runtime Checks

You extend Passport's token validation methods to check these database permissions:

```php
// Instead of generic scope checking:
// $token->can('users:read')

// You check structured permissions:
// Does this token have user:read permission?
$token->can('user', 'read')
```

The package provides the domain logic and API to make this work. You define the resources and actions that matter in
your application.

### Database-Backed

Permissions are not in configuration or code—they're in the database:

- Clear visibility: Query which permissions a client has
- Easy management: UI can assign/revoke permissions
- Auditable: Track who changed what permission when
- Flexible: Add new resources and actions without code changes

---

## Real-World Example: How Enterprise Platforms Do It

### GitHub OAuth Scopes (App-Level)

GitHub lets you grant broad capabilities:

- `repo:read` – read repositories
- `user:email` – access emails

But you can't say "read only repo X, not Y". It's coarse-grained.

### AWS IAM (Fine-Grained Resource Control)

AWS lets you specify exact permissions:

```json
{
    "Resource": "arn:aws:s3:::my-bucket/uploads/*",
    "Action": [
        "s3:GetObject",
        "s3:PutObject"
    ],
    "Effect": "Allow"
}
```

This says: "For this S3 bucket's uploads folder, allow read and write. Nothing else."

### This Package: The Middle Ground

You define resources and actions that matter to your app:

```
Resources: user, invoice, report, settings
Actions: list, read, create, update, delete

Permissions (stored in DB):
- Client A: user:read, invoice:read, invoice:create
- Client B: report:read, report:export
- User X: settings:update
```

Clear, queryable, auditable. Not as fine-grained as AWS, but way more structured than generic scopes.

---

## Core Concepts

### Resources

Entities in your system that need permission control:

- `user`
- `invoice`
- `report`
- `settings`
- etc.

### Actions

Operations you want to control:

- `list` – view a collection
- `read` – view a specific item
- `create` – create a new item
- `update` – modify an item
- `delete` – remove an item

### Permissions

The combination of resource + action, stored in the database:

- `user:read`
- `invoice:create`
- `report:delete`

### Token Authorization

Extended Passport methods check permissions at runtime:

```php
// Check if a token has a specific resource:action permission
if ($token->can('user', 'read')) {
    // Allow access
}

// Enhanced hasScope() for structured permissions
if ($request->user('api')->hasScope('invoice:create')) {
    // Allow action
}
```

---

## What This Package Does

- Defines a **domain model** for resource-based permissions (not generic scopes)
- Provides a **stable API** for checking permissions against the database
- Enables **extended Passport validation methods** (`tokenCan()`, `hasScope()`)
- Centralizes permission logic so UI and runtime enforcement use the same model
- Supports explicit, auditable permission assignment

---

## What This Package Does NOT Do

- Implement OAuth flows
- Replace Passport or modify token issuance/validation internals
- Enforce permissions by itself (you decide where to check)
- Make assumptions about your application architecture
- Provide UI or admin panels

You decide how and where to enforce permissions. This package defines the model and provides the tools.

---

## Typical Consumers

This package is designed to be used by:

- **Admin interfaces:** Filament Passport UI for managing client and user permissions
- **API guards:** Middleware and route-level checks that verify token permissions
- **Controllers:** Authorization checks before processing requests
- **Policy classes:** Laravel's native authorization patterns backed by database permissions
- **Audit systems:** Logging what permissions were checked and granted
- **Background jobs:** Permission checks for async task execution

---

## Core Features

- **Resource + action permission model** (not generic scopes)
- **Database-backed permissions** (queryable, auditable, visible)
- **Extended Passport methods** for structured permission checking
- **Explicit permission assignment** per client or user
- **Single source of truth** for authorization across your application
- **Support for custom Passport models**
- **Clear separation between domain logic and enforcement**
- **Foundation for compliance and governance workflows**

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

## Documentation

Detailed documentation is available in the `docs/` directory:

- **[Usage](docs/usage.md)** – How to define resources, actions, and check permissions
- **[Usecase Overview](docs/usecases/index.md)** – Complete reference of available patterns and integration scenarios
- **[Configuration](docs/configuration.md)** – Optional configuration, model mappings, and extensibility

Documentation reflects the package architecture. Start with Usage to understand the permission model and API.

---

## Configuration & Extensibility

- Define custom resources and actions for your application
- Extend Passport's validation methods with your permission logic
- Support for custom Passport models
- No hard coupling to specific patterns or workflows
- Designed for incremental adoption alongside existing Passport implementations

---

## Audit & Compliance

This package is **audit-friendly by design**:

- Permissions are explicit and stored in the database
- Every permission assignment is a queryable fact, not implicit configuration
- Authorization context (resource, action, token) is deterministic and traceable
- Pair with activity logging for complete audit trails

> Note: Compliance certifications are organization-specific. This package enables the structured, auditable permission
> model required for compliance; it does not constitute compliance by itself.

---

## Relationship to Filament Passport UI

This package serves as the **domain core** for [Filament Passport UI](https://github.com/N3XT0R/filament-passport-ui):

- All permission logic and validation lives here
- Filament Passport UI provides the administrative interface for assigning permissions
- Both packages evolve independently with a stable boundary between domain and UI

---

## Status

Actively developed and considered foundational infrastructure. Architectural feedback, issues, and contributions
welcome.
