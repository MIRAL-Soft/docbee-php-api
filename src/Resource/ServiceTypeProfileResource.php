<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceTypeProfileDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ServiceTypeProfile records.
 *
 * @extends AbstractResource<ServiceTypeProfileDTO>
 */
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

final class ServiceTypeProfileResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'serviceTypeProfile';
    protected string $dtoClass = ServiceTypeProfileDTO::class;
    protected string $listKey  = 'serviceTypeProfile';
}