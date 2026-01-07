# CreateResourceUseCase

## Purpose
Creates a new Passport scope resource using the `ResourceService` and emits a `ResourceCreatedEvent`.

## When to use
Use this to register a new resource (for example, `orders`, `invoices`, or any domain resource) that actions/scopes can be attached to.

## Inputs
- `array $data`: Resource payload (e.g., `name`, `description`, or other resource fields).
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns the created `PassportScopeResource` model instance.

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeResource\ResourceCreatedEvent` is dispatched after creation.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\CreateResourceUseCase;

$useCase = app(CreateResourceUseCase::class);

$resource = $useCase->execute([
    'name' => 'orders',
    'description' => 'Order data and workflows.',
], auth()->user());
```
