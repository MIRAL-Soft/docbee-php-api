<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\CustomFieldDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee CustomField records.
 *
 * @extends AbstractResource<CustomFieldDTO>
 */
final class CustomFieldResource extends AbstractResource
{
    protected string $endpoint = 'v1/customField';
    protected string $dtoClass = CustomFieldDTO::class;
    protected string $listKey  = 'customField';
}