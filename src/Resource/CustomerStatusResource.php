<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerStatusDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomerStatus records.
 *
 * @extends AbstractResource<CustomerStatusDTO>
 */
final class CustomerStatusResource extends AbstractResource
{
    protected string $endpoint = 'customerStatus';
    protected string $dtoClass = CustomerStatusDTO::class;
    protected string $listKey  = 'customerStatus';
}