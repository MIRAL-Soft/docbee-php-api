<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TableConfigStorageDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TableConfigStorage records.
 *
 * @extends AbstractResource<TableConfigStorageDTO>
 */
final class TableConfigStorageResource extends AbstractResource
{
    protected string $endpoint = 'v1/tableConfigStorage';
    protected string $dtoClass = TableConfigStorageDTO::class;
    protected string $listKey  = 'tableConfigStorage';
}