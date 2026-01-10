<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables;

use Illuminate\Contracts\Auth\Authenticatable;
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

    public function execute(int|string $ownerId, array $scopes, ?Authenticatable $actor = null)
    {
        $this->clientRepository->findForUser();
    }
}
