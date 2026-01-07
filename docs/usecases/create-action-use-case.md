# CreateActionUseCase

## Purpose
Creates a new Passport scope action via the `ActionService` and emits an `ActionCreatedEvent` once the action is persisted.

## When to use
Use this when you want to register a new action that can be attached to scope resources (for example, `read`, `write`, or other domain-specific actions).

## Inputs
- `array $data`: Payload for the action (typically includes identifying attributes like `name` and `description`).
- `?Authenticatable $actor`: Optional user responsible for the change (used for auditing/event payloads).

## Output
- Returns the created `PassportScopeAction` model instance.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionCreatedEvent` is dispatched if the action was persisted.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\CreateActionUseCase;

$useCase = app(CreateActionUseCase::class);

$action = $useCase->execute([
    'name' => 'read',
    'description' => 'Read access to resources.',
], auth()->user());
```
