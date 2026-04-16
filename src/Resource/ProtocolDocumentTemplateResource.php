<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolDocumentTemplateDTO;

/**
 * Provides access to Docbee ProtocolDocumentTemplate records.
 * When $protocolTemplateId is provided, uses the sub-resource endpoint
 * v1/protocolTemplate/{id}/documentTemplate; otherwise uses v1/protocolDocumentTemplate.
 *
 * @extends AbstractResource<ProtocolDocumentTemplateDTO>
 */
final class ProtocolDocumentTemplateResource extends AbstractResource
{
    protected string $dtoClass = ProtocolDocumentTemplateDTO::class;
    protected string $listKey  = 'protocolDocumentTemplate';

    public function __construct(HttpClientInterface $http, ?int $protocolTemplateId = null)
    {
        if ($protocolTemplateId !== null && $protocolTemplateId <= 0) {
            throw new \InvalidArgumentException('protocolTemplateId must be a positive integer.');
        }
        $this->endpoint = $protocolTemplateId !== null
            ? "v1/protocolTemplate/{$protocolTemplateId}/documentTemplate"
            : 'v1/protocolDocumentTemplate';
        parent::__construct($http);
    }
}
