# GetAllOwnersRelationshipUseCase

## Purpose

Retrieves owners as a key/value collection suitable for relationship dropdowns. The keys come from the owner model's
primary key, and the values use the configured label attribute.

## When to use

Use this when building UI forms or API endpoints that need a list of owners in `id => label` format.

## Inputs

- None.

## Output

- Returns an `Illuminate\Support\Collection<int|string, string>` keyed by the owner model key and valued by the
  configured label attribute.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners\GetAllOwnersRelationshipUseCase;

$useCase = app(GetAllOwnersRelationshipUseCase::class);
$options = $useCase->execute();

// Example output: [1 => 'Acme Inc', 2 => 'Globex Corp']
```
