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
     * @param string|int|null $contextClientId
     * @return PassportScopeGrant
     */
    public function createScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $contextClientId = null
    ): PassportScopeGrant {
        return PassportScopeGrant::create([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
            'context_client_id' => $contextClientId,
        ]);
    }

    /**
     * Create or update a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $contextClientId
     * @return PassportScopeGrant
     */
    public function createOrUpdateScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $contextClientId = null
    ): PassportScopeGrant {
        return PassportScopeGrant::updateOrCreate([
            'tokenable_type' => $tokenable->getMorphClass(),
            'tokenable_id' => $tokenable->getKey(),
            'resource_id' => $resourceId,
            'action_id' => $actionId,
            'context_client_id' => $contextClientId,
        ]);
    }

    /**
     * Delete a scope grant for the given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $contextClientId
     * @return int
     */
    public function deleteScopeGrantForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $contextClientId = null
    ): int {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey())
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($contextClientId) {
            $query->where('context_client_id', $contextClientId);
        }

        return $query->delete();
    }

    /**
     * Check if a tokenable has a scope grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $contextClientId
     * @return bool
     */
    public function tokenableHasScopeGrant(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $contextClientId = null
    ): bool {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey())
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($contextClientId) {
            /**
             * @note
             * backward compatibility: check for both null and specific client ID
             */
            $query->where(function (Builder $query) use ($contextClientId) {
                $query->whereNull('context_client_id')
                    ->orWhere('context_client_id', $contextClientId);
            });
        }

        return $query->exists();
    }

    /**
     * Check if the given tokenable has a specific scope grant.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param int $resourceId
     * @param int $actionId
     * @param string|int|null $contextClientId
     * @return bool
     */
    public function tokenableHasGrant(
        HasPassportScopeGrantsInterface $tokenable,
        int $resourceId,
        int $actionId,
        string|int|null $contextClientId = null
    ): bool {
        $query = $tokenable->passportScopeGrants()
            ->where('resource_id', $resourceId)
            ->where('action_id', $actionId);

        if ($contextClientId) {
            /**
             * @note
             * backward compatibility: check for both null and specific client ID
             */
            $query->where(function (Builder $query) use ($contextClientId) {
                $query->whereNull('context_client_id')
                    ->orWhere('context_client_id', $contextClientId);
            });
        }

        return $query->exists();
    }

    /**
     * Get all scope grants for the given tokenable.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string|int|null $contextClientId
     * @return Collection<PassportScopeGrant>
     */
    public function getTokenableGrants(
        HasPassportScopeGrantsInterface $tokenable,
        string|int|null $contextClientId = null
    ): Collection {
        $query = $tokenable->passportScopeGrants()
            ->with(['resource', 'action']);

        if ($contextClientId) {
            /**
             * @note
             * backward compatibility: check for both null and specific client ID
             */
            $query->where(function (Builder $query) use ($contextClientId) {
                $query->whereNull('context_client_id')
                    ->orWhere('context_client_id', $contextClientId);
            });
        }


        return $query->get();
    }

    /**
     * Delete all scope grants for the given tokenable.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @param string|int|null $contextClientId
     * @return int
     */
    public function deleteAllGrantsForTokenable(
        HasPassportScopeGrantsInterface $tokenable,
        string|int|null $contextClientId = null
    ): int {
        $query = PassportScopeGrant::where('tokenable_type', $tokenable->getMorphClass())
            ->where('tokenable_id', $tokenable->getKey());

        if ($contextClientId) {
            $query->where('context_client_id', $contextClientId);
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

    /**
     * Get all scope grants.
     * @return Collection<PassportScopeGrant>
     */
    public function getAllGrants(): Collection
    {
        return PassportScopeGrant::with([
            'tokenable',
            'resource',
            'action',
            'contextClient'
        ])->get();
    }

    /**
     * Get all grants for a given tokenable model.
     * @param HasPassportScopeGrantsInterface $tokenable
     * @return Collection<PassportScopeGrant>
     */
    public function getGrantsForTokenable(HasPassportScopeGrantsInterface $tokenable): Collection
    {
        return $tokenable->passportScopeGrants()
            ->with(['resource', 'action', 'contextClient'])
            ->get();
    }
}
