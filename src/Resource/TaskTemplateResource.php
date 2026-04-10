<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TaskTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TaskTemplate records.
 *
 * @extends AbstractResource<TaskTemplateDTO>
 */
final class TaskTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/taskTemplate';
    protected string $dtoClass = TaskTemplateDTO::class;
    protected string $listKey  = 'taskTemplate';
}