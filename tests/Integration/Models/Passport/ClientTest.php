<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Models\Passport;

use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class ClientTest extends DatabaseTestCase
{
    public function testHasScopeUsesParentBehaviorWhenDatabaseScopesDisabled(): void
    {
        config(['passport-authorization-core.use_database_scopes' => false]);

        $client = Client::factory()->create();

        $client->setAttribute('scopes', ['profile:read']);

        self::assertTrue($client->hasScope('profile:read'));
        self::assertFalse($client->hasScope('profile:write'));
    }

    public function testHasScopeUsesDatabaseBackedScopeGrantsWhenEnabled(): void
    {
        config(['passport-authorization-core.use_database_scopes' => true]);

        $resource = PassportScopeResource::factory()->create([
            'name' => 'profile',
        ]);

        $action = PassportScopeAction::factory()
            ->withResource($resource)
            ->create([
                'name' => 'read',
            ]);

        $client = Client::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create([
                'resource_id' => $resource->getKey(),
                'action_id' => $action->getKey(),
                'context_client_id' => $client->getKey(),
            ]);

        self::assertTrue($client->hasScope('profile:read'));
        self::assertFalse($client->hasScope('profile:write'));
    }

    public function testContextClientRelation(): void
    {
        $client = Client::factory()->create();
        $contextClient = Client::factory()->create();
        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->withContextClient($contextClient)
            ->create();

        $collection = $contextClient->contextScopeGrants;

        $this->assertCount(1, $collection);
        $this->assertTrue($grant->is($collection->first()));
    }
}
