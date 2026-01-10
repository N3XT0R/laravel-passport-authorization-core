<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client;

use Illuminate\Support\Collection;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;

/**
 * Use case to get OAuth clients.
 */
class GetAllClientsUseCase
{
    public function __construct(
        protected ClientRepository $clientRepository
    ) {
    }

    /**
     * @param bool $onlyActive
     * @return Collection<Client>
     */
    public function execute(bool $onlyActive = false): Collection
    {
        if ($onlyActive) {
            return $this->clientRepository->getActive();
        }
        return $this->clientRepository->all();
    }
}
