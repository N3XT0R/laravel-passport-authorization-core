<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\DTO\Client;

use Laravel\Passport\Client;

readonly class ClientResultDTO
{
    public function __construct(
        public Client $client,
        public ?string $plainSecret = null,
    ) {
    }
}
