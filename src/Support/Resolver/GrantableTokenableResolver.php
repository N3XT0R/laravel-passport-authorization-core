<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Support\Resolver;

use N3XT0R\LaravelPassportAuthorizationCore\DTO\Context\GrantableTokenableContext;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\ActiveClientNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Owners\OwnerNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Tokenables\IsNotGrantableException;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;

class GrantableTokenableResolver
{
    public function __construct(
        protected OwnerRepository $ownerRepository,
        protected ClientRepository $clientRepository,
    ) {
    }

    public function resolve(
        int|string $ownerId,
        int|string $contextClientId,
    ): GrantableTokenableContext {
        $client = $this->clientRepository->findActive($contextClientId);

        if (!$client) {
            throw new ActiveClientNotExistsException($contextClientId);
        }

        $owner = $this->ownerRepository->findByKey($ownerId);
        $ownerModelClass = $this->ownerRepository->getOwnerModelClass();

        if (!$owner) {
            throw new OwnerNotExistsException($ownerModelClass, $ownerId);
        }

        if (!$owner instanceof HasPassportScopeGrantsInterface) {
            throw new IsNotGrantableException($ownerModelClass, $ownerId);
        }

        return new GrantableTokenableContext(
            tokenable: $owner,
            contextClient: $client,
        );
    }
}
