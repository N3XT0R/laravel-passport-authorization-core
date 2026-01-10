<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Resources;

use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources\GetAllResourcesUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GetAllResourcesUseCaseTest extends DatabaseTestCase
{
    private GetAllResourcesUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllResourcesUseCase::class);
    }

    public function testExecuteReturnsAllResources(): void
    {
        $firstResource = PassportScopeResource::factory()->create([
            'name' => 'posts',
        ]);
        $secondResource = PassportScopeResource::factory()->create([
            'name' => 'comments',
        ]);

        $resources = $this->useCase->execute();

        self::assertCount(2, $resources);
        self::assertTrue($resources->contains('id', $firstResource->getKey()));
        self::assertTrue($resources->contains('id', $secondResource->getKey()));
    }
}
