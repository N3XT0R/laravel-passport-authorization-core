<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Clients;

use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\DomainExceptionInterface;

class ClientNotFoundException extends \DomainException implements DomainExceptionInterface
{
    public function __construct(string|int $clientIdentifier)
    {
        parent::__construct("Client with identifier '{$clientIdentifier}' not found.");
    }
}
