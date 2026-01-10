# GetAllClientsUseCase

## Purpose

Fetches OAuth clients, optionally restricting the result to active clients only.

## When to use

Use this when you need to list clients for administration screens, auditing, or reporting.

## Inputs

- `bool $onlyActive` (optional): When `true`, returns only active clients.

## Output

- `Collection<Client>` containing matching Passport clients.

## Events

- No events are dispatched.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\GetAllClientsUseCase;

$useCase = app(GetAllClientsUseCase::class);

$clients = $useCase->execute();
```
