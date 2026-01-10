<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsAssignedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\ActiveClientNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Owners\OwnerNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Tokenables\IsNotGrantableException;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

readonly class AssignGrantsToTokenableUseCase
{
    public function __construct(
        protected OwnerRepository $ownerRepository,
        protected ClientRepository $clientRepository,
        protected GrantService $grantService,
    ) {
    }

    public function execute(
        int|string $ownerId,
        int|string $contextClientId,
        array $scopes,
        ?Authenticatable $actor = null
    ): void {
        $client = $this->clientRepository->findActive($contextClientId);

        if (!$client) {
            throw new ActiveClientNotExistsException($contextClientId);
        }

        $owner = $this->ownerRepository->findByKey($ownerId);
        $ownerModelClass = $this->ownerRepository->getOwnerModelClass();

        if (!$owner) {
            throw new OwnerNotExistsException($ownerModelClass, $ownerId);
        }

        if ($owner instanceof HasPassportScopeGrantsInterface === false) {
            throw new IsNotGrantableException($ownerModelClass, $ownerId);
        }

        $this->grantService->giveGrantsToTokenable(
            tokenable: $owner,
            scopes: $scopes,
            actor: $actor,
            contextClient: $client
        );

        TokenableGrantsAssignedEvent::dispatch(
            model: $owner,
            scopes: $scopes,
            contextClient: $client,
            actor: $actor
        );
    }
}
