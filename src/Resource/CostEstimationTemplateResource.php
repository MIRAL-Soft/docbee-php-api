<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CostEstimationTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

use miralsoft\docbee\api\Resource\Concerns\NotSearchable;
/**
 * Provides access to Docbee CostEstimationTemplate records.
 *
 * @extends AbstractResource<CostEstimationTemplateDTO>
 */
final class CostEstimationTemplateResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'costEstimationTemplate';
    protected string $dtoClass = CostEstimationTemplateDTO::class;
    protected string $listKey  = 'costEstimationTemplate';

    /** @return array<string, mixed> */
    public function createPayloadForCostEstimation(int $id): array { return $this->http->get("{$this->endpoint}/{$id}/createPayloadForCostEstimation"); }
}