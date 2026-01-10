<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Actions;

use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions\GetAllActionsUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GetAllActionsUseCaseTest extends DatabaseTestCase
{
    private GetAllActionsUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCase = $this->app->make(GetAllActionsUseCase::class);
    }

    public function testExecuteReturnsAllActions(): void
    {
        $resource = PassportScopeResource::factory()->create();

        $firstAction = PassportScopeAction::factory()->create([
            'name' => 'index',
        ]);
        $secondAction = PassportScopeAction::factory()->withResource($resource)->create([
            'name' => 'show',
        ]);

        $actions = $this->useCase->execute();

        self::assertCount(2, $actions);
        self::assertTrue($actions->contains('id', $firstAction->getKey()));
        self::assertTrue($actions->contains('id', $secondAction->getKey()));
    }
}
