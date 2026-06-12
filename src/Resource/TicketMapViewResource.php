<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MapViewDTO;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee ticket map view records.
 *
 * @extends AbstractResource<MapViewDTO>
 */
final class TicketMapViewResource extends AbstractResource
{
    use NotSearchable;
    protected string $endpoint = 'ticketMapView';
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
