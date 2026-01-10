<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;

class ScopeGrantRepository
{
    /**
     * Create a new scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $clientId
     * @return PassportScopeGrant
     */
    public function createScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $clientId = null
    ): PassportScopeGrant {
        return PassportScopeGrant::create([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
            'context_client_id' => $clientId,
        ]);
    }

    /**
     * Create or update a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $clientId
     * @return PassportScopeGrant
     */
    public function createOrUpdateScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $clientId = null
    ): PassportScopeGrant {
        return PassportScopeGrant::updateOrCreate([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
            'context_client_id' => $clientId,
        ]);
    }

    /**
     * Delete a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $clientId
     * @return int
     */
    public function deleteScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $clientId = null
    ): int {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey())
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($clientId) {
            $query->where('context_client_id', $clientId);
        }

        return $query->delete();
    }

    /**
     * Check if a tokenable has a scope grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $clientId
     * @return bool
     */
    public function tokenableHasScopeGrant(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $clientId = null
    ): bool {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey())
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($clientId) {
            $query->where('context_client_id', $clientId);
        }

        return $query->exists();
    }

    /**
     * Check if the given tokenable has a specific scope grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $clientId
     * @return bool
     */
    public function tokenableHasGrant(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $clientId = null
    ): bool {
        $query = $tokenable->passportScopeGrants()
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($clientId) {
            /**
             * backward compatibility: check for both null and specific client ID
             */
            $query->where(function (Builder $query) use ($clientId) {
                $query->whereNull('context_client_id')
                    ->orWhere('context_client_id', $clientId);
            });
        }

        return $query->exists();
    }

    /**
     * Get all scope grants for the given tokenable.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string|int|null $clientId
     * @return Collection<PassportScopeGrant>
     */
    public function getTokenableGrants(
        HasPassportScopeGrantsInterface $tokenable,
        string|int|null $clientId = null
    ): Collection {
        $query = $tokenable->passportScopeGrants()
            ->with(['resource', 'action']);

        if ($clientId) {
            $query->where('context_client_id', $clientId);
        }


        return $query->get();
    }

    /**
     * Delete all scope grants for the given tokenable.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string|int|null $clientId
     * @return int
     */
    public function deleteAllGrantsForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        string|int|null $clientId = null
    ): int {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey());

        if ($clientId) {
            $query->where('context_client_id', $clientId);
        }


        return (int)$query->delete();
    }

    /**
     * Delete all scope grants that are orphaned (i.e., their tokenable model no longer exists).
     * @return int
     */
    public function deleteTokenableOrphans(): int
    {
        return (int)PassportScopeGrant::whereDoesntHave('tokenable')
            ->delete();
    }
}
