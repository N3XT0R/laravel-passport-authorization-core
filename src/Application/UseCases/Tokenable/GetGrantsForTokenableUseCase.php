<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;

/**
 * Use case to get all scope grants for a given tokenable model.
 */
class GetGrantsForTokenableUseCase
{
    public function __construct(
        protected ScopeGrantRepository $scopeGrantRepository
    ) {
    }

    public function execute(HasPassportScopeGrantsInterface $tokenable): Collection
    {
        return $this->scopeGrantRepository->getGrantsForTokenable($tokenable);
    }
}
