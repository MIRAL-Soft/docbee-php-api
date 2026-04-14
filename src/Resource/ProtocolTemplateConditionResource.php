<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolTemplateConditionDTO;

/**
 * Provides access to Docbee ProtocolTemplateCondition records (sub-resource of protocolTemplate action conditionCollection).
 *
 * @extends AbstractResource<ProtocolTemplateConditionDTO>
 */
final class ProtocolTemplateConditionResource extends AbstractResource
{
    protected string $dtoClass = ProtocolTemplateConditionDTO::class;
    protected string $listKey  = 'protocolTemplateCondition';

    public function __construct(HttpClientInterface $http, int $templateId, int $actionId, int $conditionCollectionId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/action/{$actionId}/conditionCollection/{$conditionCollectionId}/condition";
        parent::__construct($http);
    }
}
