<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Resources;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ResourceRepositoryContract;

/**
 * Use case to get all OAuth Passport Scope Resources.
 */
class GetAllResourcesUseCase
{
    public function execute(bool $withoutCache = false): Collection
    {
        return app(ResourceRepositoryContract::class, ['cache' => !$withoutCache])->all();
    }
}
