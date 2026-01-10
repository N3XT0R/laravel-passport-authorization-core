<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Application\UseCases\Tokenables;

use App\Models\PassportScopeGrantUser;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Tokenable\UpsertGrantsForTokenableUseCase;
use N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable\TokenableGrantUpsertedEvent;
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

        Event::fake();
        config(['passport-authorization-core.owner_model' => PassportScopeGrantUser::class]);

        $this->useCase = $this->app->make(UpsertGrantsForTokenableUseCase::class);
    }

    public function testExecuteUpsertsGrantsAndDispatchesEvent(): void
    {
        $actor = User::factory()->create();
        $owner = PassportScopeGrantUser::factory()->create();
        $contextClient = Client::factory()->create();

        $resource = PassportScopeResource::factory()->create(['name' => 'projects']);
        $readAction = PassportScopeAction::factory()->create(['name' => 'read']);
        $updateAction = PassportScopeAction::factory()->create(['name' => 'update']);

        PassportScopeGrant::factory()
            ->withTokenable($owner)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $readAction->getKey(),
                'context_client_id' => $contextClient->getKey(),
            ]);

        $this->useCase->execute(
            $owner->getKey(),
            $contextClient->getKey(),
            ['projects:update'],
            $actor
        );

        $this->assertDatabaseMissing('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $readAction->getKey(),
        ]);

        $this->assertDatabaseHas('passport_scope_grants', [
            'tokenable_type' => $owner->getMorphClass(),
            'tokenable_id' => $owner->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $updateAction->getKey(),
        ]);

        Event::assertDispatched(TokenableGrantUpsertedEvent::class, function (TokenableGrantUpsertedEvent $event) use (
            $owner,
            $contextClient,
            $actor
        ): bool {
            return $event->model->is($owner)
                && $event->contextClient?->is($contextClient)
                && $event->actor?->is($actor)
                && $event->scopes === ['projects:update'];
        });
    }
}
