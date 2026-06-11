<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TableConfigStorageFilterDTO;

/**
 * Provides access to Docbee map view filter records (sub-resource).
 *
 * @extends AbstractResource<TableConfigStorageFilterDTO>
 */
final class MapViewFilterResource extends AbstractResource
{
    /** Map view types accepted by the Docbee API. */
    public const array MAP_VIEW_TYPES = ['ticketMapView', 'protocolMapView', 'docBeeDocumentMapView'];

    protected string $dtoClass = TableConfigStorageFilterDTO::class;
    protected string $listKey  = 'filter';

    /**
     * @param string $mapViewType One of {@see MAP_VIEW_TYPES}.  Validated against the
     *                            whitelist because the value becomes part of the endpoint
     *                            path for every inherited CRUD method.
     * @throws \InvalidArgumentException when $mapViewType is not a known map view type.
     */
    public function __construct(HttpClientInterface $http, string $mapViewType, int $mapViewId)
    {
        if (!in_array($mapViewType, self::MAP_VIEW_TYPES, true)) {
            throw new \InvalidArgumentException(
                "MapViewFilterResource: unknown mapViewType '{$mapViewType}'. Allowed: "
                . implode(', ', self::MAP_VIEW_TYPES)
            );
        }
        $this->endpoint = "{$mapViewType}/{$mapViewId}/filter";
        parent::__construct($http);
    }
}
