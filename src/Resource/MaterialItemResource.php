<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MaterialItemDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee MaterialItem records.
 *
 * @extends AbstractResource<MaterialItemDTO>
 */
final class MaterialItemResource extends AbstractResource
{
    protected string $endpoint = 'materialItem';
    protected string $dtoClass = MaterialItemDTO::class;
    protected string $listKey  = 'materialItem';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}