<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Cleanup;

use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Cleanup\ClearCacheUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class ClearCacheUseCaseTest extends DatabaseTestCase
{
    private ClearCacheUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(ClearCacheUseCase::class);
    }

    public function testExecuteKeepsScopeDataIntact(): void
    {
        $resource = PassportScopeResource::factory()->create();
        PassportScopeAction::factory()->withResource($resource)->create();

        $this->useCase->execute();

        self::assertSame(1, PassportScopeResource::count());
        self::assertSame(1, PassportScopeAction::count());
    }
}
