<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\DashboardWidgetDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee DashboardWidget records (sub-resource).
 *
 * @extends AbstractResource<DashboardWidgetDTO>
 */
final class DashboardWidgetResource extends AbstractResource
{
    protected string $dtoClass = DashboardWidgetDTO::class;
    protected string $listKey  = 'dashboardWidget';

    public function __construct(HttpClientInterface $http, int $dashboardId)
    {
        $this->endpoint = "dashboard/{$dashboardId}/widget";
        parent::__construct($http);
    }
}