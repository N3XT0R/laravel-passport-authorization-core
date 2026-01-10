<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionCreatedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ActionService;

/**
 * Create Action Use Case for OAuth Passport Scope Actions.
 */
readonly class CreateActionUseCase
{
    public function __construct(protected ActionService $actionService)
    {
    }

    public function execute(array $data, ?Authenticatable $actor = null): PassportScopeAction
    {
        $result = $this->actionService->createAction($data, $actor);
        if ($result->exists) {
            ActionCreatedEvent::dispatch($result, $actor);
        }

        return $result;
    }
}
