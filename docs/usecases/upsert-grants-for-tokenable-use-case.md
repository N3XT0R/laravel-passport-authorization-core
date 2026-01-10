# UpsertGrantsForTokenableUseCase

## Purpose
Upserts a list of scope grants for a tokenable (owner) in the context of a specific client and dispatches a `TokenableGrantUpsertedEvent`.

## When to use
Use this when you want the tokenable's grants for a given client context to match the provided list (adding missing grants and removing unlisted ones).

## Inputs
- `int|string $ownerId`: Owner identifier resolved via `OwnerRepository::findByKey` and validated as grantable.
- `int|string $contextClientId`: Active client identifier resolved via `ClientRepository::findActive`.
- `array $scopes`: Scope identifiers that should remain granted.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- No return value.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantUpsertedEvent` is dispatched after the grants are upserted.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\UpsertGrantsForTokenableUseCase;

$useCase = app(UpsertGrantsForTokenableUseCase::class);

$useCase->execute(
    ownerId: 1,
    contextClientId: 10,
    scopes: ['orders.read'],
    actor: auth()->user(),
);
```
