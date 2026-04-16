<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\Client\HttpClientInterface;

/**
 * Provides access to Docbee group entries and mappings within a Protocol.
 *
 * Handles the protocol/{protocolId}/group/{templateGroupId}/... path hierarchy,
 * including both the base group (all instances) and indexed group instances
 * (multi-groups addressed by groupIdx).
 */
final class ProtocolGroupResource
{
    public function __construct(
        private readonly HttpClientInterface $http,
        private readonly int $protocolId,
    ) {}

    private function base(int $templateGroupId): string
    {
        return "v1/protocol/{$this->protocolId}/group/{$templateGroupId}";
    }

    // ── Entries (all instances) ────────────────────────────────────────────────

    public function getEntries(int $templateGroupId): array
    {
        return $this->http->get($this->base($templateGroupId) . '/entries');
    }

    public function createEntries(int $templateGroupId, array $data): array
    {
        return $this->http->post($this->base($templateGroupId) . '/entries', $data);
    }

    public function updateEntries(int $templateGroupId, array $data): array
    {
        return $this->http->put($this->base($templateGroupId) . '/entries', $data);
    }

    public function deleteEntries(int $templateGroupId): void
    {
        $this->http->delete($this->base($templateGroupId) . '/entries');
    }

    // ── Mapping (all instances) ────────────────────────────────────────────────

    public function getMapping(int $templateGroupId): array
    {
        return $this->http->get($this->base($templateGroupId) . '/mapping');
    }

    public function updateMapping(int $templateGroupId, array $data): array
    {
        return $this->http->put($this->base($templateGroupId) . '/mapping', $data);
    }

    // ── Entries (specific group instance) ────────────────────────────────────

    public function getEntriesByGroupIdx(int $templateGroupId, int $groupIdx): array
    {
        return $this->http->get($this->base($templateGroupId) . "/{$groupIdx}/entries");
    }

    public function updateEntriesByGroupIdx(int $templateGroupId, int $groupIdx, array $data): array
    {
        return $this->http->put($this->base($templateGroupId) . "/{$groupIdx}/entries", $data);
    }

    public function deleteEntriesByGroupIdx(int $templateGroupId, int $groupIdx): void
    {
        $this->http->delete($this->base($templateGroupId) . "/{$groupIdx}/entries");
    }

    // ── Mapping (specific group instance) ────────────────────────────────────

    public function getMappingByGroupIdx(int $templateGroupId, int $groupIdx): array
    {
        return $this->http->get($this->base($templateGroupId) . "/{$groupIdx}/mapping");
    }

    public function updateMappingByGroupIdx(int $templateGroupId, int $groupIdx, array $data): array
    {
        return $this->http->put($this->base($templateGroupId) . "/{$groupIdx}/mapping", $data);
    }
}
