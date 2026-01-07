<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Commands;

use Illuminate\Console\Command;
use N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Cleanup\ClearCacheUseCase;

class ClearCacheCommand extends Command
{
    protected $signature = 'laravel-passport-authorization-core:cleanup-cache';

    protected $description = 'Clears the cache, including scope registry cache.';

    public function handle(ClearCacheUseCase $clearCacheUseCase): int
    {
        try {
            $clearCacheUseCase->execute();
        } catch (\Throwable $e) {
            $this->error('An error occurred while clearing cache: ' . $e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
