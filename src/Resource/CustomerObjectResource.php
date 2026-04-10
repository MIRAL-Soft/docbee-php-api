<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomerObjectDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomerObject records.
 *
 * @extends AbstractResource<CustomerObjectDTO>
 */
final class CustomerObjectResource extends AbstractResource
{
    protected string $endpoint = 'v1/customerObject';
    protected string $dtoClass = CustomerObjectDTO::class;
    protected string $listKey  = 'customerObject';
}