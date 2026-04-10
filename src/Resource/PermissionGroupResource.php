<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\PermissionGroupDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee PermissionGroup records.
 *
 * @extends AbstractResource<PermissionGroupDTO>
 */
final class PermissionGroupResource extends AbstractResource
{
    protected string $endpoint = 'v1/permissionGroup';
    protected string $dtoClass = PermissionGroupDTO::class;
    protected string $listKey  = 'permissionGroup';
}