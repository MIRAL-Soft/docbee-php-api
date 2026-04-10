<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\DashboardDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Dashboard records.
 *
 * @extends AbstractResource<DashboardDTO>
 */
final class DashboardResource extends AbstractResource
{
    protected string $endpoint = 'v1/dashboard';
    protected string $dtoClass = DashboardDTO::class;
    protected string $listKey  = 'dashboard';
}