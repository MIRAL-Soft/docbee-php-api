<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ObserverCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ObserverCategory records.
 *
 * @extends AbstractResource<ObserverCategoryDTO>
 */
final class ObserverCategoryResource extends AbstractResource
{
    protected string $endpoint = 'v1/observerCategory';
    protected string $dtoClass = ObserverCategoryDTO::class;
    protected string $listKey  = 'observerCategory';
}