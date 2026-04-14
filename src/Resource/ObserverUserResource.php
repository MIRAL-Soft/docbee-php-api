<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverUserDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ObserverUser records.
 *
 * @extends AbstractResource<ObserverUserDTO>
 */
final class ObserverUserResource extends AbstractResource
{
    protected string $endpoint = 'v1/observerUser';
    protected string $dtoClass = ObserverUserDTO::class;
    protected string $listKey  = 'observerUser';

    public function reset2FA(int $id): void { $this->http->put("{$this->endpoint}/{$id}/reset2FA", []); }
}