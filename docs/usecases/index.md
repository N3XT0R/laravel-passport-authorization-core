# Use Cases Overview

This section documents the application use cases available under `src/Application/UseCases`. Each use case encapsulates
a single application-level operation and is intended to be called from controllers, jobs, or other orchestration layers.

### Action Use Cases

| Use case                                                 | Description                                                                  |
|----------------------------------------------------------|------------------------------------------------------------------------------|
| [CreateActionUseCase](actions/create-action-use-case.md) | Creates a new scope action and emits an `ActionCreatedEvent` when persisted. |
| [EditActionUseCase](actions/edit-action-use-case.md)     | Updates an existing scope action and emits an `ActionUpdatedEvent`.          |
| [DeleteActionUseCase](actions/delete-action-use-case.md) | Deletes a scope action and emits an `ActionDeletedEvent` on success.         |

### Resource Use Cases

| Use case                                                       | Description                                                             |
|----------------------------------------------------------------|-------------------------------------------------------------------------|
| [CreateResourceUseCase](resources/create-resource-use-case.md) | Creates a scope resource and emits a `ResourceCreatedEvent`.            |
| [EditResourceUseCase](resources/edit-resource-use-case.md)     | Updates a scope resource and emits a `ResourceUpdatedEvent`.            |
| [DeleteResourceUseCase](resources/delete-resource-use-case.md) | Deletes a scope resource and emits a `ResourceDeletedEvent` on success. |

### Client Use Cases

| Use case                                         | Description                                                                            |
|--------------------------------------------------|----------------------------------------------------------------------------------------|
| [ClearCacheUseCase](clear-cache-use-case.md)     | Clears the scope registry cache.                                                       |
| [CleanUpUseCase](clean-up-use-case.md)           | Removes orphaned scope grants.                                                         |
| [CreateClientUseCase](create-client-use-case.md) | Creates an OAuth client, assigns grants, and returns the client plus its plain secret. |
| [EditClientUseCase](edit-client-use-case.md)     | Updates an OAuth client, updates grants, and optionally emits a revoke event.          |
| [DeleteClientUseCase](delete-client-use-case.md) | Deletes an OAuth client and its scope grants.                                          |

### Cache/CleanUp Use Cases

| Use case                                     | Description                      |
|----------------------------------------------|----------------------------------|
| [ClearCacheUseCase](clear-cache-use-case.md) | Clears the scope registry cache. |
| [CleanUpUseCase](clean-up-use-case.md)       | Removes orphaned scope grants.   |

### Tokenable Use Cases

| Use case                                                                     | Description                                                          |
|------------------------------------------------------------------------------|----------------------------------------------------------------------|
| [AssignGrantsToTokenableUseCase](assign-grants-to-tokenable-use-case.md)     | Assigns scope grants to a tokenable for a specific client context.   |
| [RevokeGrantsFromTokenableUseCase](revoke-grants-from-tokenable-use-case.md) | Revokes scope grants from a tokenable for a specific client context. |
| [UpsertGrantsForTokenableUseCase](upsert-grants-for-tokenable-use-case.md)   | Upserts scope grants for a tokenable for a specific client context.  |

### Other Use Cases

| Use case                                                                   | Description                                       |
|----------------------------------------------------------------------------|---------------------------------------------------|
| [GetAllowedGrantTypeOptions](get-allowed-grant-type-options.md)            | Returns display labels for allowed grant types.   |
| [GetAllOwnersUseCase](get-all-owners-use-case.md)                          | Fetches all owner records.                        |
| [GetAllOwnersRelationshipUseCase](get-all-owners-relationship-use-case.md) | Fetches owners as key/value relationship options. |
| [SaveOwnershipRelationUseCase](save-ownership-relation-use-case.md)        | Reassigns a client to a new owner.                |

 
