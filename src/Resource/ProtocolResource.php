<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee Protocol records.
 *
 * @extends AbstractResource<ProtocolDTO>
 */
final class ProtocolResource extends AbstractResource
{
    protected string $endpoint = 'v1/protocol';
    protected string $dtoClass = ProtocolDTO::class;
    protected string $listKey  = 'protocol';

    /** Find a protocol by its number. */
    public function findByNumber(string $number): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/{$number}"));
    }

    /** Instantly finish a protocol. */
    public function instantFinish(array $data): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->post("{$this->endpoint}/instantFinish", $data));
    }

    /** Create protocol from predecessor. */
    public function createFromPredecessor(int $predecessorId, array $data = []): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->post("{$this->endpoint}/createFromPredecessor/{$predecessorId}", $data));
    }

    /** Clone a protocol. */
    public function clone(int $id): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/clone", []));
    }

    /** Finish a protocol. */
    public function finish(int $id, array $data = []): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/finish", $data));
    }

    /** Cancel a protocol. */
    public function cancel(int $id, array $data = []): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/cancel", $data));
    }

    /** Cancel and clone a protocol. */
    public function cancelAndClone(int $id): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/cancelAndClone", []));
    }

    /** Get PDF preview data. */
    public function preview(int $id): array
    {
        return $this->http->get("{$this->endpoint}/{$id}/preview");
    }

    /** Instant finish for a protocol by ID. */
    public function instantFinishById(int $id, array $data = []): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/instantFinish", $data));
    }

    /** Export protocols for the given export profile. */
    public function export(int $exportProfileId): array
    {
        return $this->http->get("{$this->endpoint}/export/{$exportProfileId}");
    }

    /** Export specific protocols (by IDs) for the given export profile. */
    public function exportByIds(int $exportProfileId, array $ids): array
    {
        return $this->http->post("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids);
    }
}