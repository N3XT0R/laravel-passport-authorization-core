<?php

namespace N3XT0R\LaravelPassportAuthorizationCore;

use N3XT0R\LaravelPassportAuthorizationCore\Commands\LaravelPassportAuthorizationCoreCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPassportAuthorizationCoreServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-passport-authorization-core')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_passport_authorization_core_table');
        //->hasCommand(LaravelPassportAuthorizationCoreCommand::class);
    }
}
