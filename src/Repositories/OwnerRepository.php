<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Contracts\OAuthenticatable;

readonly class OwnerRepository
{

    public function __construct(protected ConfigRepository $configRepository)
    {
    }

    /**
     * Get the owner model class.
     * @return class-string<Model&OAuthenticatable>
     */
    public function getOwnerModelClass(): string
    {
        return $this->configRepository->getOwnerModel();
    }

    /**
     * Get the base query for the owner model.
     * @return Builder
     */
    private function getBaseQuery(): Builder
    {
        $modelClass = $this->getOwnerModelClass();
        return $modelClass::query();
    }

    /**
     * Find an owner by its primary key.
     * @param $key
     * @return (OAuthenticatable&Model)|null
     */
    public function findByKey($key): (Model&OAuthenticatable)|null
    {
        /**
         * @var (OAuthenticatable&Model)|null $owner
         */
        $owner = $this->getBaseQuery()->find($key);

        return $owner;
    }

    /**
     * Get all owners.
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getBaseQuery()->get();
    }
}
