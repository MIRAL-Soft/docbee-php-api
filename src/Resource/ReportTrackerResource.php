<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ReportTrackerDTO;

/**
 * Provides access to Docbee ReportTracker records.
 *
 * @extends AbstractResource<ReportTrackerDTO>
 */
final class ReportTrackerResource extends AbstractResource
{
    protected string $endpoint = 'v1/reportTracker';
    protected string $dtoClass = ReportTrackerDTO::class;
    protected string $listKey  = 'reportTracker';
}
