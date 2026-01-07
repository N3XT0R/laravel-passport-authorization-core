<?php

namespace N3XT0R\LaravelPassportAuthorizationCore\Commands;

use Illuminate\Console\Command;

class LaravelPassportAuthorizationCoreCommand extends Command
{
    public $signature = 'laravel-passport-authorization-core';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
