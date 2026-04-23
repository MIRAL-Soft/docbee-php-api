<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolTemplateActionDTO;

/**
 * Provides access to Docbee ProtocolTemplateAction records (sub-resource of protocolTemplate).
 *
 * @extends AbstractResource<ProtocolTemplateActionDTO>
 */
final class ProtocolTemplateActionResource extends AbstractResource
{
    protected string $dtoClass = ProtocolTemplateActionDTO::class;
    protected string $listKey  = 'protocolTemplateAction';

    public function __construct(HttpClientInterface $http, int $templateId)
    {
        $this->endpoint = "protocolTemplate/{$templateId}/action";
        parent::__construct($http);
    }
}
