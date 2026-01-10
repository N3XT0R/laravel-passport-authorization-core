# GetAllowedGrantTypeOptions

## Purpose

Builds a map of allowed OAuth grant types to human-readable labels based on the configured allowed grant types.

## When to use

Use this when you need a UI-friendly list of grant type options (for example, in admin forms or API responses).

## Inputs

- None.

## Output

- Returns a `Collection` keyed by grant type value with a human-readable label as the value.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Grant\GetAllowedGrantTypeOptions;

$useCase = app(GetAllowedGrantTypeOptions::class);
$options = $useCase->execute();

// Example output: ['client_credentials' => 'Client credentials', ...]
```
