<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ExportProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ExportProfile records.
 *
 * @extends AbstractResource<ExportProfileDTO>
 */
final class ExportProfileResource extends AbstractResource
{
    protected string $endpoint = 'v1/exportProfile';
    protected string $dtoClass = ExportProfileDTO::class;
    protected string $listKey  = 'exportProfile';
}