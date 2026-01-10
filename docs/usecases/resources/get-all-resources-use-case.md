# GetAllResourcesUseCase

## Purpose

Retrieves all Passport scope resources, optionally bypassing the resource cache.

## When to use

Use this when you need the complete list of resources to present options or validate scope selections.

## Inputs

- `bool $withoutCache` (optional): When `true`, loads resources directly from storage instead of the cache.

## Output

- `Collection<PassportScopeResource>` containing all resources.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\GetAllResourcesUseCase;

$useCase = app(GetAllResourcesUseCase::class);

$resources = $useCase->execute();
```
