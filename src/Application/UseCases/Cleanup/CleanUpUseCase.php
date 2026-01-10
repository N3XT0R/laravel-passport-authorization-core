<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Cleanup;

use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;

/**
 * Use case to clean up
 */
readonly class CleanUpUseCase
{
    public function __construct(
        protected ScopeGrantRepository $scopeGrantRepository,
    ) {
    }

    public function execute(): void
    {
        $this->scopeGrantRepository->deleteTokenableOrphans();
    }
}
