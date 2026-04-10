<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceProviderDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ServiceProvider records.
 *
 * @extends AbstractResource<ServiceProviderDTO>
 */
final class ServiceProviderResource extends AbstractResource
{
    protected string $endpoint = 'v1/serviceProvider';
    protected string $dtoClass = ServiceProviderDTO::class;
    protected string $listKey  = 'serviceProvider';
}