# AssignGrantsToTokenableUseCase

## Purpose
Assigns a list of scope grants to a tokenable (owner) in the context of a specific client and dispatches a `TokenableGrantsAssignedEvent`.

## When to use
Use this when you need to add grants to a tokenable for a given client context without removing any existing grants.

## Inputs
- `int|string $ownerId`: Owner identifier resolved via `OwnerRepository::findByKey` and validated as grantable.
- `int|string $contextClientId`: Active client identifier resolved via `ClientRepository::findActive`.
- `array $scopes`: Scope identifiers to grant.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- No return value.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsAssignedEvent` is dispatched after the grants are assigned.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\AssignGrantsToTokenableUseCase;

$useCase = app(AssignGrantsToTokenableUseCase::class);

$useCase->execute(
    ownerId: 1,
    contextClientId: 10,
    scopes: ['orders.read', 'orders.write'],
    actor: auth()->user(),
);
```
