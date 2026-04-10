<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolDocumentTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolDocumentTemplate records (sub-resource).
 *
 * @extends AbstractResource<ProtocolDocumentTemplateDTO>
 */
final class ProtocolDocumentTemplateResource extends AbstractResource
{
    protected string $dtoClass = ProtocolDocumentTemplateDTO::class;
    protected string $listKey  = 'protocolDocumentTemplate';

    public function __construct(HttpClientInterface $http, int $protocolTemplateId)
    {
        $this->endpoint = "v1/protocolTemplate/{$protocolTemplateId}/documentTemplate";
        parent::__construct($http);
    }
}