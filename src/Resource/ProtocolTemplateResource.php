<?php

declare(strict_types=1);

namespace miralsoft\docbee\api\Resource;

use miralsoft\docbee\api\DTO\ProtocolTemplateDTO;
use miralsoft\docbee\api\Query\QueryBuilder;

/**
 * Provides access to Docbee ProtocolTemplate records.
 *
 * @extends AbstractResource<ProtocolTemplateDTO>
 */
final class ProtocolTemplateResource extends AbstractResource
{
    protected string $endpoint = 'protocolTemplate';
    protected string $dtoClass = ProtocolTemplateDTO::class;
    protected string $listKey  = 'protocolTemplate';

    public function release(int $id): void { $this->http->put("{$this->endpoint}/{$id}/release", []); }
    public function revision(int $id): void { $this->http->put("{$this->endpoint}/{$id}/revision", []); }

    /** @return array<string, mixed> */
    public function getComponent(int $templateId): array          { return $this->http->get("{$this->endpoint}/{$templateId}/component"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateComponent(int $templateId, array $data): array { return $this->http->put("{$this->endpoint}/{$templateId}/component", $data); }
    /** @return array<string, mixed> */
    public function getGroupComponent(int $templateId, int $groupId): array          { return $this->http->get("{$this->endpoint}/{$templateId}/group/{$groupId}/component"); }
    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function updateGroupComponent(int $templateId, int $groupId, array $data): array { return $this->http->put("{$this->endpoint}/{$templateId}/group/{$groupId}/component", $data); }
}