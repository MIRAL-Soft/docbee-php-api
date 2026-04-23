<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomColorDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomColor records.
 *
 * @extends AbstractResource<CustomColorDTO>
 */
final class CustomColorResource extends AbstractResource
{
    protected string $endpoint = 'customColor';
    protected string $dtoClass = CustomColorDTO::class;
    protected string $listKey  = 'customColor';
}