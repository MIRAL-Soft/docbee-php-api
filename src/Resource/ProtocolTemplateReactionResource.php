<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolTemplateReactionDTO;

/**
 * Provides access to Docbee ProtocolTemplateReaction records (sub-resource of protocolTemplate action).
 *
 * @extends AbstractResource<ProtocolTemplateReactionDTO>
 */
final class ProtocolTemplateReactionResource extends AbstractResource
{
    protected string $dtoClass = ProtocolTemplateReactionDTO::class;
    protected string $listKey  = 'protocolTemplateReaction';

    public function __construct(HttpClientInterface $http, int $templateId, int $actionId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/action/{$actionId}/reaction";
        parent::__construct($http);
    }
}
