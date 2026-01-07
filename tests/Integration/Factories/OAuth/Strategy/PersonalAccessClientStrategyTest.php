<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth\Strategy\PersonalAccessClientStrategy;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class PersonalAccessClientStrategyTest extends DatabaseTestCase
{
    public function testCreatePersonalAccessClient(): void
    {
        $strategy = $this->app->make(PersonalAccessClientStrategy::class);

        $client = $strategy->create('Personal Client', [], 'users');

        self::assertTrue($strategy->supports(OAuthClientType::PERSONAL_ACCESS));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Personal Client', $client->name);
        self::assertSame('users', $client->provider);
        self::assertTrue(in_array('personal_access', $client->grant_types, true));
        self::assertNotNull($client->plainSecret);
    }
}
