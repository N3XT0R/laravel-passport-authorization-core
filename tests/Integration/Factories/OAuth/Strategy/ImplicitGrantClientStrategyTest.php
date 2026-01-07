<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Factories\OAuth\Strategy;

use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth\Strategy\ImplicitGrantClientStrategy;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class ImplicitGrantClientStrategyTest extends DatabaseTestCase
{
    public function testCreateImplicitGrantClient(): void
    {
        $strategy = $this->app->make(ImplicitGrantClientStrategy::class);

        $client = $strategy->create('Implicit Client', ['https://example.com/implicit']);

        self::assertTrue($strategy->supports(OAuthClientType::IMPLICIT));
        self::assertInstanceOf(Client::class, $client);
        self::assertSame('Implicit Client', $client->name);
        self::assertSame(['https://example.com/implicit'], $client->redirect_uris);
        self::assertTrue(in_array('implicit', $client->grant_types, true));
        self::assertNull($client->secret);
    }
}
