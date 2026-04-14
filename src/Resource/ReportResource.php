<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ReportDTO;

/**
 * Provides access to Docbee Report records.
 *
 * @extends AbstractResource<ReportDTO>
 */
final class ReportResource extends AbstractResource
{
    protected string $endpoint = 'v1/report';
    protected string $dtoClass = ReportDTO::class;
    protected string $listKey  = 'report';
}
