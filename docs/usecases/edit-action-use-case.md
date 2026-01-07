# EditActionUseCase

## Purpose
Updates an existing Passport scope action via the `ActionService` and emits an `ActionUpdatedEvent`.

## When to use
Use this when you need to modify a scope action's attributes (for example, updating its name or description).

## Inputs
- `PassportScopeAction $action`: The action model to update.
- `array $data`: Attributes to update.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns the updated `PassportScopeAction` model instance.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionUpdatedEvent` is dispatched after the update.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\EditActionUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

$useCase = app(EditActionUseCase::class);
$action = PassportScopeAction::query()->firstOrFail();

$updated = $useCase->execute($action, [
    'description' => 'Updated description for read access.',
], auth()->user());
```
