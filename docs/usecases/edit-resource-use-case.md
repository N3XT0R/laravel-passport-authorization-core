# EditResourceUseCase

## Purpose
Updates an existing Passport scope resource using the `ResourceService` and emits a `ResourceUpdatedEvent`.

## When to use
Use this when you need to change resource details such as name or description.

## Inputs
- `PassportScopeResource $resource`: The resource model to update.
- `array $data`: Updated resource attributes.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns the updated `PassportScopeResource` model instance.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeResource\ResourceUpdatedEvent` is dispatched after update.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\EditResourceUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;

$useCase = app(EditResourceUseCase::class);
$resource = PassportScopeResource::query()->firstOrFail();

$updated = $useCase->execute($resource, [
    'description' => 'Updated resource description.',
], auth()->user());
```
