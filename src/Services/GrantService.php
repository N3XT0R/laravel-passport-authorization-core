<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ActionRepositoryContract;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ResourceRepositoryContract;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Support\OAuth\ValueObjects\Scope;

readonly class GrantService
{
    public function __construct(
        protected ScopeGrantRepository $scopeGrantRepository,
        protected ResourceRepositoryContract $resourceRepository,
        protected ActionRepositoryContract $actionRepository,
    ) {
    }

    /**
     * Grant a scope to the given tokenable model.
     * @param HasPassportScopeGrantsInterface&Model $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @param Authenticatable|null $actor
     * @param Client|null $contextClient
     * @return PassportScopeGrant
     */
    public function grantScopeToTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
        ?Authenticatable $actor = null,
        ?Client $contextClient = null,
    ): PassportScopeGrant {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            throw new \InvalidArgumentException("Resource '{$resourceName}' not found.");
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            throw new \InvalidArgumentException("Action '{$actionName}' not found.");
        }

        $result = $this->scopeGrantRepository->createOrUpdateScopeGrantForTokenable(
            $tokenable,
            $resource->getKey(),
            $action->getKey(),
            $contextClient?->getKey(),
        );

        if ($result && $actor) {
            activity('oauth')
                ->causedBy($actor)
                ->withProperties([
                    'tokenable' => [
                        'type' => $tokenable->getMorphClass(),
                        'id' => $tokenable->getKey(),
                    ],
                    'context_client_id' => $contextClient?->getKey(),
                    'granted_scope' => new Scope($resourceName, $actionName)->toString(),
                ])
                ->log('OAuth scope grant given to tokenable');
        }


        return $result;
    }

    /**
     * Revoke a scope from the given tokenable model.
     * @param Model&HasPassportScopeGrantsInterface $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @param Authenticatable|null $actor
     * @param Client|null $contextClient
     * @return bool
     */
    public function revokeScopeFromTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
        ?Authenticatable $actor = null,
        ?Client $contextClient = null,
    ): bool {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            throw new \InvalidArgumentException("Resource '{$resourceName}' not found.");
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            throw new \InvalidArgumentException("Action '{$actionName}' not found.");
        }

        $result = $this->scopeGrantRepository->deleteScopeGrantForTokenable(
                $tokenable,
                $resource->getKey(),
                $action->getKey(),
            ) > 0;

        if (true === $result && $actor) {
            activity('oauth')
                ->causedBy($actor)
                ->withProperties([
                    'tokenable' => [
                        'type' => $tokenable->getMorphClass(),
                        'id' => $tokenable->getKey(),
                    ],
                    'context_client_id' => $contextClient?->getKey(),
                    'revoked_scope' => new Scope($resourceName, $actionName)->toString(),
                ])
                ->log('OAuth scope grant revoked from tokenable');
        }


        return $result;
    }

    /**
     * Check if the tokenable has a specific grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string $resourceName
     * @param string $actionName
     * @param Client|null $contextClient
     * @return bool
     */
    public function tokenableHasGrant(
        HasPassportScopeGrantsInterface $tokenable,
        string $resourceName,
        string $actionName,
        ?Client $contextClient = null,
    ): bool {
        $resource = $this->resourceRepository->findByName($resourceName);
        if ($resource === null) {
            return false;
        }

        $action = $this->actionRepository->findByName($actionName);
        if ($action === null) {
            return false;
        }

        return app(ScopeGrantRepository::class)->tokenableHasScopeGrant(
            tokenable: $tokenable,
            resourceId: $resource->getKey(),
            actionId: $action->getKey(),
            clientId: $contextClient?->getKey(),
        );
    }

    /**
     * Check if the tokenable has a grant to a specific scope.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string $scopeString
     * @param Client|null $contextClient
     * @return bool
     */
    public function tokenableHasGrantToScope(
        HasPassportScopeGrantsInterface $tokenable,
        string $scopeString,
        ?Client $contextClient = null,
    ): bool {
        $scope = Scope::fromString($scopeString);

        return $this->tokenableHasGrant(
            $tokenable,
            $scope->resource,
            $scope->action,
            $contextClient
        );
    }

    /**
     * Get all grants of the tokenable as scope strings.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param Client|null $contextClient
     * @return Collection<string>
     */
    public function getTokenableGrantsAsScopes(
        HasPassportScopeGrantsInterface $tokenable,
        ?Client $contextClient = null,
    ): Collection {
        $grants = $this->scopeGrantRepository->getTokenableGrants(
            tokenable: $tokenable,
            clientId: $contextClient?->getKey()
        );


        return $grants->map(fn(PassportScopeGrant $grant) => new Scope(
            $grant->resource->getAttribute('name'), $grant->action->getAttribute('name')
        )->toString());
    }


    /**
     * Give multiple grants to the tokenable based on the provided scopes.
     * @param HasPassportScopeGrantsInterface&Model $tokenable
     * @param array $scopes
     * @param Authenticatable|null $actor
     * @param Client|null $contextClient
     * @return void
     */
    public function giveGrantsToTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
        ?Client $contextClient = null,
    ): void {
        foreach ($scopes as $scopeString) {
            $scope = Scope::fromString($scopeString);

            if (
                $this->tokenableHasGrantToScope(
                    tokenable: $tokenable,
                    scopeString: $scopeString,
                    contextClient: $contextClient
                )
            ) {
                continue;
            }


            $this->grantScopeToTokenable(
                $tokenable,
                $scope->resource,
                $scope->action,
                $actor,
                $contextClient
            );
        }

        if ($actor) {
            activity('oauth')
                ->causedBy($actor)
                ->withProperties([
                    'tokenable' => [
                        'type' => $tokenable->getMorphClass(),
                        'id' => $tokenable->getKey(),
                    ],
                    'context_client_id' => $contextClient?->getKey(),
                    'granted_scopes' => $scopes,
                ])
                ->log('OAuth scope grants given to tokenable');
        }
    }

    /**
     * Revoke multiple grants from the tokenable based on the provided scopes.
     * @param Model&HasPassportScopeGrantsInterface $tokenable
     * @param array $scopes
     * @param Authenticatable|null $actor
     * @param Client|null $contextClient
     * @return void
     */
    public function revokeGrantsFromTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
        ?Client $contextClient = null,
    ): void {
        foreach ($scopes as $scopeString) {
            $scope = Scope::fromString($scopeString);

            if (!$this->tokenableHasGrantToScope($tokenable, $scopeString, $contextClient)) {
                continue;
            }

            $this->revokeScopeFromTokenable(
                $tokenable,
                $scope->resource,
                $scope->action,
                $contextClient
            );
        }

        if ($actor) {
            activity('oauth')
                ->causedBy($actor)
                ->withProperties([
                    'tokenable' => [
                        'type' => $tokenable->getMorphClass(),
                        'id' => $tokenable->getKey(),
                    ],
                    'context_client_id' => $contextClient?->getKey(),
                    'revoked_scopes' => $scopes,
                ])
                ->log('OAuth scope grants revoked from tokenable');
        }
    }

    /**
     * Upsert grants for the tokenable based on the provided scopes.
     * @param HasPassportScopeGrantsInterface&Model $tokenable
     * @param array $scopes
     * @param Authenticatable|null $actor
     * @param Client|null $contextClient
     * @return void
     */
    public function upsertGrantsForTokenable(
        Model&HasPassportScopeGrantsInterface $tokenable,
        array $scopes,
        ?Authenticatable $actor = null,
        ?Client $contextClient = null,
    ): void {
        $existingGrants = $this->getTokenableGrantsAsScopes($tokenable)->toArray();

        $scopesToRevoke = array_diff($existingGrants, $scopes);
        $scopesToGrant = array_diff($scopes, $existingGrants);

        $this->revokeGrantsFromTokenable($tokenable, $scopesToRevoke, $contextClient);
        $this->giveGrantsToTokenable($tokenable, $scopesToGrant, $contextClient);

        if ($actor) {
            activity('oauth')
                ->causedBy($actor)
                ->withProperties([
                    'tokenable' => [
                        'type' => $tokenable->getMorphClass(),
                        'id' => $tokenable->getKey(),
                    ],
                    'context_client_id' => $contextClient?->getKey(),
                    'upserted_scopes' => $scopes,
                ])
                ->log('OAuth scope grants upserted for tokenable');
        }
    }
}
