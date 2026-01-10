<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\GetAllGrantsUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GetAllGrantsUseCaseTest extends DatabaseTestCase
{
    private GetAllGrantsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllGrantsUseCase::class);
    }

    public function testExecuteReturnsAllGrants(): void
    {
        $firstGrant = PassportScopeGrant::factory()->create();
        $secondGrant = PassportScopeGrant::factory()->create();

        $grants = $this->useCase->execute();

        self::assertCount(2, $grants);
        self::assertTrue($grants->contains('id', $firstGrant->getKey()));
        self::assertTrue($grants->contains('id', $secondGrant->getKey()));
    }
}
