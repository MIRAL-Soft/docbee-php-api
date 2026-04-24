<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ReportTrackerDTO;

/**
 * Provides access to Docbee ReportTracker records.
 *
 * @extends AbstractResource<ReportTrackerDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class ReportTrackerResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'reportTracker';
    protected string $dtoClass = ReportTrackerDTO::class;
    protected string $listKey  = 'reportTracker';
}
