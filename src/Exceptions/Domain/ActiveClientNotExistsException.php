<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain;

use DomainException;

class ActiveClientNotExistsException extends DomainException
{
    public function __construct(string|int $id)
    {
        parent::__construct(
            sprintf('No active OAuth client with id "%s" exists.', $id)
        );
    }
}
