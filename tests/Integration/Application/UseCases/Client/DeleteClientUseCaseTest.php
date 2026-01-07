<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Client;

use Illuminate\Support\Facades\Event;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Client\DeleteClientUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Clients\OauthClientDeletedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class DeleteClientUseCaseTest extends DatabaseTestCase
{
    private DeleteClientUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(DeleteClientUseCase::class);
    }

    public function testExecuteDeletesClient(): void
    {
        $client = Client::factory()->create([
            'name' => 'Client to delete',
        ]);

        $result = $this->useCase->execute($client);

        self::assertTrue($result);
        $this->assertDatabaseMissing($client->getTable(), [
            'id' => $client->getKey(),
            'name' => 'Client to delete',
        ]);

        Event::assertDispatched(OauthClientDeletedEvent::class);
    }
}
