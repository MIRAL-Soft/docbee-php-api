<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TaskTemplateProfileDTO;

/**
 * Provides access to Docbee TaskTemplateProfile records.
 *
 * @extends AbstractResource<TaskTemplateProfileDTO>
 */
final class TaskTemplateProfileResource extends AbstractResource
{
    protected string $endpoint = 'taskTemplateProfile';
    protected string $dtoClass = TaskTemplateProfileDTO::class;
    protected string $listKey  = 'taskTemplateProfile';
}
