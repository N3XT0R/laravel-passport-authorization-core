<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Application\UseCases\Actions;

use Illuminate\Contracts\Auth\Authenticatable;
use N3XT0R\LaravelPassportAuthorizationCore\Events\PassportScopeAction\ActionDeletedEvent;
use N3XT0R\LaravelPassportAuthorizationCore\Models\PassportScopeAction;
use N3XT0R\LaravelPassportAuthorizationCore\Services\ActionService;

/**
 * Delete Action Use Case for OAuth Passport Scope Actions.
 */
readonly class DeleteActionUseCase
{
    public function __construct(protected ActionService $actionService)
    {
    }

    public function execute(PassportScopeAction $action, ?Authenticatable $actor = null): bool
    {
        $result = $this->actionService->deleteAction($action, $actor);
        if ($result) {
            ActionDeletedEvent::dispatch($action, $actor);
        }
        return $result;
    }
}
