<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ElementDTO;

/**
 * Provides access to Docbee Element records for a ProtocolTemplateEntry (sub-resource).
 *
 * @extends AbstractResource<ElementDTO>
 */
final class ProtocolTemplateEntryElementResource extends AbstractResource
{
    protected string $dtoClass = ElementDTO::class;
    protected string $listKey  = 'element';

    public function __construct(HttpClientInterface $http, int $entryId)
    {
        $this->endpoint = "v1/protocolTemplateEntry/{$entryId}/element";
        parent::__construct($http);
    }
}
