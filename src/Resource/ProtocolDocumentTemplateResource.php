<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolDocumentTemplateDTO;
use miralsoft\docbee\api\Resource\Concerns\NotSearchable;

/**
 * Provides access to Docbee ProtocolDocumentTemplate records.
 * When $protocolTemplateId is provided, uses the sub-resource endpoint
 * v1/protocolTemplate/{id}/documentTemplate; otherwise uses v1/protocolDocumentTemplate.
 *
 * @extends AbstractResource<ProtocolDocumentTemplateDTO>
 */
final class ProtocolDocumentTemplateResource extends AbstractResource
{
    use NotSearchable;
    protected string $dtoClass = ProtocolDocumentTemplateDTO::class;
    protected string $listKey  = 'protocolDocumentTemplate';

    public function __construct(HttpClientInterface $http, ?int $protocolTemplateId = null)
    {
        if ($protocolTemplateId !== null && $protocolTemplateId <= 0) {
            throw new \InvalidArgumentException('protocolTemplateId must be a positive integer.');
        }
        $this->endpoint = $protocolTemplateId !== null
            ? "protocolTemplate/{$protocolTemplateId}/documentTemplate"
            : 'protocolDocumentTemplate';
        parent::__construct($http);
    }
}
