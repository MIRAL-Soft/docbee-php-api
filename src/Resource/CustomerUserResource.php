<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerUserDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomerUser records.
 *
 * @extends AbstractResource<CustomerUserDTO>
 */
final class CustomerUserResource extends AbstractResource
{
    protected string $endpoint = 'v1/customerUser';
    protected string $dtoClass = CustomerUserDTO::class;
    protected string $listKey  = 'customerUser';

    public function reset2FA(int $id): void { $this->http->put("{$this->endpoint}/{$id}/reset2FA", []); }
}