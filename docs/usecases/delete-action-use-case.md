# DeleteActionUseCase

## Purpose
Deletes an existing Passport scope action using the `ActionService` and emits an `ActionDeletedEvent` on success.

## When to use
Use this when an action is no longer needed and should be removed from the system.

## Inputs
- `PassportScopeAction $action`: The action model to delete.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns `bool` indicating whether the delete succeeded.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionDeletedEvent` is dispatched when deletion succeeds.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\DeleteActionUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;

$useCase = app(DeleteActionUseCase::class);
$action = PassportScopeAction::query()->firstOrFail();

$deleted = $useCase->execute($action, auth()->user());
```
