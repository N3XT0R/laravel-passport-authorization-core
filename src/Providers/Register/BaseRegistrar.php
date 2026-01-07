<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Providers\Register;

use Illuminate\Contracts\Container\Container;
use N3XT0R\LaravelPassportAuthorizationCore\Providers\Register\Concerns\RegistrarInterface;

abstract class BaseRegistrar implements RegistrarInterface
{
    public function __construct(
        protected readonly Container $app
    ) {
    }
}
