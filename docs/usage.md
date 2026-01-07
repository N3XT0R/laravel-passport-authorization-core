# Usage

This document describes how **Laravel Passport Authorization Core** is intended to be used within an application.

The package exposes its functionality exclusively through **explicit application-level use cases**.  
There is no direct interaction with low-level domain objects or infrastructure services.

All consumer code must go through the **Application / Usecase layer**.

---

## Architectural Intent

This package follows a **clean, layered architecture** with a strict separation of concerns:

- **Domain**  
  Pure authorization concepts (scopes, permissions, grants, context)

- **Application**  
  Explicit use cases that orchestrate domain behavior

- **Infrastructure**  
  Passport integration and persistence concerns

Consumers are expected to interact **only** with the Application layer.

---

## Application / Usecases

All supported interactions are exposed via classes located under:

```
Application/Usecases
```

Each use case represents a **single, explicit authorization-related operation**, such as:

- registering or synchronizing scopes
- resolving structured scopes
- evaluating authorization intent
- inspecting grants or permissions
- preparing authorization context for enforcement or auditing

Use cases are designed to:

- be explicit and intention-revealing
- encapsulate domain rules
- remain independent of UI or transport layers
- be safely callable from controllers, policies, jobs, or CLIs

There is no supported API outside of these use cases.
---

## Usecase Overview

For a structured overview of all available application use cases provided by this package, see:

- **[Usecase Overview](usecases/index.md)**

This section documents each use case individually, including:

- its purpose and intent
- the authorization concern it addresses
- expected inputs and outputs
- typical integration scenarios

The overview serves as the primary entry point when integrating the package into an application and should be consulted
before interacting with individual use cases.

---

## How to Use the Package

### 1. Identify the relevant use case

Determine which authorization-related operation your application needs to perform.

Examples:

- preparing scope definitions
- mapping application permissions to OAuth scopes
- inspecting authorization context for a request
- synchronizing Passport state with domain intent

Each of these concerns maps to a dedicated use case class.

---

### 2. Invoke the use case from your application layer

Use cases are designed to be invoked from:

- Controllers
- Policies
- Middleware
- Console commands
- Background jobs
- Admin or integration tooling

The package does **not** assume a specific invocation context.

---

### 3. Handle results at the application boundary

Use cases return structured results or domain value objects.

Your application is responsible for:

- enforcing authorization decisions
- translating results into HTTP responses or UI state
- logging or auditing actions where required

The core package deliberately avoids making decisions on behalf of the application.

---

## What You Should *Not* Do

- ❌ Do not access domain entities directly
- ❌ Do not rely on internal services or repositories
- ❌ Do not couple UI or controllers to infrastructure details
- ❌ Do not bypass use cases for convenience

All public interaction must go through the **Application / Usecases** layer.

---

## Why This Matters

This design ensures that:

- authorization logic remains centralized and testable
- UI layers stay thin and replaceable
- domain rules evolve without breaking consumers
- multiple frontends or interfaces can reuse the same core
- auditability and reasoning remain intact

The Application / Usecase layer is the **contract** between this package and its consumers.

---

## Summary

To use **Laravel Passport Authorization Core** correctly:

1. Treat it as a domain library, not a helper toolkit
2. Interact only via `Application/Usecases`
3. Keep enforcement and presentation in your application
4. Let the core define intent — not behavior

This ensures long-term stability, clarity, and architectural integrity.

