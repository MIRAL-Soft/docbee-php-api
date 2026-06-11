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
    protected string $endpoint = 'protocol';
    protected string $dtoClass = ProtocolDTO::class;
    protected string $listKey  = 'protocol';

    /**
     * Find a protocol by its number.
     *
     * @throws \InvalidArgumentException when $number is empty.
     */
    public function findByNumber(string $number): ProtocolDTO
    {
        if (trim($number) === '') {
            throw new \InvalidArgumentException('findByNumber(): number must not be empty.');
        }
        return ProtocolDTO::fromArray($this->http->get("{$this->endpoint}/findByNumber/" . rawurlencode($number)));
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

    /**
     * Renders a protocol as a PDF and returns the raw bytes.
     *
     * Maps to `GET /protocol/{id}/preview`, which returns a binary PDF (`application/pdf`),
     * not JSON.  The response is fetched via `getRaw()` — the same pattern as
     * {@see export()} / {@see exportByIds()} — so the binary content is returned intact.
     *
     * ```php
     * $pdf = $client->protocols()->preview($protocolId);
     * file_put_contents('protocol.pdf', $pdf);   // %PDF…
     * ```
     *
     * @return string Raw PDF bytes (response starts with `%PDF`).
     * @throws \miralsoft\docbee\api\Exception\DocbeeApiException
     */
    public function preview(int $id): string
    {
        return $this->http->getRaw("{$this->endpoint}/{$id}/preview");
    }

    /** Instant finish for a protocol by ID. */
    public function instantFinishById(int $id, array $data = []): ProtocolDTO
    {
        return ProtocolDTO::fromArray($this->http->put("{$this->endpoint}/{$id}/instantFinish", $data));
    }

    /** Export protocols for the given export profile. Returns raw file bytes (PDF/CSV). */
    public function export(int $exportProfileId): string
    {
        return $this->http->getRaw("{$this->endpoint}/export/{$exportProfileId}");
    }

    /** Export specific protocols (by IDs) for the given export profile. Returns raw file bytes (PDF/CSV). */
    public function exportByIds(int $exportProfileId, array $ids): string
    {
        return $this->postExportByIds("{$this->endpoint}/exportByIds/{$exportProfileId}", $ids);
    }
}