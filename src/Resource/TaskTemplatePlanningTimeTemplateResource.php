<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;

/**
 * Provides access to Docbee PlanningTimeTemplate records (sub-resource of taskTemplate).
 *
 * @extends AbstractResource<PlanningTimeDTO>
 */
final class TaskTemplatePlanningTimeTemplateResource extends AbstractResource
{
    protected string $dtoClass = PlanningTimeDTO::class;
    protected string $listKey  = 'planningTimeTemplate';

    public function __construct(HttpClientInterface $http, int $taskTemplateId)
    {
        $this->endpoint = "taskTemplate/{$taskTemplateId}/planningTimeTemplate";
        parent::__construct($http);
    }
}
