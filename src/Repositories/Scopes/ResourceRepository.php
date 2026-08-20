<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ResourceRepositoryContract;

class ResourceRepository implements ResourceRepositoryContract
{
    public function all(): Collection
    {
        return PassportScopeResource::query()->get();
    }

    /**
     * Get all active scope resources.
     * @return Collection<PassportScopeResource>
     */
    public function active(): Collection
    {
        return PassportScopeResource::query()
            ->where('is_active', true)
            ->get();
    }

    /**
     * Count all scope resources.
     * @return int
     */
    public function count(): int
    {
        return PassportScopeResource::query()->count();
    }

    /**
     * Find a scope resource by its name.
     * @param string $name
     * @return PassportScopeResource|null
     */
    public function findByName(string $name): ?PassportScopeResource
    {
        return PassportScopeResource::query()
            ->where('name', $name)
            ->first();
    }

    /**
     * Check if the scope resources table is migrated.
     *
     * Returns false instead of throwing when the database itself is not
     * reachable (e.g. no database file yet on a fresh install), since
     * this is queried unconditionally on every application boot.
     * @return bool
     */
    public function isMigrated(): bool
    {
        try {
            return Schema::hasTable('passport_scope_resources');
        } catch (\Throwable) {
            return false;
        }
    }

    public function createResource(array $data): PassportScopeResource
    {
        return PassportScopeResource::query()->create($data);
    }

    public function deleteResource(PassportScopeResource $resource): bool
    {
        return $resource->delete();
    }

    public function updateResource(PassportScopeResource $resource, array $data): PassportScopeResource
    {
        $resource->update($data);
        return $resource;
    }
}
