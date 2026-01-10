<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Client;

use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\GetAllClientsUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GetAllClientsUseCaseTest extends DatabaseTestCase
{
    private GetAllClientsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllClientsUseCase::class);
    }

    public function testExecuteReturnsAllClients(): void
    {
        $activeClient = Client::factory()->create([
            'revoked' => false,
        ]);
        $revokedClient = Client::factory()->create([
            'revoked' => true,
        ]);

        $clients = $this->useCase->execute();

        self::assertCount(2, $clients);
        self::assertTrue($clients->contains('id', $activeClient->getKey()));
        self::assertTrue($clients->contains('id', $revokedClient->getKey()));
    }

    public function testExecuteReturnsOnlyActiveClientsWhenRequested(): void
    {
        $activeClient = Client::factory()->create([
            'revoked' => false,
        ]);
        Client::factory()->create([
            'revoked' => true,
        ]);

        $clients = $this->useCase->execute(true);

        self::assertCount(1, $clients);
        self::assertTrue($clients->contains('id', $activeClient->getKey()));
    }
}
