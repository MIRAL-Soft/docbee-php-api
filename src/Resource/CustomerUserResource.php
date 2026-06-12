<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerUserDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

use miralsoft\docbee\api\Resource\Concerns\NotSearchable;
/**
 * Provides access to Docbee CustomerUser records.
 *
 * @extends AbstractResource<CustomerUserDTO>
 */
final class CustomerUserResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'customerUser';
    protected string $dtoClass = CustomerUserDTO::class;
    protected string $listKey  = 'customerUser';

    public function reset2FA(int $id): void { $this->http->put("{$this->endpoint}/{$id}/reset2FA", []); }
}