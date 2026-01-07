<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Client\OAuthClientData;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

interface OAuthClientFactoryInterface
{
    public function __invoke(
        OAuthClientType $type,
        OAuthClientData $data,
        ?Authenticatable $user = null,
    ): Client;
}
