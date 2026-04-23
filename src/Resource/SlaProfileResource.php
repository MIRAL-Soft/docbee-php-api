<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\SlaProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee SlaProfile records.
 *
 * @extends AbstractResource<SlaProfileDTO>
 */
final class SlaProfileResource extends AbstractResource
{
    protected string $endpoint = 'slaProfile';
    protected string $dtoClass = SlaProfileDTO::class;
    protected string $listKey  = 'slaProfile';
}