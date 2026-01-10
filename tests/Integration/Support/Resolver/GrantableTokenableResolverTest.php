<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Support\Resolver;

use App\Models\PassportScopeGrantUser;
use App\Models\User;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\ActiveClientNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Owners\OwnerNotExistsException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Tokenables\IsNotGrantableException;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Support\Resolver\GrantableTokenableResolver;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class GrantableTokenableResolverTest extends DatabaseTestCase
{
    private GrantableTokenableResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = $this->app->make(GrantableTokenableResolver::class);
    }

    public function testResolvesGrantableTokenableContext(): void
    {
        config(['passport-authorization-core.owner_model' => PassportScopeGrantUser::class]);

        $owner = PassportScopeGrantUser::factory()->create();
        $client = Client::factory()->create();

        $context = $this->resolver->resolve($owner->getKey(), $client->getKey());

        self::assertSame($owner->getKey(), $context->tokenable->getKey());
        self::assertSame($client->getKey(), $context->contextClient->getKey());
    }

    public function testThrowsWhenActiveClientDoesNotExist(): void
    {
        $this->expectException(ActiveClientNotExistsException::class);

        $this->resolver->resolve(1, 999);
    }

    public function testThrowsWhenOwnerDoesNotExist(): void
    {
        config(['passport-authorization-core.owner_model' => PassportScopeGrantUser::class]);

        $client = Client::factory()->create();

        $this->expectException(OwnerNotExistsException::class);

        $this->resolver->resolve(999, $client->getKey());
    }

    public function testThrowsWhenOwnerIsNotGrantable(): void
    {
        config(['passport-authorization-core.owner_model' => User::class]);

        $owner = User::factory()->create();
        $client = Client::factory()->create();

        $this->expectException(IsNotGrantableException::class);

        $this->resolver->resolve($owner->getKey(), $client->getKey());
    }
}
