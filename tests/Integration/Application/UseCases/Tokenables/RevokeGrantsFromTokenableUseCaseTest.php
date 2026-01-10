<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use App\Models\PassportScopeGrantUser;
use Illuminate\Support\Facades\Event;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\RevokeGrantsFromTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantsRevokedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class RevokeGrantsFromTokenableUseCaseTest extends DatabaseTestCase
{
    private RevokeGrantsFromTokenableUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'passport-authorization-core.owner_model' => PassportScopeGrantUser::class,
        ]);

        $this->useCase = $this->app->make(RevokeGrantsFromTokenableUseCase::class);
    }

    public function testExecuteRevokesGrantsAndDispatchesEvent(): void
    {
        $owner = PassportScopeGrantUser::factory()->create();
        $client = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'articles']);
        $action = PassportScopeAction::factory()->withResource($resource)->create(['name' => 'read']);

        $secondaryResource = PassportScopeResource::factory()->create(['name' => 'videos']);
        $secondaryAction = PassportScopeAction::factory()->withResource($secondaryResource)->create(['name' => 'write']);

        PassportScopeGrant::factory()
            ->withTokenable($owner)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
                'context_client_id' => $client->getKey(),
            ]);

        PassportScopeGrant::factory()
            ->withTokenable($owner)
            ->create([
                'resource_id' => $secondaryResource->getKey(),
                'action_id' => $secondaryAction->getKey(),
                'context_client_id' => $client->getKey(),
            ]);

        $scopesToRevoke = [
            sprintf('%s:%s', $resource->name, $action->name),
        ];

        $dispatchedEvent = null;
        Event::listen(TokenableGrantsRevokedEvent::class, function (TokenableGrantsRevokedEvent $event) use (&$dispatchedEvent): void {
            $dispatchedEvent = $event;
        });

        $this->useCase->execute($owner->getKey(), $client->getKey(), $scopesToRevoke);

        $this->assertDatabaseMissing('passport_scope_grants', [
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
        self::assertSame($scopesToRevoke, $dispatchedEvent->scopes);
        self::assertTrue($dispatchedEvent->contextClient?->is($client));
    }
}
