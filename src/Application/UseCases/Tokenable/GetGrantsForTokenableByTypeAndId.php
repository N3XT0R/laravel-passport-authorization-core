<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;

/**
 * Use case to get all scope grants for a given tokenable type and ID.
 */
class GetGrantsForTokenableByTypeAndId
{
    public function __construct(
        protected ScopeGrantRepository $scopeGrantRepository
    ) {
    }

    /**
     * @param string $tokenableType
     * @param int|string $tokenableId
     * @return Collection<PassportScopeGrant>
     */
    public function execute(string $tokenableType, int|string $tokenableId): Collection
    {
        return $this->scopeGrantRepository->getGrantsForTokenableByTypeAndId(
            $tokenableType,
            $tokenableId
        );
    }
}
