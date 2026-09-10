<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Migrations;

use Illuminate\Support\Facades\Schema;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\DatabaseTestCase;

/**
 * Guards the column that binds a scope grant to the client it was issued for.
 *
 * Passport 13 gives clients uuid primary keys. While context_client_id was a
 * bigint, MySQL/MariaDB truncated the uuid on write and aborted every OAuth
 * client creation. SQLite does not enforce column types, so the only portable
 * way to catch that here is to compare the column against the key it
 * references.
 */
class PassportScopeGrantContextClientIdTypeTest extends DatabaseTestCase
{
    public function testContextClientIdMatchesTheClientPrimaryKeyType(): void
    {
        $this->assertSame(
            Schema::getColumnType('oauth_clients', 'id'),
            Schema::getColumnType('passport_scope_grants', 'context_client_id'),
            'context_client_id must have the same column type as oauth_clients.id, '
            . 'otherwise MySQL/MariaDB truncates the client uuid on write.',
        );
    }
}
