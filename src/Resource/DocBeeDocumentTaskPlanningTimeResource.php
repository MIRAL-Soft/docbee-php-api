<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\PlanningTimeDTO;

/**
 * Provides access to Docbee PlanningTime records for a DocBeeDocument Task (sub-resource).
 *
 * @extends AbstractResource<PlanningTimeDTO>
 */
final class DocBeeDocumentTaskPlanningTimeResource extends AbstractResource
{
    protected string $dtoClass = PlanningTimeDTO::class;
    protected string $listKey  = 'planningTime';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId, int $taskId)
    {
        $this->endpoint = "docBeeDocument/{$docBeeDocumentId}/task/{$taskId}/planningTime";
        parent::__construct($http);
    }
}
