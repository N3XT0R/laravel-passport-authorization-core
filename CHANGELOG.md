# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- Added `context_client_id` column to the `passport_scope_grants` table to explicitly
  associate scope grants with an OAuth client context.
- Enabled client-scoped authorization grants for all grant types where a tokenable
  (e.g. user or service account) acts in the context of a specific client.
- Improved authorization clarity and auditability by making client context an
  explicit part of scope grant assignments.
- Introduced `GrantableTokenableResolver` to centralize resolution and validation of
  grantable tokenables and their active client context.
- Added `GrantableTokenableContext` DTO to represent resolved authorization context
  in a typed, explicit form.
- Added domain events for tokenable grant lifecycle:
    - `TokenableGrantsAssigned`
    - `TokenableGrantsRevoked`
    - `TokenableGrantsUpserted`
- Added application use cases for managing tokenable grants:
    - assign grants to tokenables
    - revoke grants from tokenables
    - upsert grants for tokenables
- Established a consistent, event-driven authorization workflow for tokenable grant
  changes, enabling auditability and external integration.

### Fixed

- Fixed wrong normalization on `PassportScopeGrant::toScopeString` incorrect scope strings like `resource.` instead of
  `resource:`.

## [1.1.0] - 2025-01-09

### Changed

- Changed constructor property visibility from `private` to `protected` to allow safe inheritance and extension of core
  classes.

## [1.0.0] - 2025-01-07

### Added

- Extracted all non-UI authorization and domain logic from **Filament Passport UI** into this dedicated core package.
- Introduced a UI-agnostic, domain-oriented authorization layer built on top of Laravel Passport.
- Added explicit **Application / Usecase** layer as the only supported integration surface.
- Added structured scope modeling (resource + action) as a first-class domain concept.
- Added reusable authorization context abstractions for inspection, auditing, and enforcement.
- Added clear separation between domain, application, and infrastructure concerns.
- Added configuration support for custom Passport models and domain integration.
- Added comprehensive documentation covering architecture, configuration, usage, and use cases.
- Established a stable foundation for multiple UI or integration layers to consume shared authorization logic.
