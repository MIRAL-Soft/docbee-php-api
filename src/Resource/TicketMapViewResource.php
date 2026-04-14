<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MapViewDTO;

/**
 * Provides access to Docbee ticket map view records.
 *
 * @extends AbstractResource<MapViewDTO>
 */
final class TicketMapViewResource extends AbstractResource
{
    protected string $endpoint = 'v1/ticketMapView';
    protected string $dtoClass = MapViewDTO::class;
    protected string $listKey  = 'ticketMapView';

    public function setFavorite(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/setFavorite", []);
    }

    public function unsetFavorite(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/unsetFavorite", []);
    }
}
