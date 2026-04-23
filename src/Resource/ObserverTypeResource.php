<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverTypeDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ObserverType records.
 *
 * @extends AbstractResource<ObserverTypeDTO>
 */
final class ObserverTypeResource extends AbstractResource
{
    protected string $endpoint = 'observerType';
    protected string $dtoClass = ObserverTypeDTO::class;
    protected string $listKey  = 'observerType';
}