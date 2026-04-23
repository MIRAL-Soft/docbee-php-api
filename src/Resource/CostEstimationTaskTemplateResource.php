<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\CostEstimationTaskTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CostEstimationTaskTemplate records (sub-resource).
 *
 * @extends AbstractResource<CostEstimationTaskTemplateDTO>
 */
final class CostEstimationTaskTemplateResource extends AbstractResource
{
    protected string $dtoClass = CostEstimationTaskTemplateDTO::class;
    protected string $listKey  = 'costEstimationTask';

    public function __construct(HttpClientInterface $http, int $costEstimationTemplateId)
    {
        $this->endpoint = "costEstimationTemplate/{$costEstimationTemplateId}/task";
        parent::__construct($http);
    }
}