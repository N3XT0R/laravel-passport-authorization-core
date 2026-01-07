# SaveOwnershipRelationUseCase

## Purpose
Assigns or reassigns an OAuth client to a specific owner.

## When to use
Use this when ownership of a client needs to change (for example, transferring a client between tenants).

## Inputs
- `Client|string|int $client`: The `Laravel\Passport\Client` instance or its identifier.
- `int $ownerId`: The owner identifier.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns `void`.

## Events
- No events are dispatched directly by this use case.

## Errors
- Throws `InvalidArgumentException` when the provided owner cannot be found.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners\SaveOwnershipRelationUseCase;

$useCase = app(SaveOwnershipRelationUseCase::class);
$useCase->execute(42, 10, auth()->user());
```
