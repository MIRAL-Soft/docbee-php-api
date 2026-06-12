<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\SlaProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee SlaProfile records.
 *
 * @extends AbstractResource<SlaProfileDTO>
 */
final class SlaProfileResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'slaProfile';
    protected string $dtoClass = SlaProfileDTO::class;
    protected string $listKey  = 'slaProfile';
}