<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DepartmentDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Department records.
 *
 * @extends AbstractResource<DepartmentDTO>
 */
final class DepartmentResource extends AbstractResource
{
    protected string $endpoint = 'department';
    protected string $dtoClass = DepartmentDTO::class;
    protected string $listKey  = 'department';
}