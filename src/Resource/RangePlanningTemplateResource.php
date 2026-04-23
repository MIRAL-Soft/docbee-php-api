<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\RangePlanningTemplateDTO;

/**
 * Provides access to Docbee RangePlanningTemplate records.
 *
 * @extends AbstractResource<RangePlanningTemplateDTO>
 */
final class RangePlanningTemplateResource extends AbstractResource
{
    protected string $endpoint = 'rangePlanningTemplate';
    protected string $dtoClass = RangePlanningTemplateDTO::class;
    protected string $listKey  = 'rangePlanningTemplate';
}
