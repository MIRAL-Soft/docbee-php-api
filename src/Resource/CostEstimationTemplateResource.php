<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CostEstimationTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CostEstimationTemplate records.
 *
 * @extends AbstractResource<CostEstimationTemplateDTO>
 */
final class CostEstimationTemplateResource extends AbstractResource
{
    protected string $endpoint = 'v1/costEstimationTemplate';
    protected string $dtoClass = CostEstimationTemplateDTO::class;
    protected string $listKey  = 'costEstimationTemplate';

    public function createPayloadForCostEstimation(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/createPayloadForCostEstimation"); }
}