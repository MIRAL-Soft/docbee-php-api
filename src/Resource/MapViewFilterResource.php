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
    protected string $dtoClass = TableConfigStorageFilterDTO::class;
    protected string $listKey  = 'filter';

    public function __construct(HttpClientInterface $http, string $mapViewType, int $mapViewId)
    {
        // mapViewType: ticketMapView, protocolMapView, docBeeDocumentMapView
        $this->endpoint = "v1/{$mapViewType}/{$mapViewId}/filter";
        parent::__construct($http);
    }
}
