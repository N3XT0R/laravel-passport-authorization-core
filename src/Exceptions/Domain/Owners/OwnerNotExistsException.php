<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Owners;

use DomainException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\DomainExceptionInterface;

class OwnerNotExistsException extends DomainException implements DomainExceptionInterface
{
    public function __construct(string $ownerType, int|string $ownerId)
    {
        parent::__construct(
            sprintf('Owner of type "%s" with ID "%s" does not exist.', $ownerType, $ownerId)
        );
    }
}
