<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TravelLogDTO;

/**
 * Provides access to Docbee TravelLog records for a DocBeeDocument (sub-resource).
 *
 * @extends AbstractResource<TravelLogDTO>
 */
final class DocBeeDocumentTravelLogResource extends AbstractResource
{
    protected string $dtoClass = TravelLogDTO::class;
    protected string $listKey  = 'travelLog';

    public function __construct(HttpClientInterface $http, int $docBeeDocumentId)
    {
        $this->endpoint = "v1/docBeeDocument/{$docBeeDocumentId}/travelLog";
        parent::__construct($http);
    }
}
