<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth\Strategy\ClientCredentialsClientStrategy;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class ClientCredentialsClientStrategyTest extends DatabaseTestCase
{
    public function testCreateClientCredentialsClient(): void
    {
        $strategy = $this->app->make(ClientCredentialsClientStrategy::class);

        $client = $strategy->create('Client Credentials App');

        self::assertTrue($strategy->supports(OAuthClientType::CLIENT_CREDENTIALS));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Client Credentials App', $client->name);
        self::assertTrue(in_array('client_credentials', $client->grant_types, true));
        self::assertNotNull($client->plainSecret);
        self::assertEmpty($client->redirect_uris);
    }
}
