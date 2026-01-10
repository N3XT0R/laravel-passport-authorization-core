<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use App\Models\PassportScopeGrantUser;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\GetGrantsForTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GetGrantsForTokenableUseCaseTest extends DatabaseTestCase
{
    private GetGrantsForTokenableUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['passport-authorization-core.owner_model' => PassportScopeGrantUser::class]);

        $this->useCase = $this->app->make(GetGrantsForTokenableUseCase::class);
    }

    public function testExecuteReturnsGrantsForTokenable(): void
    {
        $owner = PassportScopeGrantUser::factory()->create();
        $otherOwner = PassportScopeGrantUser::factory()->create();

        $firstGrant = PassportScopeGrant::factory()->withTokenable($owner)->create();
        $secondGrant = PassportScopeGrant::factory()->withTokenable($owner)->create();
        PassportScopeGrant::factory()->withTokenable($otherOwner)->create();

        $grants = $this->useCase->execute($owner);

        self::assertCount(2, $grants);
        self::assertTrue($grants->contains('id', $firstGrant->getKey()));
        self::assertTrue($grants->contains('id', $secondGrant->getKey()));
        self::assertFalse($grants->contains('tokenable_id', $otherOwner->getKey()));
    }
}
