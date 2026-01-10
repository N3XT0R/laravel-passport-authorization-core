<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;

/**
 * Use case to get all scope grants.
 */
class GetAllGrantsUseCase
{
    public function __construct(
        protected ScopeGrantRepository $scopeGrantRepository
    ) {
    }

    public function execute(): Collection
    {
        return $this->scopeGrantRepository->getAllGrants();
    }
}
