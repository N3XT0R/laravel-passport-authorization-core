<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\Tokenables;

use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\Domain\DomainExceptionInterface;

class IsNotGrantableException extends \DomainException implements DomainExceptionInterface
{
    public function __construct(string $tokenableType, int|string $tokenableId)
    {
        parent::__construct(
            sprintf('Tokenable of type "%s" with ID "%s" is not grantable.', $tokenableType, $tokenableId)
        );
    }
}
