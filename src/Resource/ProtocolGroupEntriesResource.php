<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolEntryDTO;

/**
 * Provides access to Docbee ProtocolGroupEntries for a specific template group within
 * a Protocol (sub-resource).
 *
 * Each "group instance" is addressed by a zero-based groupIdx. Standard CRUD methods
 * inherited from AbstractResource operate on individual instances via groupIdx as the ID.
 *
 * @extends AbstractResource<ProtocolEntryDTO>
 */
final class ProtocolGroupEntriesResource extends AbstractResource
{
    protected string $dtoClass = ProtocolEntryDTO::class;
    protected string $listKey  = 'protocolEntry';

    public function __construct(HttpClientInterface $http, int $protocolId, int $groupId)
    {
        $this->endpoint = "v1/protocol/{$protocolId}/protocolGroupEntries/{$groupId}";
        parent::__construct($http);
    }

    /** Replace all group instances at once. */
    public function updateAll(array $data): array
    {
        return $this->http->put($this->endpoint, $data);
    }

    /** Delete all instances of this group. */
    public function deleteAll(): void
    {
        $this->http->delete($this->endpoint);
    }
}
