<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\TravelTypeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee TravelType records.
 *
 * @extends AbstractResource<TravelTypeDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class TravelTypeResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'travelType';
    protected string $dtoClass = TravelTypeDTO::class;
    protected string $listKey  = 'travelType';

    public function guess(array $data): array { return $this->http->post("{$this->endpoint}/guess", $data); }
}