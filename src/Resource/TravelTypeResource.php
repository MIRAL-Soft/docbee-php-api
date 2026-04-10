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
final class TravelTypeResource extends AbstractResource
{
    protected string $endpoint = 'v1/travelType';
    protected string $dtoClass = TravelTypeDTO::class;
    protected string $listKey  = 'travelType';
}