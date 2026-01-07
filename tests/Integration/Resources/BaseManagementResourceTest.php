<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Tests\Integration\Resources;

use N3XT0R\LaravelPassportAuthorizationCore\Resources\BaseManagementResource;
use N3XT0R\LaravelPassportAuthorizationCore\Tests\TestCase;

class BaseManagementResourceTest extends TestCase
{

    public function testGetNavigationGroupReturnsNotNull(): void
    {
        $result = BaseManagementResource::getNavigationGroup();
        self::assertNotNull($result);
    }

    public function testGetNavigationIconReturnsNotNull(): void
    {
        $result = BaseManagementResource::getNavigationIcon();
        self::assertNotNull($result);
    }
}
