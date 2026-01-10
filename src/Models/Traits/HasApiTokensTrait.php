<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Models\Traits;

use Laravel\Passport\AccessToken;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens as BaseHasApiTokens;
use N3XT0R\LaravelPassportAuthorizationCore\Repositories\ClientRepository;
use N3XT0R\LaravelPassportAuthorizationCore\Services\GrantService;

trait HasApiTokensTrait
{
    use BaseHasApiTokens {
        BaseHasApiTokens::tokenCan as parentTokenCan;
    }

    /**
     * Determine if the token has a given scope with additional scope grants check.
     * @param string $scope
     * @return bool
     */
    public function tokenCan(string $scope): bool
    {
        $result = $this->parentTokenCan($scope);
        if (true === $result) {
            $result = app(GrantService::class)->tokenableHasGrantToScope(
                $this,
                $scope,
                $this->getClient()
            );
        }


        return $result;
    }

    private function getClient(): ?Client
    {
        $client = null;
        $currentToken = $this->currentAccessToken();
        if ($currentToken instanceof AccessToken) {
            $clientId = $currentToken->toArray()['oauth_client_id'] ?? null;
            if ($clientId) {
                $client = app(ClientRepository::class)->find($clientId);
            }
        }

        return $client;
    }
}
