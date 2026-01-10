<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsRevokedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;
use N3XT0R\LaravelPassportAuthorizationCore\Support\Resolver\GrantableTokenableResolver;

/**
 * Use case to revoke grants from a tokenable entity.
 */
readonly class RevokeGrantsFromTokenableUseCase
{
    public function __construct(
        protected GrantableTokenableResolver $grantableTokenableResolver,
        protected GrantService $grantService,
    ) {
    }

    public function execute(
        int|string $ownerId,
        int|string $contextClientId,
        array $scopes,
        ?Authenticatable $actor = null
    ): void {
        $context = $this->grantableTokenableResolver->resolve($ownerId, $contextClientId);
        $this->grantService->revokeGrantsFromTokenable(
            tokenable: $context->tokenable,
            scopes: $scopes,
            actor: $actor,
            contextClient: $context->contextClient
        );

        TokenableGrantsRevokedEvent::dispatch(
            model: $context->tokenable,
            scopes: $scopes,
            contextClient: $context->contextClient,
            actor: $actor
        );
    }
}
