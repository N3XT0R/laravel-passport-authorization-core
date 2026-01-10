<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\DTO\Context;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;

final readonly class GrantableTokenableContext
{
    public function __construct(
        public HasPassportScopeGrantsInterface&Model $tokenable,
        public Client $contextClient,
    ) {
    }
}
