<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CostEstimationSubTaskDTO;

/**
 * Provides access to Docbee CostEstimationSubTask records (sub-resource of costEstimation task).
 *
 * @extends AbstractResource<CostEstimationSubTaskDTO>
 */
final class CostEstimationSubTaskResource extends AbstractResource
{
    protected string $dtoClass = CostEstimationSubTaskDTO::class;
    protected string $listKey  = 'costEstimationSubTask';

    public function __construct(HttpClientInterface $http, int $costEstimationId, int $taskId)
    {
        $this->endpoint = "costEstimation/{$costEstimationId}/task/{$taskId}/subTask";
        parent::__construct($http);
    }
}
