# GetAllOwnersUseCase

## Purpose
Retrieves all owner records via the `OwnerRepository`.

## When to use
Use this to load the full list of owner entities (for example, when populating a selection list or running audits).

## Inputs
- None.

## Output
- Returns an `Illuminate\Support\Collection` of owner models.

## Events
- No events are dispatched.

## Example
```php
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners\GetAllOwnersUseCase;

$useCase = app(GetAllOwnersUseCase::class);
$owners = $useCase->execute();
```
