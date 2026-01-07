<?php

declare(strict_types=1);

namespace N3XT0R\LaravelPassportAuthorizationCore\Models\Concerns;

interface HasRelationshipsInterface
{
    public function morphMany($related, $name, $type = null, $id = null, $localKey = null);
}
