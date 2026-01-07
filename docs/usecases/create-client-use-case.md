# CreateClientUseCase

## Purpose
Creates a new OAuth client, assigns scope grants, dispatches `OAuthClientCreatedEvent`, and returns the client plus its plain secret.

## When to use
Use this when you want to register a new Passport client (e.g., for a new integration) and assign initial scopes in a single operation.

## Inputs
- `array $data`: Client payload.
  - `name` (string, required)
  - `grant_type` (string, required): Must map to `OAuthClientType` values.
  - `owner` (model or identifier, optional): Owner instance or key that can be resolved via `OwnerRepository::findByKey`.
  - `redirect_uris` (array, optional)
  - `provider` (string, optional)
  - `confidential` (bool, optional)
  - `options` (array, optional)
  - `scopes` (array, optional): Scope identifiers to grant.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output
- Returns `ClientResultDTO` containing:
  - `client`: the created `Laravel\Passport\Client` model.
  - `plainSecret`: the plain text client secret (only available at creation).

## Events
- `N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientCreatedEvent` is dispatched after the client is created.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\CreateClientUseCase;

$useCase = app(CreateClientUseCase::class);

$result = $useCase->execute([
    'name' => 'My Integration',
    'grant_type' => 'client_credentials',
    'owner' => 1,
    'redirect_uris' => ['https://example.com/callback'],
    'confidential' => true,
    'scopes' => ['orders.read', 'orders.write'],
], auth()->user());

$client = $result->client;
$plainSecret = $result->plainSecret;
```
