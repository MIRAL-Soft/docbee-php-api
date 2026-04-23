<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\WorkLogDTO;

/**
 * Provides access to Docbee WorkLogTemplate records (sub-resource of taskTemplate).
 *
 * @extends AbstractResource<WorkLogDTO>
 */
final class TaskTemplateWorkLogTemplateResource extends AbstractResource
{
    protected string $dtoClass = WorkLogDTO::class;
    protected string $listKey  = 'workLogTemplate';

    public function __construct(HttpClientInterface $http, int $taskTemplateId)
    {
        $this->endpoint = "taskTemplate/{$taskTemplateId}/workLogTemplate";
        parent::__construct($http);
    }
}
