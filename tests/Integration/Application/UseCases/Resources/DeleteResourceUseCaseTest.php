<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Resources;

use Illuminate\Support\Facades\Event;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\DeleteResourceUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeResource\ResourceDeletedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class DeleteResourceUseCaseTest extends DatabaseTestCase
{
    private DeleteResourceUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        $this->useCase = $this->app->make(DeleteResourceUseCase::class);
    }

    public function testExecuteDeletesResource(): void
    {
        $resource = PassportScopeResource::factory()->create([
            'name' => 'temporary-resource',
        ]);

        $result = $this->useCase->execute($resource);

        self::assertTrue($result);
        $this->assertDatabaseMissing($resource->getTable(), [
            'id' => $resource->getKey(),
            'name' => 'temporary-resource',
        ]);

        Event::assertDispatched(ResourceDeletedEvent::class);
    }
}
