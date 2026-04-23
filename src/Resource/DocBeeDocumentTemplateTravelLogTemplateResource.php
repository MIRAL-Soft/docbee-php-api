<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\TravelLogTemplateDTO;

/**
 * Provides access to Docbee TravelLogTemplate records for a DocBeeDocumentTemplate (sub-resource).
 *
 * @extends AbstractResource<TravelLogTemplateDTO>
 */
final class DocBeeDocumentTemplateTravelLogTemplateResource extends AbstractResource
{
    protected string $dtoClass = TravelLogTemplateDTO::class;
    protected string $listKey  = 'travelLogTemplate';

    public function __construct(HttpClientInterface $http, int $documentTemplateId)
    {
        $this->endpoint = "docBeeDocumentTemplate/{$documentTemplateId}/travelLogTemplate";
        parent::__construct($http);
    }
}
