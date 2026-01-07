# CleanUpUseCase

## Purpose
Removes orphaned scope grants via the `ScopeGrantRepository`.

## When to use
Use this as a maintenance task to remove grants whose tokenable owners no longer exist.

## Inputs
- None.

## Output
- Returns `void`.

## Events
- No events are dispatched.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Cleanup\CleanUpUseCase;

$useCase = app(CleanUpUseCase::class);
$useCase->execute();
```
