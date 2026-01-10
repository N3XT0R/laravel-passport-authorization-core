# GetGrantsForTokenableUseCase

## Purpose

Fetches all scope grants assigned to a specific tokenable model, including related resources, actions, and context
clients.

## When to use

Use this when you already have a tokenable instance and want its granted scopes.

## Inputs

- `HasPassportScopeGrantsInterface $tokenable`: The tokenable model instance to inspect.

## Output

- `Collection<PassportScopeGrant>` containing the tokenable's scope grants.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\GetGrantsForTokenableUseCase;

$useCase = app(GetGrantsForTokenableUseCase::class);

$grants = $useCase->execute($owner);
```
