<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ActionRepositoryContract;

class ActionRepository implements ActionRepositoryContract
{
    /**
     * Get all scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function all(): Collection
    {
        return PassportScopeAction::query()->get();
    }

    /**
     * Get all active scope actions.
     * @return Collection<PassportScopeAction>
     */
    public function active(): Collection
    {
        return PassportScopeAction::query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Find a scope action by its name.
     * @param string $name
     * @return PassportScopeAction|null
     */
    public function findByName(string $name): ?PassportScopeAction
    {
        return PassportScopeAction::query()
            ->where('name', $name)
            ->first();
    }

    /**
     * Check if the scope actions table is migrated.
     *
     * Returns false instead of throwing when the database itself is not
     * reachable (e.g. no database file yet on a fresh install), since
     * this is queried unconditionally on every application boot.
     * @return bool
     */
    public function isMigrated(): bool
    {
        try {
            return Schema::hasTable('passport_scope_actions');
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Get the total count of scope actions.
     * @return int
     */
    public function count(): int
    {
        return PassportScopeAction::query()->count();
    }

    /**
     * Create a new scope action.
     * @param array $data
     * @return PassportScopeAction
     */
    public function createAction(array $data): PassportScopeAction
    {
        return PassportScopeAction::query()->create($data);
    }

    /**
     * Delete a scope action.
     * @param PassportScopeAction $action
     * @return bool
     */
    public function deleteAction(PassportScopeAction $action): bool
    {
        return $action->delete();
    }

    /**
     * Update a scope action.
     * @param PassportScopeAction $action
     * @param array $data
     * @return PassportScopeAction
     */
    public function updateAction(PassportScopeAction $action, array $data): PassportScopeAction
    {
        $action->update($data);
        return $action;
    }
}
