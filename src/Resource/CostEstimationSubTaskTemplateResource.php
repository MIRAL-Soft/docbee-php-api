<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CostEstimationSubTaskTemplateDTO;

/**
 * Provides access to Docbee CostEstimationSubTaskTemplate records (sub-resource of costEstimationTemplate task).
 *
 * @extends AbstractResource<CostEstimationSubTaskTemplateDTO>
 */
final class CostEstimationSubTaskTemplateResource extends AbstractResource
{
    protected string $dtoClass = CostEstimationSubTaskTemplateDTO::class;
    protected string $listKey  = 'costEstimationSubTaskTemplate';

    public function __construct(HttpClientInterface $http, int $costEstimationId, int $taskId)
    {
        $this->endpoint = "v1/costEstimationTemplate/{$costEstimationId}/task/{$taskId}/subTask";
        parent::__construct($http);
    }
}
