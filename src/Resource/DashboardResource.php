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
    protected string $endpoint = 'dashboard';
    protected string $dtoClass = DashboardDTO::class;
    protected string $listKey  = 'dashboard';

    /** Returns the default dashboard. */
    public function getDefault(): DashboardDTO
    {
        return DashboardDTO::fromArray($this->http->get("{$this->endpoint}/default"));
    }

    /** Returns available widget types. */
    public function getWidgetTypes(): array
    {
        return $this->http->get("{$this->endpoint}/widgetType")['dashboardWidgetType'] ?? [];
    }

    /** Subscribe to a dashboard. */
    public function subscribe(int $id): void { $this->http->put("{$this->endpoint}/{$id}/subscribe", []); }

    /** Unsubscribe from a dashboard. */
    public function unsubscribe(int $id): void { $this->http->put("{$this->endpoint}/{$id}/unsubscribe", []); }
}