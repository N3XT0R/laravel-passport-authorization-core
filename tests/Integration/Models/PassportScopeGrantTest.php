<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Models;

use App\Models\User;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

class PassportScopeGrantTest extends DatabaseTestCase
{
    public function testRelationsAndScopeString(): void
    {
        $tokenable = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $resource = PassportScopeResource::create([
            'name' => 'profile',
            'description' => 'Profile resource',
        ]);

        $action = PassportScopeAction::create([
            'name' => 'update',
            'description' => 'Update profile',
            'resource_id' => $resource->getKey(),
        ]);

        $client = Client::factory()->create();

        $grant = PassportScopeGrant::create([
            'tokenable_type' => $tokenable::class,
            'tokenable_id' => (string)$tokenable->getKey(),
            'resource_id' => $resource->getKey(),
            'action_id' => $action->getKey(),
            'context_client_id' => $client->getKey(),
        ]);


        $grant->refresh();

        $this->assertTrue($grant->tokenable->is($tokenable));
        $this->assertTrue($grant->resource->is($resource));
        $this->assertTrue($grant->action->is($action));
        $this->assertTrue($grant->contextClient->is($client));
        $this->assertSame('profile:update', $grant->toScopeString());
    }
}
