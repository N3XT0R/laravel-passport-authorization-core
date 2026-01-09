<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Repositories;

use Illuminate\Contracts\Config\Repository;
use N3XT0R\LaravelPassportAuthorizationCore\Enum\OAuthClientType;

/**
 * Configuration Repository for Filament Passport UI
 */
readonly class ConfigRepository
{
    private const string CONFIG_ROOT = 'passport-authorization-core.';


    public function __construct(protected Repository $config)
    {
    }

    /**
     * Get active OAuth client types based on configuration
     * @return OAuthClientType[]
     */
    public function getAllowedGrantTypes(): array
    {
        $values = $this->config->get(
            self::CONFIG_ROOT . 'oauth.allowed_grant_types',
            []
        );

        $types = [];

        foreach ($values as $value) {
            $types[] = OAuthClientType::from($value);
        }

        return $types;
    }

    /**
     * Get the owner model class name for OAuth clients
     * @return string
     */
    public function getOwnerModel(): string
    {
        return (string)$this->config->get(self::CONFIG_ROOT . 'owner_model', '\\App\\Models\\User');
    }

    /**
     * Get the attribute used as label for owner relationships for OAuth clients
     * @return string
     */
    public function getOwnerLabelAttribute(): string
    {
        return (string)$this->config->get(self::CONFIG_ROOT . 'owner_label_attribute', 'name');
    }

    /**
     * Check if database scopes are used for Passport
     * @return bool
     */
    public function isUsingDatabaseScopes(): bool
    {
        return (bool)$this->config->get(self::CONFIG_ROOT . 'use_database_scopes', false);
    }

}
