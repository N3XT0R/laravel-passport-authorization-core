# GetAllActionsUseCase

## Purpose

Retrieves all Passport scope actions, optionally bypassing the action cache.

## When to use

Use this when you need a list of every available action for administration, selection lists, or validation logic.

## Inputs

- `bool $withoutCache` (optional): When `true`, loads actions directly from storage instead of the cache.

## Output

- `Collection<PassportScopeAction>` containing all actions.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\GetAllActionsUseCase;

$useCase = app(GetAllActionsUseCase::class);

$actions = $useCase->execute();
```
