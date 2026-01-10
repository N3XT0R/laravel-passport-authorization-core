# ClearCacheUseCase

## Purpose

Clears the scope registry cache via the `ScopeRegistryService`.

## When to use

Use this after changing scope resources/actions or when you need to force the registry to be rebuilt.

## Inputs

- None.

## Output

- Returns `void`.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Cleanup\ClearCacheUseCase;

$useCase = app(ClearCacheUseCase::class);
$useCase->execute();
```
