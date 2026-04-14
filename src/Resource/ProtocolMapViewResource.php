<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\MapViewDTO;

/**
 * Provides access to Docbee protocol map view records.
 *
 * @extends AbstractResource<MapViewDTO>
 */
final class ProtocolMapViewResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocolMapView';
    protected string $dtoClass = MapViewDTO::class;
    protected string $listKey  = 'protocolMapView';

    public function setFavorite(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/setFavorite", []);
    }

    public function unsetFavorite(int $id): void
    {
        $this->http->put("{$this->endpoint}/{$id}/unsetFavorite", []);
    }
}
