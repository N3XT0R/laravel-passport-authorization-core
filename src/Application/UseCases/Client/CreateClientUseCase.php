<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Contracts\OAuthenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Client\ClientResultDTO;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Client\OAuthClientData;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OAuthClientCreatedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ClientService;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

/**
 * Use case to create a new OAuth client
 */
readonly class CreateClientUseCase
{
    public function __construct(
        protected OwnerRepository $ownerRepository,
        protected ClientService $clientService,
        protected GrantService $grantService,
    ) {
    }

    /**
     * Create a new OAuth client
     * @param array $data
     * @param Authenticatable|null $actor
     * @return ClientResultDTO
     * @throws \Throwable
     */
    public function execute(array $data, ?Authenticatable $actor = null): ClientResultDTO
    {
        $owner = $data['owner'] ?? null;

        if ($owner instanceof OAuthenticatable === false) {
            $owner = $this->ownerRepository->findByKey($owner);
        }

        $data['owner'] = $owner;
        $scopes = $data['scopes'] ?? [];


        $client = $this->clientService->createClientForUser(
            type: OAuthClientType::from($data['grant_type']),
            data: OAuthClientData::fromArray($data),
            actor: $actor
        );

        $this->grantService->giveGrantsToTokenable(
            tokenable: $client,
            scopes: $scopes,
            actor: $actor,
            contextClient: $client
        );

        OAuthClientCreatedEvent::dispatch($client, $actor);
        return new ClientResultDTO($client, $client->plainSecret);
    }
}
