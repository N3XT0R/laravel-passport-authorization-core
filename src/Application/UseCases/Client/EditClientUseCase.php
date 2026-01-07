<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Client\OAuthClientData;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientRevokedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientUpdatedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ClientService;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

/**
 * Use case to edit an existing OAuth client
 */
readonly class EditClientUseCase
{
    public function __construct(
        private OwnerRepository $ownerRepository,
        private ClientService $clientService,
        private GrantService $grantService,
    ) {
    }

    /**
     * Edit an existing OAuth client
     * @param Client $client
     * @param array $data
     * @param Authenticatable|null $actor
     * @return Client
     */
    public function execute(Client $client, array $data, ?Authenticatable $actor = null): Client
    {
        $owner = $data['owner'] ?? null;

        if ($owner instanceof OAuthenticatable === false) {
            $owner = $this->ownerRepository->findByKey($owner);
        }

        $data['owner'] = $owner;

        $dto = new OAuthClientData(
            name: $data['name'],
            redirectUris: $data['redirect_uris'] ?? [],
            provider: $data['provider'] ?? null,
            confidential: $data['confidential'] ?? true,
            options: $data['options'] ?? [],
            revoked: $data['revoked'] ?? false,
            owner: $data['owner'] ?? null,
        );

        $scopes = $data['scopes'] ?? [];

        $client = $this->clientService->updateClient($client, $dto, $actor);

        if ($dto->revoked) {
            OAuthClientRevokedEvent::dispatch($client, $actor);
        }

        OAuthClientUpdatedEvent::dispatch($client, $actor);

        $this->grantService->upsertGrantsForTokenable(
            tokenable: $client,
            scopes: $scopes,
            actor: $actor
        );

        return $client;
    }
}
