<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionUpdatedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ActionService;

/**
 * Edit Action Use Case for OAuth Passport Scope Actions.
 */
readonly class EditActionUseCase
{
    public function __construct(protected ActionService $actionService)
    {
    }

    public function execute(
        PassportScopeAction $action,
        array $data,
        ?Authenticatable $actor = null
    ): PassportScopeAction {
        $result = $this->actionService->updateAction(
            action: $action,
            data: $data,
            actor: $actor
        );

        ActionUpdatedEvent::dispatch($result, $actor);

        return $result;
    }
}
