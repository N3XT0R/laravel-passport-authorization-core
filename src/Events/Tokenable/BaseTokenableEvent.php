<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Events\Tokenable;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns\HasPassportScopeGrantsInterface;

abstract class BaseTokenableEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public HasPassportScopeGrantsInterface $model, public ?Authenticatable $actor = null)
    {
    }
}
