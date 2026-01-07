# Configuration

This document explains how to configure **Laravel Passport Authorization Core** after installation.

As this package is a **domain-level authorization library**, configuration focuses on **model mappings, domain behavior,
and integration points** — not UI, routes, or presentation concerns.

---

## 1. Installation

Install the package via Composer:

```bash
composer require n3xt0r/laravel-passport-authorization-core
```

No interactive installer is provided or required.  
The package is designed to integrate cleanly into existing Laravel applications with minimal setup.

---

## 2. Publishing Configuration (Optional)

By default, the package uses **Laravel Passport’s standard models and conventions**.

If your application uses **custom Passport models** or requires explicit configuration, you may publish the
configuration
file:

```bash
php artisan vendor:publish   --provider="N3XT0R\LaravelPassportAuthorizationCore\PassportAuthorizationCoreServiceProvider"   --tag="config"
```

This will publish the configuration file to:

```
config/passport-authorization-core.php
```

Publishing the configuration is optional and only necessary if you need to override defaults.

---

## 3. Configuration File

The configuration file allows you to adjust **domain-level integration details**, such as:

- Passport model mappings
- Scope structure conventions
- Authorization domain behavior
- Extension points for application-specific logic

Typical concerns handled here include:

- Using custom `Client`, `Token`, or `Scope` models
- Defining how structured scopes are resolved
- Adapting authorization concepts to application-specific needs

This package does **not** define routes, guards, middleware, or UI-related configuration.

---

## 4. Integration Expectations

This package intentionally does **not** perform:

- Route registration
- Policy registration
- Authorization enforcement
- UI configuration

Instead, it provides **stable domain primitives** that can be consumed by:

- Admin interfaces (e.g. Filament-based tools)
- Policy layers
- Middleware
- API gateways
- Audit and compliance tooling

How these primitives are applied is fully controlled by the application.

---

## 5. Verification

After installation (and optional configuration publishing), verify that:

- Laravel Passport is installed and functional
- Tokens and scopes are managed by Passport as usual
- The configuration file exists **only if explicitly published**
- No unintended routes, views, or UI assets were introduced

The package should remain invisible at runtime unless explicitly used.

---

## Notes on Architecture

This package is designed as a **pure authorization domain layer**.

- It introduces structure and intent
- It does not enforce decisions
- It does not assume workflows
- It does not own application context

This makes it suitable for long-lived systems, shared libraries, and multi-UI environments.
