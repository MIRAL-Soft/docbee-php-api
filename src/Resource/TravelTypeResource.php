<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TravelTypeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee TravelType records.
 *
 * @extends AbstractResource<TravelTypeDTO>
 */
final class TravelTypeResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'travelType';
    protected string $dtoClass = TravelTypeDTO::class;
    protected string $listKey  = 'travelType';

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}