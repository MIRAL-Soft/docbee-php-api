<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ContingentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Contingent records.
 *
 * @extends AbstractResource<ContingentDTO>
 */
final class ContingentResource extends AbstractResource
{
    protected string $endpoint = 'contingent';
    protected string $dtoClass = ContingentDTO::class;
    protected string $listKey  = 'contingent';
}