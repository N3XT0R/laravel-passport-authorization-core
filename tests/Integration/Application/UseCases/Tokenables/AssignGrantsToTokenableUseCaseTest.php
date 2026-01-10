<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use App\Models\PassportScopeGrantUser;
use Illuminate\Support\Facades\Event;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\AssignGrantsToTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsAssignedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class AssignGrantsToTokenableUseCaseTest extends DatabaseTestCase
{
    private AssignGrantsToTokenableUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'passport-authorization-core.owner_model' => PassportScopeGrantUser::class,
        ]);

        $this->useCase = $this->app->make(AssignGrantsToTokenableUseCase::class);
    }

    public function testExecuteAssignsGrantsAndDispatchesEvent(): void
    {
        $owner = PassportScopeGrantUser::factory()->create();
        $client = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'articles']);
        $action = PassportScopeAction::factory()->withResource($resource)->create(['name' => 'read']);

        $secondaryResource = PassportScopeResource::factory()->create(['name' => 'videos']);
        $secondaryAction = PassportScopeAction::factory()->withResource($secondaryResource)->create(['name' => 'write']);

        $scopes = [
            sprintf('%s:%s', $resource->name, $action->name),
            sprintf('%s:%s', $secondaryResource->name, $secondaryAction->name),
        ];

        $dispatchedEvent = null;
        Event::listen(TokenableGrantsAssignedEvent::class, function (TokenableGrantsAssignedEvent $event) use (&$dispatchedEvent): void {
            $dispatchedEvent = $event;
        });

        $this->useCase->execute($owner->getKey(), $client->getKey(), $scopes);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'context_client_id' => $client->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
        ]);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'context_client_id' => $client->getKey(),
            'resource_id' => $secondaryResource->getKey(),
            'action_id' => $secondaryAction->getKey(),
        ]);

        self::assertNotNull($dispatchedEvent);
        self::assertTrue($dispatchedEvent->model->is($owner));
        self::assertSame($scopes, $dispatchedEvent->scopes);
        self::assertTrue($dispatchedEvent->contextClient?->is($client));
    }
}
