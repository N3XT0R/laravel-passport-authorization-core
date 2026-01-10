# RevokeGrantsFromTokenableUseCase

## Purpose
Revokes a list of scope grants from a tokenable (owner) in the context of a specific client and dispatches a `TokenableGrantsRevokedEvent`.

## When to use
Use this when you need to remove specific grants from a tokenable for a given client context.

## Inputs
- `int|string $ownerId`: Owner identifier resolved via `OwnerRepository::findByKey` and validated as grantable.
- `int|string $contextClientId`: Active client identifier resolved via `ClientRepository::findActive`.
- `array $scopes`: Scope identifiers to revoke.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- No return value.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsRevokedEvent` is dispatched after the grants are revoked.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\RevokeGrantsFromTokenableUseCase;

$useCase = app(RevokeGrantsFromTokenableUseCase::class);

$useCase->execute(
    ownerId: 1,
    contextClientId: 10,
    scopes: ['orders.write'],
    actor: auth()->user(),
);
```
