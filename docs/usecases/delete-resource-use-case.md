# DeleteResourceUseCase

## Purpose

Deletes a Passport scope resource using the `ResourceService` and emits a `ResourceDeletedEvent` on success.

## When to use

Use this when a resource should be removed from the system.

## Inputs

- `PassportScopeResource $resource`: The resource model to delete.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output

- Returns `bool` indicating whether the delete succeeded.

## Events

- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeResource\ResourceDeletedEvent` is dispatched when
  deletion succeeds.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\DeleteResourceUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

$useCase = app(DeleteResourceUseCase::class);
$resource = PassportScopeResource::query()->firstOrFail();

$deleted = $useCase->execute($resource, auth()->user());
```
