<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\SelectionCategoryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SelectionCategory records.
 *
 * @extends AbstractResource<SelectionCategoryDTO>
 */
final class SelectionCategoryResource extends AbstractResource
{
    protected string $endpoint = 'v1/selectionCategory';
    protected string $dtoClass = SelectionCategoryDTO::class;
    protected string $listKey  = 'selectionCategory';
}