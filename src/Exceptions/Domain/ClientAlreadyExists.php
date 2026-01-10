<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain;

use DomainException;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Contracts\DomainExceptionInterface;

class ClientAlreadyExists extends DomainException implements DomainExceptionInterface
{
    public function __construct(string $name)
    {
        parent::__construct(
            sprintf('OAuth client with name "%s" already exists.', $name)
        );
    }
}
