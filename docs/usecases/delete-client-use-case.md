# DeleteClientUseCase

## Purpose

Deletes an OAuth client, removes all scope grants for that client, and emits `OauthClientDeletedEvent`.

## When to use

Use this when a client should be permanently removed from the system.

## Inputs

- `Client $client`: The Passport client to delete.
- `?Authenticatable $actor`: Optional user responsible for the change.

## Output

- Returns `bool` indicating whether the delete succeeded.

## Events

- `N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OauthClientDeletedEvent` is dispatched after deletion.

## Example

```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\DeleteClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;

$useCase = app(DeleteClientUseCase::class);
$client = Client::query()->firstOrFail();

$deleted = $useCase->execute($client, auth()->user());
```
