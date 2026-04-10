<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DepartmentProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DepartmentProfile records.
 *
 * @extends AbstractResource<DepartmentProfileDTO>
 */
final class DepartmentProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/departmentProfile';
    protected string $dtoClass = DepartmentProfileDTO::class;
    protected string $listKey  = 'departmentProfile';
}