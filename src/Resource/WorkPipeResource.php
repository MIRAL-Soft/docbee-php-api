<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\WorkPipeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee WorkPipe records.
 *
 * @extends AbstractResource<WorkPipeDTO>
 */
final class WorkPipeResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'workPipe';
    protected string $dtoClass = WorkPipeDTO::class;
    protected string $listKey  = 'workPipe';
}