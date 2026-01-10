<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use App\Models\PassportScopeGrantUser;
use Illuminate\Support\Facades\Event;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenables\UpsertGrantsForTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantUpsertedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class UpsertGrantsForTokenableUseCaseTest extends DatabaseTestCase
{
    private UpsertGrantsForTokenableUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'passport-authorization-core.owner_model' => PassportScopeGrantUser::class,
        ]);

        $this->useCase = $this->app->make(UpsertGrantsForTokenableUseCase::class);
    }

    public function testExecuteUpsertsGrantsAndDispatchesEvent(): void
    {
        $owner = PassportScopeGrantUser::factory()->create();
        $client = Client::factory()->create();

        $keepResource = PassportScopeResource::factory()->create(['name' => 'articles']);
        $keepAction = PassportScopeAction::factory()->withResource($keepResource)->create(['name' => 'read']);

        $removeResource = PassportScopeResource::factory()->create(['name' => 'videos']);
        $removeAction = PassportScopeAction::factory()->withResource($removeResource)->create(['name' => 'write']);

        $newResource = PassportScopeResource::factory()->create(['name' => 'comments']);
        $newAction = PassportScopeAction::factory()->withResource($newResource)->create(['name' => 'update']);

        PassportScopeGrant::factory()
            ->withTokenable($owner)
            ->create([
                'resource_id' => $keepResource->getKey(),
                'action_id' => $keepAction->getKey(),
                'context_client_id' => $client->getKey(),
            ]);

        PassportScopeGrant::factory()
            ->withTokenable($owner)
            ->create([
                'resource_id' => $removeResource->getKey(),
                'action_id' => $removeAction->getKey(),
                'context_client_id' => $client->getKey(),
            ]);

        $scopesToUpsert = [
            sprintf('%s:%s', $keepResource->name, $keepAction->name),
            sprintf('%s:%s', $newResource->name, $newAction->name),
        ];

        $dispatchedEvent = null;
        Event::listen(TokenableGrantUpsertedEvent::class, function (TokenableGrantUpsertedEvent $event) use (&$dispatchedEvent): void {
            $dispatchedEvent = $event;
        });

        $this->useCase->execute($owner->getKey(), $client->getKey(), $scopesToUpsert);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'context_client_id' => $client->getKey(),
            'resource_id' => $keepResource->getKey(),
            'action_id' => $keepAction->getKey(),
        ]);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'context_client_id' => $client->getKey(),
            'resource_id' => $newResource->getKey(),
            'action_id' => $newAction->getKey(),
        ]);

        $this->assertDatabaseMissing('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'context_client_id' => $client->getKey(),
            'resource_id' => $removeResource->getKey(),
            'action_id' => $removeAction->getKey(),
        ]);

        self::assertNotNull($dispatchedEvent);
        self::assertTrue($dispatchedEvent->model->is($owner));
        self::assertSame($scopesToUpsert, $dispatchedEvent->scopes);
        self::assertTrue($dispatchedEvent->contextClient?->is($client));
    }
}
