<?php

namespace N3XT0R\LaravelPassportAuthorizationCore;

use Illuminate\Support\Facades\Artisan;
use N3XT0R\LaravelPassportAuthorizationCore\Commands\CleanupDatabaseCommand;
use N3XT0R\LaravelPassportAuthorizationCore\Commands\ClearCacheCommand;
use N3XT0R\LaravelPassportAuthorizationCore\Database\Seeders\DatabaseSeeder;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelPassportAuthorizationCoreServiceProvider extends PackageServiceProvider
{

    /**
     * @var array|class-string[]
     */
    protected array $registrars = [
        Providers\Register\RepositoryRegistrar::class,
        Providers\Register\OAuthStrategyRegistrar::class,
    ];

    /**
     * @var array|class-string[]
     */
    protected array $booter = [
        Providers\Boot\ScopeBooter::class,
        Providers\Boot\PassportModelsBooter::class,
        Providers\Boot\OAuthClientFactoryBooter::class,
    ];


    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-passport-authorization-core')
            ->hasConfigFile()
            ->hasMigrations($this->getMigrations())
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->endWith(function (InstallCommand $command) {
                        if (!$command->confirm(
                            question: 'Do you want to seed default Passport scope resources and actions?',
                            default: false
                        )) {
                            return;
                        }

                        $command->comment('Seeding default Passport scope resources and actions...');
                        Artisan::call('db:seed', [
                            '--class' => DatabaseSeeder::class,
                        ]);
                    })
                    ->askToStarRepoOnGitHub('n3xt0r/laravel-passport-authorization-core');
            });
    }

    private function getCommands(): array
    {
        return [
            CleanupDatabaseCommand::class,
            ClearCacheCommand::class,
        ];
    }

    private function getMigrations(): array
    {
        return [
            'create_passport_scope_resources_table',
            'create_passport_scope_actions_table',
            'create_passport_scope_grant_table',
        ];
    }
}
