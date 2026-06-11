<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;
use miralsoft\docbee\api\DTO\ProtocolEntryDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolEntry records for a Protocol (sub-resource).
 *
 * Supports standard CRUD via inherited methods plus lookup by entry mapping,
 * group index, and placeholder name.
 *
 * @extends AbstractResource<ProtocolEntryDTO>
 */
final class ProtocolEntryResource extends AbstractResource
{
    protected string $dtoClass = ProtocolEntryDTO::class;
    protected string $listKey  = 'protocolEntry';

    public function __construct(HttpClientInterface $http, int $protocolId)
    {
        $this->endpoint = "protocol/{$protocolId}/protocolEntry";
        parent::__construct($http);
    }

    /** Get a protocol entry by its entry mapping ID. */
    public function getByEntryMapping(int $entryMappingId): ProtocolEntryDTO
    {
        return ProtocolEntryDTO::fromArray(
            $this->http->get("{$this->endpoint}/byEntryMapping/{$entryMappingId}")
        );
    }

    /** Update a protocol entry by its entry mapping ID. */
    public function updateByEntryMapping(int $entryMappingId, array $data): ProtocolEntryDTO
    {
        return ProtocolEntryDTO::fromArray(
            $this->http->put("{$this->endpoint}/byEntryMapping/{$entryMappingId}", $data)
        );
    }

    /** Get a protocol entry by entry mapping ID and group index (for multi-groups). */
    public function getByEntryMappingAndGroupIdx(int $entryMappingId, int $groupIdx): ProtocolEntryDTO
    {
        return ProtocolEntryDTO::fromArray(
            $this->http->get("{$this->endpoint}/byEntryMapping/{$entryMappingId}/{$groupIdx}")
        );
    }

    /** Update a protocol entry by entry mapping ID and group index (for multi-groups). */
    public function updateByEntryMappingAndGroupIdx(int $entryMappingId, int $groupIdx, array $data): ProtocolEntryDTO
    {
        return ProtocolEntryDTO::fromArray(
            $this->http->put("{$this->endpoint}/byEntryMapping/{$entryMappingId}/{$groupIdx}", $data)
        );
    }

    /**
     * Find a protocol entry by its template placeholder name.
     *
     * The name is URL-encoded — placeholder names are free text from templates
     * and must not be able to alter the request path.
     *
     * @throws \InvalidArgumentException when $name is empty.
     */
    public function findByPlaceholderName(string $name): ProtocolEntryDTO
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('findByPlaceholderName(): name must not be empty.');
        }
        return ProtocolEntryDTO::fromArray(
            $this->http->get("{$this->endpoint}/findByPlaceholderName/" . rawurlencode($name))
        );
    }

    /**
     * Update a protocol entry by its template placeholder name.
     *
     * The name is URL-encoded — this is a WRITE operation; an unencoded name
     * could redirect the PUT to a different endpoint entirely.
     *
     * @throws \InvalidArgumentException when $name is empty.
     */
    public function updateByPlaceholderName(string $name, array $data): ProtocolEntryDTO
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('updateByPlaceholderName(): name must not be empty.');
        }
        return ProtocolEntryDTO::fromArray(
            $this->http->put("{$this->endpoint}/updateByPlaceholderName/" . rawurlencode($name), $data)
        );
    }
}
