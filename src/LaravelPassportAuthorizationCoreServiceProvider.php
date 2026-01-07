<?php

namespace N3XT0R\LaravelPassportAuthorizationCore;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPassportAuthorizationCoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-passport-authorization-core')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_passport_authorization_core_table');
        //->hasCommand(LaravelPassportAuthorizationCoreCommand::class);
    }
}
