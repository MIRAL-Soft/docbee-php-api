<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CostEstimationDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CostEstimation records.
 *
 * @extends AbstractResource<CostEstimationDTO>
 */
final class CostEstimationResource extends AbstractResource
{
    protected string $endpoint = 'v1/costEstimation';
    protected string $dtoClass = CostEstimationDTO::class;
    protected string $listKey  = 'costEstimation';

    public function approve(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/approve", []); }
    public function finish(int $id): array { return $this->http->put("{$this->endpoint}/{$id}/finish", []); }
}