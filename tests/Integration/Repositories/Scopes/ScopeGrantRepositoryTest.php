<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Repositories\Scopes;

use Illuminate\Support\Collection;
use Laravel\Passport\Client as PassportClient;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeGrant;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\ScopeGrantRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

final class ScopeGrantRepositoryTest extends DatabaseTestCase
{
    protected ScopeGrantRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->app->make(ScopeGrantRepository::class);
    }

    public function testCreateScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $grant = $this->repository->createScopeGrantForTokenable(
            $client,
            1,
            1
        );

        self::assertSame($client->getMorphClass(), $grant->tokenable_type);
        self::assertSame($client->getKey(), $grant->tokenable_id);
    }

    public function testCreateOrUpdateScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $first = $this->repository->createOrUpdateScopeGrantForTokenable($client, 1, 1);
        $second = $this->repository->createOrUpdateScopeGrantForTokenable($client, 1, 1);

        self::assertSame($first->getKey(), $second->getKey());
        self::assertSame(1, PassportScopeGrant::count());
    }

    public function testDeleteScopeGrantForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteScopeGrantForTokenable(
            $client,
            $grant->resource_id,
            $grant->action_id
        );

        self::assertSame(1, $deleted);
        self::assertSame(0, PassportScopeGrant::count());
    }

    public function testDeleteScopeGrantForTokenableWithContextClient(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->withContextClient($contextClient)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteScopeGrantForTokenable(
            $client,
            $grant->resource_id,
            $grant->action_id,
            $contextClient->getKey()
        );

        self::assertSame(1, $deleted);
        self::assertSame(1, PassportScopeGrant::count());
    }

    public function testTokenableHasScopeGrant(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        self::assertTrue(
            $this->repository->tokenableHasScopeGrant($client, 1, 1)
        );
    }

    public function testTokenableHasScopeGrantWithContextClientMatchesNullContext(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        self::assertTrue(
            $this->repository->tokenableHasScopeGrant(
                $client,
                $grant->resource_id,
                $grant->action_id,
                $contextClient->getKey()
            )
        );
    }

    public function testTokenableHasScopeGrantWithContextClientDoesNotMatchOtherContext(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();
        $otherContextClient = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->withContextClient($otherContextClient)
            ->create();

        self::assertFalse(
            $this->repository->tokenableHasScopeGrant(
                $client,
                $grant->resource_id,
                $grant->action_id,
                $contextClient->getKey()
            )
        );
    }

    public function testGetTokenableGrants(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(2)
            ->withTokenable($client)
            ->create();

        $grants = $this->repository->getTokenableGrants($client);

        self::assertInstanceOf(Collection::class, $grants);
        self::assertCount(2, $grants);
    }

    public function testGetTokenableGrantsWithContextClient(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->withContextClient($contextClient)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->withContextClient(PassportClient::factory()->create())
            ->create();

        $grants = $this->repository->getTokenableGrants($client, $contextClient->getKey());

        self::assertCount(2, $grants);
        self::assertTrue($grants->contains('context_client_id', null));
        self::assertTrue($grants->contains('context_client_id', $contextClient->getKey()));
    }

    public function testDeleteAllGrantsForTokenable(): void
    {
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(3)
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteAllGrantsForTokenable($client);

        self::assertSame(3, $deleted);
        self::assertSame(0, PassportScopeGrant::count());
    }

    public function testDeleteAllGrantsForTokenableWithContextClient(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(2)
            ->withTokenable($client)
            ->withContextClient($contextClient)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $deleted = $this->repository->deleteAllGrantsForTokenable($client, $contextClient->getKey());

        self::assertSame(2, $deleted);
        self::assertSame(1, PassportScopeGrant::count());
    }

    public function testDeleteTokenableOrphans(): void
    {
        PassportClient::flushEventListeners();
        $client = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        $client->delete();

        $this->repository->deleteTokenableOrphans();
        self::assertSame(0, PassportScopeGrant::count());
    }

    public function testTokenableHasGrantUsesRelation(): void
    {
        $client = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        self::assertTrue(
            $this->repository->tokenableHasGrant(
                $client,
                $grant->resource_id,
                $grant->action_id
            )
        );
    }

    public function testTokenableHasGrantWithContextClientMatchesNullContext(): void
    {
        $client = PassportClient::factory()->create();
        $contextClient = PassportClient::factory()->create();

        $grant = PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        self::assertTrue(
            $this->repository->tokenableHasGrant(
                $client,
                $grant->resource_id,
                $grant->action_id,
                $contextClient->getKey()
            )
        );
    }

    public function testGetAllGrants(): void
    {
        PassportScopeGrant::factory()
            ->withTokenable(PassportClient::factory()->create())
            ->create();
        PassportScopeGrant::factory()
            ->withTokenable(PassportClient::factory()->create())
            ->create();

        $grants = $this->repository->getAllGrants();

        self::assertInstanceOf(Collection::class, $grants);
        self::assertCount(2, $grants);
    }

    public function testGetGrantsForTokenable(): void
    {
        $client = PassportClient::factory()->create();
        $otherClient = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->count(2)
            ->withTokenable($client)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($otherClient)
            ->create();

        $grants = $this->repository->getGrantsForTokenable($client);

        self::assertCount(2, $grants);
        self::assertTrue($grants->every(fn (PassportScopeGrant $grant): bool => $grant->tokenable_id === $client->getKey()));
    }

    public function testGetGrantsForTokenableByTypeAndId(): void
    {
        $client = PassportClient::factory()->create();
        $otherClient = PassportClient::factory()->create();

        PassportScopeGrant::factory()
            ->withTokenable($client)
            ->create();

        PassportScopeGrant::factory()
            ->withTokenable($otherClient)
            ->create();

        $grants = $this->repository->getGrantsForTokenableByTypeAndId($client->getMorphClass(), $client->getKey());

        self::assertCount(1, $grants);
        self::assertSame($client->getKey(), $grants->first()?->tokenable_id);
    }

}
