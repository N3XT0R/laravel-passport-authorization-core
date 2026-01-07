<?php

namespace N3XT0R\LaravelPassportAuthorizationCore\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \N3XT0R\LaravelPassportAuthorizationCore\LaravelPassportAuthorizationCore
 */
class LaravelPassportAuthorizationCore extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \N3XT0R\LaravelPassportAuthorizationCore\LaravelPassportAuthorizationCore::class;
    }
}
