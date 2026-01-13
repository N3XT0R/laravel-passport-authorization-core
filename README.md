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

**Laravel Passport Authorization Core** provides a **domain model and use cases** for structured access control on top
of Laravel Passport.

Instead of implicit authorization scattered across your codebase, it offers an explicit permission model: **resources
** (user, invoice, report) + **actions** (read, create, delete) stored in the database as queryable facts. You implement
enforcement however you need—middleware, policies, guards, custom logic.

Single source of truth. No opinions about how you validate.

---

## The Problem

### Without This Package

- Scopes are undocumented strings with no structure
- Permissions defined in code, config, and middleware—scattered
- No way to query "what can this client do?"
- Manual governance, impossible to audit
- Example: Dropbox integration created via CLI, permissions unclear, no visibility

### With This Package

- Permissions stored as `resource:action` in the database
- Single, queryable source of truth
- Clear what each client/user can do
- Full audit trail, systematic governance
- Example: Same client, explicit permissions visible in UI, queryable via code, revokable with confidence

---

## How It Works

### 1. Define Resources and Actions

```php
// Global actions (apply to any resource)
$readAction = Action::firstOrCreate(['name' => 'read']);
$createAction = Action::firstOrCreate(['name' => 'create']);

// Resources
$userResource = Resource::firstOrCreate(['name' => 'user']);
$invoiceResource = Resource::firstOrCreate(['name' => 'invoice']);

// Resource-specific actions (invoice only)
$exportAction = Action::firstOrCreate([
    'name' => 'export',
    'resource_id' => $invoiceResource->id
]);
```

### 2. Assign Permissions (Use Cases)

```php
$grant = app(GrantPermissionUseCase::class);

// User 5 can read users
$grant->execute(
    tokenableType: User::class,
    tokenableId: 5,
    resourceId: $userResource->id,
    actionId: $readAction->id
);

// Client 3 can create invoices
$grant->execute(
    tokenableType: Client::class,
    tokenableId: 3,
    resourceId: $invoiceResource->id,
    actionId: $createAction->id
);
```

### 3. Query & Enforce

```php
// Query permissions (single source of truth)
$check = app(CheckPermissionUseCase::class);
$hasPermission = $check->execute(User::class, 5, $userResource->id, $readAction->id);

// Enforce however you want
Route::post('/users', function () {
    if (!$check->execute(User::class, auth()->id(), 'user', 'create')) {
        abort(403);
    }
});
```

---



**Resources:** Entities needing permission control (user, invoice, report, etc.)

**Actions:** Operations you control. Global (`list`, `read`, `create`, `update`, `delete`) or resource-specific (
`export`, `approve`).

**Grants:** Permissions assigned to any `OAuthenticatable` entity (User, Client, ServiceAccount, or custom).

- Polymorphic: who has the permission?
- `resource_id` + `action_id`: which permission?
- `context_client_id` (optional): in context of which client?

**Use Cases:** Encapsulated business logic to manage permissions (see [Usecase Overview](docs/usecases/index.md)).

---

## What This Package Does

- Domain model for structured access control
- Use cases for managing permissions
- Polymorphic grant storage (User, Client, ServiceAccount, custom entities)
- Single source of truth for permissions
- Support for custom Passport models

---

## What This Package Does NOT Do

- Enforce permissions (you implement that)
- Implement OAuth flows
- Modify Passport internals
- Assume your application architecture
- Provide UI or middleware

---

## Requirements

- PHP ^8.4
- Laravel ^12
- Laravel Passport ^13

---

## Installation

```bash
composer require n3xt0r/laravel-passport-authorization-core
php artisan vendor:publish --tag=passport-authorization-core-config
```

---

## Documentation

- **[Usage](docs/usage.md)** – Working with resources, actions, and use cases
- **[Usecase Overview](docs/usecases/index.md)** – Complete reference of available use cases
- **[Configuration](docs/configuration.md)** – Custom Passport models, extensibility
- **[Enforcement Patterns](docs/enforcement.md)** – How to implement authorization in your app

---

## Audit & Compliance

- Permissions are explicit database facts, not implicit configuration
- Authorization context is deterministic and queryable
- Full audit trail via activity logging
- Supports ISO 27001 and similar compliance requirements

---

## Relationship to Filament Passport UI

This package is the **domain core** for [Filament Passport UI](https://github.com/N3XT0R/filament-passport-ui):

- Core: domain model + use cases
- Filament UI: admin interface for managing permissions
- Independent evolution with stable boundary

---

## Status

Actively developed. Feedback and contributions welcome.
