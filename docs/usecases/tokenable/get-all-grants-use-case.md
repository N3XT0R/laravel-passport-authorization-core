# GetAllGrantsUseCase

## Purpose

Fetches every scope grant in the system, including related tokenables, resources, actions, and context clients.

## When to use

Use this when you need a global list of all grants for administration, auditing, or reporting.

## Inputs

- None.

## Output

- `Collection<PassportScopeGrant>` containing all scope grants.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\GetAllGrantsUseCase;

$useCase = app(GetAllGrantsUseCase::class);

$grants = $useCase->execute();
```
