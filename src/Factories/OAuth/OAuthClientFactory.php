<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Passport\Client;
use N3XT0R\LaravelPassportAuthorizationCore\DTO\Client\OAuthClientData;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;
use N3XT0R\LaravelPassportAuthorizationCore\Exceptions\UnsupportedOAuthClientTypeException;
use N3XT0R\LaravelPassportAuthorizationCore\Factories\OAuth\Strategy\OAuthClientCreationStrategyInterface;

readonly class OAuthClientFactory implements OAuthClientFactoryInterface
{
    /** @param iterable<OAuthClientCreationStrategyInterface> $strategies */
    public function __construct(
        protected iterable $strategies
    ) {
    }

    public function __invoke(
        OAuthClientType $type,
        OAuthClientData $data,
        ?Authenticatable $user = null,
    ): Client {
        return $this->createUsingStrategy($type, $data, $user);
    }

    private function createUsingStrategy(
        OAuthClientType $type,
        OAuthClientData $data,
        ?Authenticatable $user,
    ): Client {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($type)) {
                return $strategy->create(
                    name: $data->name,
                    redirectUris: $data->redirectUris,
                    provider: $data->provider,
                    confidential: $data->confidential,
                    user: $user,
                    options: $data->options
                );
            }
        }

        throw UnsupportedOAuthClientTypeException::forType($type);
    }
}
