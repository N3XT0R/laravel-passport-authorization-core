<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Clients\ClientNotFoundException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Owners\OwnerNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ClientService;

/**
 * Use case to save ownership relation between client and owner
 */
readonly class SaveOwnershipRelationUseCase
{
    public function __construct(
        protected ClientRepository $clientRepository,
        protected OwnerRepository $ownerRepository,
        protected ClientService $clientService,
        protected ScopeGrantRepository $scopeGrantRepository,
    ) {
    }

    /**
     * Save ownership relation between client and owner
     * @param Client|string|int $clientId
     * @param int $ownerId
     * @param Authenticatable|null $actor
     * @return void
     * @throws \Throwable
     */
    public function execute(Client|string|int $clientId, int $ownerId, ?Authenticatable $actor = null): void
    {
        $client = null;
        if ($clientId instanceof Client === false) {
            $client = $this->clientRepository->find($clientId);
        }

        if ($client === null) {
            throw new ClientNotFoundException($clientId);
        }

        $owner = $this->ownerRepository->findByKey($ownerId);
        if ($owner === null) {
            $type = $this->ownerRepository->getOwnerModelClass();
            throw new OwnerNotExistsException($type, $ownerId);
        }

        $currentOwner = $client->owner;
        if ($currentOwner) {
            $this->scopeGrantRepository->deleteAllGrantsForTokenable($currentOwner);
        }

        $this->clientService->changeOwnerOfClient($client, $owner, $actor);
    }
}
