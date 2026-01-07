<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth\Strategy;

use Laravel\Passport\ClientRepository;

abstract readonly class BaseStrategy implements OAuthClientCreationStrategyInterface
{
    public function __construct(
        protected ClientRepository $clients
    ) {
    }
}
