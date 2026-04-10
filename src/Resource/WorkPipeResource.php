<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\WorkPipeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee WorkPipe records.
 *
 * @extends AbstractResource<WorkPipeDTO>
 */
final class WorkPipeResource extends AbstractResource
{
    protected string $endpoint = 'v1/workPipe';
    protected string $dtoClass = WorkPipeDTO::class;
    protected string $listKey  = 'workPipe';
}