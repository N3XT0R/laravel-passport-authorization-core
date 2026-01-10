# GetGrantsForTokenableByTypeAndId

## Purpose

Fetches all scope grants assigned to a tokenable by providing the tokenable morph type and identifier.

## When to use

Use this when you do not have a model instance but know the tokenable type and ID.

## Inputs

- `string $tokenableType`: The morph class name of the tokenable model.
- `int|string $tokenableId`: The tokenable model identifier.

## Output

- `Collection<PassportScopeGrant>` containing the tokenable's scope grants.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\GetGrantsForTokenableByTypeAndId;

$useCase = app(GetGrantsForTokenableByTypeAndId::class);

$grants = $useCase->execute(
    tokenableType: App\Models\User::class,
    tokenableId: 1,
);
```
