# EditClientUseCase

## Purpose

Updates an existing OAuth client, updates scope grants, dispatches `OAuthClientUpdatedEvent`, and dispatches
`OAuthClientRevokedEvent` when the client is revoked.

## When to use

Use this when you need to modify a client (name, redirect URIs, owner, confidentiality, or revocation) and synchronize
its grants.

## Inputs

- `Client $client`: The Passport client to update.
- `array $data`: Updated client payload.
    - `name` (string, required)
    - `owner` (model or identifier, optional)
    - `redirect_uris` (array, optional)
    - `provider` (string, optional)
    - `confidential` (bool, optional)
    - `options` (array, optional)
    - `revoked` (bool, optional)
    - `scopes` (array, optional): Scope identifiers to grant.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output

- Returns the updated `N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client` instance.

## Events

- `N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientUpdatedEvent` is dispatched after update.
- `N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientRevokedEvent` is dispatched if `revoked` is `true`.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\EditClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

$useCase = app(EditClientUseCase::class);
$client = Client::query()->firstOrFail();

$updated = $useCase->execute($client, [
    'name' => 'Updated Integration Name',
    'redirect_uris' => ['https://example.com/updated-callback'],
    'revoked' => false,
    'scopes' => ['orders:read'],
], auth()->user());
```
