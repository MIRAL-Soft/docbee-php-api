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
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class ObserverUserResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'observerUser';
    protected string $dtoClass = ObserverUserDTO::class;
    protected string $listKey  = 'observerUser';

    public function reset2FA(int $id): void { $this->http->put("{$this->endpoint}/{$id}/reset2FA", []); }
}