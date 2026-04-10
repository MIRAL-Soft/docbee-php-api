<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CostEstimationTaskDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CostEstimationTask records (sub-resource).
 *
 * @extends AbstractResource<CostEstimationTaskDTO>
 */
final class CostEstimationTaskResource extends AbstractResource
{
    protected string $dtoClass = CostEstimationTaskDTO::class;
    protected string $listKey  = 'costEstimationTask';

    public function __construct(HttpClientInterface $http, int $costEstimationId)
    {
        $this->endpoint = "v1/costEstimation/{$costEstimationId}/task";
        parent::__construct($http);
    }
}