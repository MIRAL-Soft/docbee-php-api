<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolTemplateConditionCollectionDTO;

/**
 * Provides access to Docbee ProtocolTemplateConditionCollection records (sub-resource of protocolTemplate action).
 *
 * @extends AbstractResource<ProtocolTemplateConditionCollectionDTO>
 */
final class ProtocolTemplateConditionCollectionResource extends AbstractResource
{
    protected string $dtoClass = ProtocolTemplateConditionCollectionDTO::class;
    protected string $listKey  = 'protocolTemplateConditionCollection';

    public function __construct(HttpClientInterface $http, int $templateId, int $actionId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/action/{$actionId}/conditionCollection";
        parent::__construct($http);
    }
}
