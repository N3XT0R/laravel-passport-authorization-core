<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\Scopes\Contracts\ActionRepositoryContract;

/**
 * Use case to get all OAuth Passport Scope Actions.
 */
class GetAllActionsUseCase
{
    public function execute(bool $withoutCache = false): Collection
    {
        return app(ActionRepositoryContract::class, ['cache' => !$withoutCache])->all();
    }
}
