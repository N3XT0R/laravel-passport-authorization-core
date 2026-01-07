<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\OwnerRepository;

readonly class GetAllOwnersUseCase
{
    /**
     * Get All Owners
     * @return Collection
     */
    public function execute(): Collection
    {
        return app(OwnerRepository::class)->all();
    }
}
