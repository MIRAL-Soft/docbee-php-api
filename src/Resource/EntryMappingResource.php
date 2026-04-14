<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\EntryMappingDTO;

/**
 * Provides access to Docbee EntryMapping records (sub-resource of protocolTemplate group).
 *
 * @extends AbstractResource<EntryMappingDTO>
 */
final class EntryMappingResource extends AbstractResource
{
    protected string $dtoClass = EntryMappingDTO::class;
    protected string $listKey  = 'entryMapping';

    public function __construct(HttpClientInterface $http, int $templateId, int $groupId)
    {
        $this->endpoint = "v1/protocolTemplate/{$templateId}/group/{$groupId}/entryMapping";
        parent::__construct($http);
    }
}
