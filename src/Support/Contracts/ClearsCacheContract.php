<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Support\Contracts;

interface ClearsCacheContract
{
    public function clearCache(): void;
}
