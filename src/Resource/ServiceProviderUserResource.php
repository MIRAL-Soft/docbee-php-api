<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ServiceProviderUserDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ServiceProviderUser records.
 *
 * @extends AbstractResource<ServiceProviderUserDTO>
 */
final class ServiceProviderUserResource extends AbstractResource
{
    protected string $endpoint = 'v1/serviceProviderUser';
    protected string $dtoClass = ServiceProviderUserDTO::class;
    protected string $listKey  = 'serviceProviderUser';
}